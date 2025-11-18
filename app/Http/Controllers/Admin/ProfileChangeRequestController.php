<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileChangeRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileChangeRequestController extends Controller
{
    /**
     * Display a listing of pending profile change requests.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $changeRequests = ProfileChangeRequest::with(['student', 'reviewer'])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.profile-change-requests.index', compact('changeRequests', 'status'));
    }

    /**
     * Display the specified profile change request grouped by student.
     */
    public function show($studentId)
    {
        $student = Student::with('family')->findOrFail($studentId);

        $pendingChanges = ProfileChangeRequest::where('student_id', $studentId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($pendingChanges->isEmpty()) {
            return redirect()->route('admin.profile-change-requests.index')
                ->with('error', 'No pending changes found for this student.');
        }

        return view('admin.profile-change-requests.show', compact('student', 'pendingChanges'));
    }

    /**
     * Approve a profile change request.
     */
    public function approve($id)
    {
        $changeRequest = ProfileChangeRequest::findOrFail($id);

        if ($changeRequest->status !== 'pending') {
            return back()->with('error', 'This change request has already been processed.');
        }

        try {
            DB::beginTransaction();

            $student = $changeRequest->student;
            $student->load('family');

            // Apply the change based on field type
            if (str_starts_with($changeRequest->field_name, 'family.')) {
                // Family field
                $fieldName = str_replace('family.', '', $changeRequest->field_name);

                if (!$student->family) {
                    $student->family()->create([]);
                    $student->load('family');
                }

                $student->family->$fieldName = $changeRequest->new_value;
                $student->family->save();
            } else {
                // Student field
                $student->{$changeRequest->field_name} = $changeRequest->new_value;
                $student->save();
            }

            // Update the change request status
            $changeRequest->status = 'approved';
            $changeRequest->reviewed_by = auth('admin')->id();
            $changeRequest->reviewed_at = now();
            $changeRequest->save();

            DB::commit();

            return back()->with('message', 'Change approved and applied successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile change approval failed: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while approving the change.');
        }
    }

    /**
     * Reject a profile change request.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $changeRequest = ProfileChangeRequest::findOrFail($id);

        if ($changeRequest->status !== 'pending') {
            return back()->with('error', 'This change request has already been processed.');
        }

        $changeRequest->status = 'rejected';
        $changeRequest->reviewed_by = auth('admin')->id();
        $changeRequest->reviewed_at = now();
        $changeRequest->rejection_reason = $request->rejection_reason;
        $changeRequest->save();

        return back()->with('message', 'Change request rejected.');
    }

    /**
     * Approve all pending changes for a student.
     */
    public function approveAll($studentId)
    {
        try {
            DB::beginTransaction();

            $student = Student::with('family')->findOrFail($studentId);
            $pendingChanges = ProfileChangeRequest::where('student_id', $studentId)
                ->where('status', 'pending')
                ->get();

            if ($pendingChanges->isEmpty()) {
                return back()->with('error', 'No pending changes found for this student.');
            }

            foreach ($pendingChanges as $changeRequest) {
                // Apply the change
                if (str_starts_with($changeRequest->field_name, 'family.')) {
                    $fieldName = str_replace('family.', '', $changeRequest->field_name);

                    if (!$student->family) {
                        $student->family()->create([]);
                        $student->load('family');
                    }

                    $student->family->$fieldName = $changeRequest->new_value;
                } else {
                    $student->{$changeRequest->field_name} = $changeRequest->new_value;
                }

                // Update request status
                $changeRequest->status = 'approved';
                $changeRequest->reviewed_by = auth('admin')->id();
                $changeRequest->reviewed_at = now();
                $changeRequest->save();
            }

            // Save all changes
            $student->save();
            if ($student->family) {
                $student->family->save();
            }

            DB::commit();

            return redirect()->route('admin.profile-change-requests.index')
                ->with('message', "All {$pendingChanges->count()} changes approved and applied successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk profile change approval failed: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while approving the changes.');
        }
    }

    /**
     * Reject all pending changes for a student.
     */
    public function rejectAll(Request $request, $studentId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $pendingChanges = ProfileChangeRequest::where('student_id', $studentId)
            ->where('status', 'pending')
            ->get();

        if ($pendingChanges->isEmpty()) {
            return back()->with('error', 'No pending changes found for this student.');
        }

        foreach ($pendingChanges as $changeRequest) {
            $changeRequest->status = 'rejected';
            $changeRequest->reviewed_by = auth('admin')->id();
            $changeRequest->reviewed_at = now();
            $changeRequest->rejection_reason = $request->rejection_reason;
            $changeRequest->save();
        }

        return redirect()->route('admin.profile-change-requests.index')
            ->with('message', "All {$pendingChanges->count()} changes rejected.");
    }

    /**
     * Display history of approved and rejected profile change requests.
     */
    public function history(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $query = ProfileChangeRequest::with(['student', 'reviewer'])
            ->whereIn('status', ['approved', 'rejected']);

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('cne', 'like', "%{$search}%")
                        ->orWhere('apogee', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%");
                })
                ->orWhere('field_name', 'like', "%{$search}%")
                ->orWhere('old_value', 'like', "%{$search}%")
                ->orWhere('new_value', 'like', "%{$search}%");
            });
        }

        $changeRequests = $query->orderBy('reviewed_at', 'desc')->paginate(20);

        // Get counts for tabs
        $totalCount = ProfileChangeRequest::whereIn('status', ['approved', 'rejected'])->count();
        $approvedCount = ProfileChangeRequest::where('status', 'approved')->count();
        $rejectedCount = ProfileChangeRequest::where('status', 'rejected')->count();

        return view('admin.profile-change-requests.history', compact(
            'changeRequests',
            'status',
            'totalCount',
            'approvedCount',
            'rejectedCount'
        ));
    }
}
