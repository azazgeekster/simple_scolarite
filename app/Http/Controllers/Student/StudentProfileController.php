<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentProfileRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Str;

class StudentProfileController extends Controller
{
    public function show()
    {
        $student = auth('student')->user();

        return view('student.profile.show', compact('student'));
    }


    public function edit()
    {
        $student = auth('student')->user();

        return view('student.profile.edit', compact('student'));
    }

    public function update(UpdateStudentProfileRequest $request)
    {
        $data = $request->validated();
        // $request->validated();
        $student = auth('student')->user();
        \Log::info("Before altering date_naissance: ".$data['date_naissance']);
        if (isset($data['date_naissance'])) {
            \Log::info("Received date_naissance: ".$data['date_naissance']);
        }

        \Log::info("after altering date_naissance: ".$data['date_naissance']);

        $student->update($data);

        return redirect()->route('student.profile.show')->with('message', 'Profile Updated Successfully!');
    }


    public function updatePhoto(Request $request)
    {

        /// handle if python API is not working
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $student = Auth::guard('student')->user();
        $photo = $request->file('avatar');

        // Send to Python API for face detection + cropping
        $response = Http::timeout(30)
            ->asMultipart()
            ->attach(
                'avatar',
                file_get_contents($photo->path()),
                $photo->getClientOriginalName()
            )
            ->post('http://127.0.0.1:5001/crop-photo');


        if (! $response->ok() || ! $response->json('success')) {
            return back()->with('error', $response->json('message') ?? 'Invalid photo');
        }

        $base64Image = $response->json('cropped_base64');

        // Decode Base64 and store image
        $imageData = base64_decode($base64Image);
        $filename = 'profile_pictures/'.Str::uuid().'.jpg';
        Storage::disk('public')->put($filename, $imageData);

        // Delete old photo
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }

        // Save new photo
        $student->avatar = $filename;
        \Log::warning("filename ".$filename);
        $student->save();

        return back()->with('message', 'Profile photo updated successfully.');
    }

    public function deletePhoto()
    {
        $user = Auth::guard('student')->user();

        if ($user->avatar) {
            Storage::delete('public/'.$user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return response()->json(['message' => 'Profile photo removed.']);
    }


}

