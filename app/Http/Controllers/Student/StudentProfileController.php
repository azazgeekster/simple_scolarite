<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Models\ProfileChangeRequest;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Str;

class StudentProfileController extends Controller
{
    /**
     * Display the student's profile and pending change requests.
     */
    public function show()
    {
        $student = auth('student')->user();
        $student->load('family'); // Eager load family relationship

        // Get pending profile change requests
        $pendingChanges = ProfileChangeRequest::where('student_id', $student->id)
            ->where('status', 'pending')
            ->get();

        return view('student.profile.show', compact('student', 'pendingChanges'));
    }

    /**
     * Show the form for editing the student's profile.
     */
    public function edit()
    {
        $student = auth('student')->user();
        $student->load('family'); // Eager load family relationship

        // Create family record if it doesn't exist
        if (!$student->family) {
            $student->family()->create([]);
            $student->load('family');
        }

        return view('student.profile.edit', compact('student'));
    }

    /**
     * Submit profile changes for administrative review.
     * Changes are saved as ProfileChangeRequest records, not directly to the student/family models.
     */
    public function update(UpdateStudentProfileRequest $request)
    {
        $data = $request->validated();
        $student = auth('student')->user();
        $student->load('family');

        $changesMade = 0;

        // Ensure family data is an array
        $familyData = isset($data['family']) && is_array($data['family']) ? $data['family'] : [];
        unset($data['family']);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Helper function to process fields and create/update ProfileChangeRequest
            $processField = function (string $fieldName, $newValue, $oldValue, string $prefix = '') use ($student, &$changesMade) {
                // Convert to string for comparison
                $newValueStr = $newValue !== null ? (string)$newValue : null;
                $oldValueStr = $oldValue !== null ? (string)$oldValue : null;

                // Check if value has changed
                if ($newValueStr !== $oldValueStr) {
                    ProfileChangeRequest::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'field_name' => $prefix . $fieldName,
                            'status' => 'pending'
                        ],
                        [
                            'old_value' => $oldValueStr,
                            'new_value' => $newValueStr,
                        ]
                    );
                    $changesMade++;
                }
            };

            // Process student fields
            $studentFields = [
                'prenom', 'nom', 'prenom_ar', 'nom_ar', 'cin', 'sexe', 'tel',
                'tel_urgence', 'date_naissance', 'lieu_naissance', 'lieu_naissance_ar',
                'nationalite', 'situation_familiale', 'situation_professionnelle',
                'adresse', 'adresse_ar', 'pays'
            ];

            foreach ($studentFields as $field) {
                if (array_key_exists($field, $data)) {
                    $processField($field, $data[$field], $student->$field);
                }
            }

            // Process family fields
            if (!empty($familyData)) {
                // Create family record if it doesn't exist (should be done in edit, but safety check)
                if (!$student->family) {
                    $student->family()->create([]);
                    $student->load('family');
                }

                $familyFields = [
                    'father_firstname', 'father_lastname', 'father_cin',
                    'father_birth_date', 'father_death_date', 'father_profession',
                    'mother_firstname', 'mother_lastname', 'mother_cin',
                    'mother_birth_date', 'mother_death_date', 'mother_profession',
                    'spouse_cin', 'spouse_death_date',
                    'handicap_code', 'handicap_type', 'handicap_card_number'
                ];

                foreach ($familyFields as $field) {
                    if (array_key_exists($field, $familyData)) {
                        $processField($field, $familyData[$field], $student->family->$field, 'family.');
                    }
                }
            }

            // Commit the transaction only if all requests were processed successfully
            DB::commit();

            if ($changesMade > 0) {
                return redirect()->route('student.profile.show')->with('message',
                    'Your changes have been submitted for review. You will be notified once they are approved.');
            } else {
                return redirect()->route('student.profile.show')->with('message',
                    'No changes detected.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            \Log::error("Profile update failed for student {$student->id}: " . $e->getMessage());

            return redirect()->route('student.profile.show')->with('error',
                'An unexpected error occurred while submitting your changes. Please try again.');
        }
    }


    /**
     * Update the student's profile photo using an external service for cropping.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
 
        $student = Auth::guard('student')->user();
        $photo = $request->file(key: 'avatar');
        $apiUrl = env('PYTHON_PHOTO_PROCESSOR_URL');

        if (!$apiUrl) {
             return back()->with('error', 'Photo processor service URL is not configured.');
        }

        try {
            // Send to Python API for face detection + cropping
            $response = Http::timeout(30)
                ->retry(3, 1000) // Retry up to 3 times with 1-second delay
                ->asMultipart()
                ->attach(
                    'avatar',
                    fopen($photo->getRealPath(), 'r'),
                    $photo->getClientOriginalName()
                )
                ->post($apiUrl);

            // Check if the response was successful
            if (! $response->successful() || ! $response->json('success')) {
                $errorMessage = $response->json('message') ?? 'Photo processing failed: Invalid photo or server error.';
                return back()->with('error', $errorMessage);
            }

            $base64Image = $response->json('cropped_base64');

            // Decode Base64 and store image
            $imageData = base64_decode($base64Image);
            $filename = 'students_avatars/'.Str::uuid().'.jpg';
            Storage::disk('public')->put($filename, $imageData);

            // Delete old photo
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }

            // Save new photo
            $student->avatar = $filename;
            $student->save();

            return back()->with('message', 'Profile photo updated successfully.');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error("Python API Connection Error: " . $e->getMessage());
            return back()->with('error', 'Could not connect to the photo processing service. Please try again later.');
        } catch (\Exception $e) {
            \Log::error("Photo update error: " . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred during photo update.');
        }
    }

    /**
     * Remove the student's profile photo.
     */
    public function deletePhoto()
    {
        $user = Auth::guard('student')->user();

        if ($user->avatar) {
            // Ensure the correct disk is used for deletion
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return response()->json(['message' => 'Profile photo removed.']);
    }
}