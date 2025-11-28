<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamConvocation;
use App\Models\ExamPeriod;
use App\Models\ExamRoomAllocation;
use App\Models\Filiere;
use App\Models\Local;
use App\Models\Module;
use App\Models\StudentModuleEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class ExamSchedulingController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with(['module.filiere', 'examPeriod', 'roomAllocations.local']);

        // Filter by exam period
        if ($request->filled('exam_period_id')) {
            $query->where('exam_period_id', $request->exam_period_id);
        }

        // Filter by session type
        if ($request->filled('session_type')) {
            $query->where('session_type', $request->session_type);
        }

        // Filter by filiere
        if ($request->filled('filiere_id')) {
            $query->whereHas('module', function ($q) use ($request) {
                $q->where('filiere_id', $request->filiere_id);
            });
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('exam_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('exam_date', '<=', $request->date_to);
        }

        // Filter by allocation status
        if ($request->filled('allocation_status')) {
            if ($request->allocation_status === 'allocated') {
                $query->has('roomAllocations');
            } elseif ($request->allocation_status === 'not_allocated') {
                $query->doesntHave('roomAllocations');
            }
        }

        // Search by module name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('module', function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                    ->orWhere('label_ar', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'exam_date');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir)->orderBy('start_time', $sortDir);

        $exams = $query->paginate(20)->withQueryString();
        $examPeriods = ExamPeriod::orderBy('start_date', 'desc')->get();
        $filieres = Filiere::orderBy('label_fr')->get();

        // Statistics
        $stats = [
            'total' => Exam::count(),
            'allocated' => Exam::has('roomAllocations')->count(),
            'not_allocated' => Exam::doesntHave('roomAllocations')->count(),
            'upcoming' => Exam::where('exam_date', '>=', now()->toDateString())->count(),
        ];

        return view('admin.exam-scheduling.index', compact('exams', 'examPeriods', 'filieres', 'stats'));
    }

    public function create()
    {
        $examPeriods = ExamPeriod::orderBy('start_date', 'desc')->get();
        $filieres = Filiere::orderBy('label_fr')->get();

        return view('admin.exam-scheduling.create', compact('examPeriods', 'filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_period_id' => 'required|exists:exam_periods,id',
            'module_id' => 'required|exists:modules,id',
            'session_type' => 'required|in:normal,rattrapage',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        try {
            DB::beginTransaction();

            // Get exam period and module for additional data
            $examPeriod = ExamPeriod::findOrFail($validated['exam_period_id']);
            $module = Module::findOrFail($validated['module_id']);

            // Create the exam
            $exam = Exam::create([
                'exam_period_id' => $validated['exam_period_id'],
                'module_id' => $validated['module_id'],
                'session_type' => $validated['session_type'],
                'exam_date' => $validated['exam_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'academic_year' => $examPeriod->academic_year,
                'semester' => $module->semester,
                'season' => $examPeriod->season,
                'is_published' => false,
            ]);

            // Automatically create convocations for all students enrolled in this module
            $this->createConvocationsForExam($exam, $module, $examPeriod);

            DB::commit();

            // Handle different redirect options
            if ($request->has('action')) {
                switch ($request->input('action')) {
                    case 'schedule_and_allocate':
                        return redirect()
                            ->route('admin.exam-scheduling.room-allocation', $exam->id)
                            ->with('success', 'Exam scheduled successfully! Now allocate rooms.');

                    case 'schedule_and_new':
                        return redirect()
                            ->route('admin.exam-scheduling.create')
                            ->with('success', 'Exam scheduled successfully! Add another exam.');

                    default:
                        return redirect()
                            ->route('admin.exam-scheduling.index')
                            ->with('success', 'Exam scheduled successfully!');
                }
            }

            return redirect()
                ->route('admin.exam-scheduling.index')
                ->with('success', 'Exam scheduled successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating exam: '.$e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to schedule exam. Please try again.');
        }
    }

    public function roomAllocation($examId)
    {
        $exam = Exam::with(['module', 'convocations', 'examPeriod'])->findOrFail($examId);
        $totalStudents = $exam->convocations()->count();

        // Count students who have already been assigned to rooms (have local_id)
        $assignedStudents = $exam->convocations()->whereNotNull('local_id')->count();

        // Get locals that are NOT already allocated to other exams in the same period
        $usedLocalIds = ExamRoomAllocation::whereHas('exam', function ($q) use ($exam) {
            $q->where('exam_period_id', $exam->exam_period_id)
                ->where('id', '!=', $exam->id);
        })->pluck('local_id')->toArray();

        $availableLocals = Local::active()
            ->whereNotIn('id', $usedLocalIds)
            ->orderBy('capacity', 'desc')
            ->get();

        return view('admin.exam-scheduling.room-allocation', compact('exam', 'totalStudents', 'assignedStudents', 'availableLocals'));
    }

    /**
     * Rebalance students across existing room allocations
     */
    public function rebalanceRooms(Request $request, $examId)
    {
        $exam = Exam::with(['roomAllocations.local', 'convocations'])->findOrFail($examId);

        if ($exam->roomAllocations->count() < 2) {
            return back()->with('error', 'Need at least 2 rooms to rebalance.');
        }

        $mode = $request->input('mode', 'auto');
        $totalStudents = $exam->convocations()->whereNotNull('local_id')->count();

        if ($totalStudents === 0) {
            return back()->with('error', 'No students assigned to rooms yet.');
        }

        try {
            DB::beginTransaction();

            if ($mode === 'auto') {
                // Auto-balance: distribute students proportionally based on room capacity
                $allocations = $exam->roomAllocations()->with('local')->orderBy('id')->get();
                $totalCapacity = $allocations->sum('local.capacity');

                $studentsRemaining = $totalStudents;
                $newAllocations = [];

                foreach ($allocations as $index => $allocation) {
                    if ($index === $allocations->count() - 1) {
                        // Last room gets remaining students
                        $newAllocations[$allocation->local_id] = $studentsRemaining;
                    } else {
                        // Distribute proportionally based on capacity
                        $proportion = $allocation->local->capacity / $totalCapacity;
                        $studentsForRoom = min(
                            round($totalStudents * $proportion),
                            $allocation->local->capacity,
                            $studentsRemaining
                        );
                        $newAllocations[$allocation->local_id] = $studentsForRoom;
                        $studentsRemaining -= $studentsForRoom;
                    }
                }
            } else {
                // Manual mode: use provided allocations
                $validated = $request->validate([
                    'allocations' => 'required|array',
                    'allocations.*' => 'required|integer|min:0',
                ]);

                $newAllocations = $validated['allocations'];

                // Validate total matches
                $totalAllocated = array_sum($newAllocations);
                if ($totalAllocated !== $totalStudents) {
                    throw new \Exception("Total allocated seats ({$totalAllocated}) must equal total students ({$totalStudents}).");
                }

                // Validate each room doesn't exceed capacity
                foreach ($newAllocations as $localId => $seats) {
                    $allocation = $exam->roomAllocations()->where('local_id', $localId)->first();
                    if ($allocation && $seats > $allocation->local->capacity) {
                        throw new \Exception("Room {$allocation->local->name} cannot accommodate {$seats} students. Maximum: {$allocation->local->capacity}");
                    }
                }
            }

            // Update room allocations with new seat counts
            foreach ($newAllocations as $localId => $seats) {
                ExamRoomAllocation::where('exam_id', $examId)
                    ->where('local_id', $localId)
                    ->update(['allocated_seats' => $seats]);
            }

            // Reassign students to rooms with new distribution
            $this->assignStudentsToRooms($exam);

            DB::commit();

            // Build success message
            $message = "✓ Rooms rebalanced successfully!\n\n📊 New Distribution:\n";
            foreach ($newAllocations as $localId => $seats) {
                $local = Local::find($localId);
                if ($local) {
                    $utilization = ($seats / $local->capacity) * 100;
                    $message .= "• {$local->name}: {$seats} students (" . number_format($utilization, 0) . "% utilization)\n";
                }
            }

            return redirect()
                ->route('admin.exam-scheduling.room-allocation', $examId)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error rebalancing rooms: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function allocateRooms(Request $request, $examId)
    {
        $exam = Exam::with(['examPeriod'])->findOrFail($examId);

        // Handle room removal
        if ($request->has('remove_local_id')) {
            try {
                $allocation = ExamRoomAllocation::where('exam_id', $examId)
                    ->where('local_id', $request->remove_local_id)
                    ->first();

                if ($allocation) {
                    // Clear student assignments for this room
                    ExamConvocation::where('exam_id', $examId)
                        ->where('local_id', $request->remove_local_id)
                        ->update(['local_id' => null, 'n_examen' => null]);

                    $allocation->delete();

                    return redirect()
                        ->route('admin.exam-scheduling.room-allocation', $examId)
                        ->with('success', 'Room allocation removed successfully! Student seat assignments have been cleared.');
                }

                return back()->with('error', 'Room allocation not found.');
            } catch (\Exception $e) {
                \Log::error('Error removing room allocation: '.$e->getMessage());
                return back()->with('error', 'Failed to remove room allocation.');
            }
        }

        // Handle new allocations
        $validated = $request->validate([
            'allocations' => 'required|array|min:1',
            'allocations.*' => 'required|integer|min:1',
        ], [
            'allocations.required' => 'Please select at least one room.',
            'allocations.min' => 'Please select at least one room.',
            'allocations.*.required' => 'Please enter the number of seats for all selected rooms.',
            'allocations.*.integer' => 'Seat count must be a valid number.',
            'allocations.*.min' => 'Seat count must be at least 1.',
        ]);

        try {
            DB::beginTransaction();

            $roomsAllocated = [];
            $totalSeatsAllocated = 0;

            foreach ($validated['allocations'] as $localId => $allocatedSeats) {
                // Skip if allocated seats is 0 or empty
                if (empty($allocatedSeats) || $allocatedSeats <= 0) {
                    continue;
                }

                // Verify the room exists and has enough capacity
                $local = Local::findOrFail($localId);

                if ($allocatedSeats > $local->capacity) {
                    throw new \Exception("Room {$local->name} cannot accommodate {$allocatedSeats} seats. Maximum capacity: {$local->capacity}");
                }

                // Check if room is available for this exam time
                if (! $local->isAvailable($exam->exam_date, $exam->start_time, $exam->end_time, $exam->id)) {
                    throw new \Exception("Room {$local->name} is not available at the scheduled exam time.");
                }

                // Check if room is already used in another exam during the same period
                $conflictingExam = Exam::where('exam_period_id', $exam->exam_period_id)
                    ->where('id', '!=', $examId)
                    ->whereHas('roomAllocations', function ($q) use ($localId) {
                        $q->where('local_id', $localId);
                    })
                    ->with('module')
                    ->first();

                if ($conflictingExam) {
                    throw new \Exception("Room {$local->name} is already allocated to another exam in this period: {$conflictingExam->module->label} ({$conflictingExam->exam_date->format('d/m/Y')})");
                }

                // Create or update the allocation
                ExamRoomAllocation::updateOrCreate(
                    [
                        'exam_id' => $examId,
                        'local_id' => $localId,
                    ],
                    [
                        'allocated_seats' => $allocatedSeats,
                    ]
                );

                $roomsAllocated[] = $local->name;
                $totalSeatsAllocated += $allocatedSeats;
            }

            // Assign students to rooms and generate seat numbers
            $this->assignStudentsToRooms($exam);

            DB::commit();

            $totalStudents = $exam->convocations()->count();
            $studentsAssigned = ExamConvocation::where('exam_id', $examId)->whereNotNull('local_id')->count();

            // Build comprehensive success message
            $message = "✓ Room allocation saved successfully!\n\n";
            $message .= "📊 Summary:\n";
            $message .= "• ".count($roomsAllocated)." room(s) allocated: ".implode(', ', $roomsAllocated)."\n";
            $message .= "• Total seats: {$totalSeatsAllocated}\n";
            $message .= "• Students assigned: {$studentsAssigned} / {$totalStudents}\n";

            if ($studentsAssigned >= $totalStudents) {
                $message .= "\n✓ All students have been assigned seat numbers!";
            } else {
                $remaining = $totalStudents - $studentsAssigned;
                $message .= "\n⚠ {$remaining} student(s) still need room assignment.";
            }

            return redirect()
                ->route('admin.exam-scheduling.room-allocation', $examId)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error allocating rooms: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Create exam convocations for students
     * - Normal exams: All enrolled students
     * - Rattrapage exams: Only students with RATT status or justified absences
     */
    private function createConvocationsForExam($exam, $module, $examPeriod)
    {
        $convocationsCreated = 0;

        if ($exam->session_type === 'rattrapage') {
            // For rattrapage exams: Only convocate students with RATT or justified ABI
            // Get students with RATT status from normal session
            $rattGrades = \App\Models\ModuleGrade::where('result', 'RATT')
                ->where('session', 'normal')
                ->whereHas('moduleEnrollment', function($q) use ($module, $examPeriod) {
                    $q->where('module_id', $module->id)
                      ->whereHas('programEnrollment', function($q2) use ($examPeriod) {
                          $q2->where('academic_year', $examPeriod->academic_year)
                             ->where('enrollment_status', 'active');
                      });
                })
                ->with('moduleEnrollment')
                ->get();

            foreach ($rattGrades as $grade) {
                ExamConvocation::create([
                    'exam_id' => $exam->id,
                    'student_module_enrollment_id' => $grade->module_enrollment_id,
                ]);
                $convocationsCreated++;
            }

            \Log::info("Created {$convocationsCreated} rattrapage convocations for exam {$exam->id} (Module: {$module->label}) - Only RATT students");

        } else {
            // For normal exams: Convocate all enrolled students
            $enrollments = StudentModuleEnrollment::where('module_id', $module->id)
                ->where('semester', $module->semester)
                ->whereHas('programEnrollment', function ($query) use ($examPeriod) {
                    $query->where('academic_year', $examPeriod->academic_year)
                        ->where('enrollment_status', 'active');
                })
                ->get();

            foreach ($enrollments as $enrollment) {
                ExamConvocation::create([
                    'exam_id' => $exam->id,
                    'student_module_enrollment_id' => $enrollment->id,
                ]);
                $convocationsCreated++;
            }

            \Log::info("Created {$convocationsCreated} normal session convocations for exam {$exam->id} (Module: {$module->label}) - All enrolled students");
        }

        return $convocationsCreated;
    }

    /**
     * Assign students to rooms and generate seat numbers
     * Each local gets sequential seat numbers starting from 1
     */
    private function assignStudentsToRooms($exam)
    {
        // First, clear all existing assignments to ensure clean reassignment
        ExamConvocation::where('exam_id', $exam->id)
            ->update(['local_id' => null, 'n_examen' => null]);

        // Get all room allocations for this exam, ordered by local_id for consistency
        $roomAllocations = $exam->roomAllocations()->with('local')->orderBy('local_id')->get();

        // Get all convocations for this exam (ordered by id for consistency)
        $convocations = $exam->convocations()->orderBy('id')->get();

        $convocationIndex = 0;
        $totalConvocations = $convocations->count();

        // Assign students to each room sequentially
        foreach ($roomAllocations as $allocation) {
            $seatsToFill = $allocation->allocated_seats;
            $seatNumber = 1; // Start from 1 for each local
            $localCode = $allocation->local->code; // Get the local code (e.g., "FS1")

            // Fill seats in this local
            while ($seatNumber <= $seatsToFill && $convocationIndex < $totalConvocations) {
                $convocation = $convocations[$convocationIndex];

                // Generate n_examen in format: LOCALCODE-NUMBER (e.g., "FS1-1", "FS1-2", etc.)
                $nExamen = $localCode . '-' . $seatNumber;

                // Assign room and seat number
                $convocation->update([
                    'local_id' => $allocation->local_id,
                    'n_examen' => $nExamen,
                ]);

                $seatNumber++;
                $convocationIndex++;
            }
        }
    }

    public function getModules(Request $request)
    {
        try {
            $query = Module::query();

            if ($request->filled('filiere_id')) {
                $query->where('filiere_id', $request->filiere_id);
            }

            if ($request->filled('semester')) {
                $query->where('semester', $request->semester);
            }

            // Exclude modules that already have exams scheduled for this period
            if ($request->filled('exam_period_id')) {
                $scheduledModuleIds = Exam::where('exam_period_id', $request->exam_period_id)
                    ->pluck('module_id')
                    ->toArray();

                if (! empty($scheduledModuleIds)) {
                    $query->whereNotIn('id', $scheduledModuleIds);
                }
            }

            $modules = $query->orderBy('code')->get(['id', 'code', 'label as label']);

            return response()->json($modules);
        } catch (\Exception $e) {
            \Log::error('Error loading modules: '.$e->getMessage());
            return response()->json(['error' => 'Failed to load modules'], 500);
        }
    }

    public function destroy($examId)
    {
        try {
            $exam = Exam::with(['module', 'roomAllocations'])->findOrFail($examId);

            // Check if exam has room allocations
            $hasAllocations = $exam->roomAllocations->isNotEmpty();
            $moduleName = $exam->module->label;
            $examDate = $exam->exam_date->format('d/m/Y');

            DB::beginTransaction();

            // Delete convocations first (cascading)
            $exam->convocations()->delete();

            // Delete room allocations
            $exam->roomAllocations()->delete();

            // Delete the exam
            $exam->delete();

            DB::commit();

            $message = "Exam deleted successfully!\n\n";
            $message .= "📋 Details:\n";
            $message .= "• Module: {$moduleName}\n";
            $message .= "• Date: {$examDate}\n";
            if ($hasAllocations) {
                $message .= "• All room allocations and student assignments have been removed.";
            }

            return redirect()
                ->route('admin.exam-scheduling.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting exam: '.$e->getMessage());
            return back()->with('error', 'Failed to delete exam. Please try again.');
        }
    }

    public function downloadPV($examId)
    {
        try {
            $exam = Exam::with([
                'module.filiere',
                'examPeriod',
                'roomAllocations.local',
                'convocations.studentModuleEnrollment.student',
                'convocations.local'
            ])->findOrFail($examId);

            // Ensure all convocations have their local relationship loaded
            $exam->load('convocations.local');

            if ($exam->convocations->isEmpty()) {
                return back()->with('error', 'Cannot generate PV: No student convocations for this exam.');
            }

            // Filter out convocations without room assignment and group by local_id
            $assignedConvocations = $exam->convocations->filter(function($convocation) {
                return $convocation->local_id !== null;
            });

            if ($assignedConvocations->isEmpty()) {
                return back()->with('error', 'Cannot generate PV: No students have been assigned to rooms yet. Please allocate rooms first.');
            }

            $localGroups = $assignedConvocations->groupBy('local_id');
            $logoPath = public_path('storage/logos/logo_fac_fr.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoMime = mime_content_type($logoPath);
                $logoBase64 = 'data:'.$logoMime.';base64,'.$logoData;
            }

            $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
            // Configure mPDF with proper Arabic font support using DejaVu
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 20,
                'margin_right' => 20,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'default_font' => 'roboto',
                'default_font_size' => 10,
                'orientation' => 'P',
                'tempDir' => storage_path('app/mpdf_temp'),
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'useAdobeCJK' => false,
                'useKerning' => true,
                'fontDir' => array_merge($fontDirs, [
                    public_path('fonts'),
                ]),
                'fontdata' => $fontData + [

                    'roboto'=>[
                     'R'=>'brawler/brawler-regular.ttf',
                     'B'=>'brawler/brawler-bold.ttf',
                    ]
                ],
            ]);

            // Render HTML
            $html = view('admin.exam-scheduling.pv-template', [
                'exam' => $exam,
                'logoBase64' =>$logoBase64,
                'localGroups' => $localGroups,
            ])->render();

            $mpdf->WriteHTML($html);

            $fileName = 'PV_'
                .str_replace(' ', '_', $exam->module->code)
                .'_'.$exam->exam_date->format('Y-m-d')
                .'.pdf';

            // 🔹 Preview instead of download → use 'I'
            return response($mpdf->Output($fileName, 'I'))
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            \Log::error('Error generating PV: '.$e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Failed to generate PV: '.$e->getMessage());
        }
    }

}

