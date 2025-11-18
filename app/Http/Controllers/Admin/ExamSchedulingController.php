<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamPeriod;
use App\Models\ExamRoomAllocation;
use App\Models\Filiere;
use App\Models\Local;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            \Log::error('Error creating exam: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to schedule exam. Please try again.');
        }
    }

    public function roomAllocation($examId)
    {
        $exam = Exam::with(['module', 'convocations'])->findOrFail($examId);
        $totalStudents = $exam->convocations()->count();
        $availableLocals = Local::active()->orderBy('capacity', 'desc')->get();

        return view('admin.exam-scheduling.room-allocation', compact('exam', 'totalStudents', 'availableLocals'));
    }

    public function allocateRooms(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);

        // Handle room removal
        if ($request->has('remove_local_id')) {
            try {
                $allocation = ExamRoomAllocation::where('exam_id', $examId)
                    ->where('local_id', $request->remove_local_id)
                    ->first();

                if ($allocation) {
                    $allocation->delete();
                    return redirect()
                        ->route('admin.exam-scheduling.room-allocation', $examId)
                        ->with('success', 'Room allocation removed successfully!');
                }

                return back()->with('error', 'Room allocation not found.');
            } catch (\Exception $e) {
                \Log::error('Error removing room allocation: ' . $e->getMessage());
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
                if (!$local->isAvailable($exam->exam_date, $exam->start_time, $exam->end_time, $exam->id)) {
                    throw new \Exception("Room {$local->name} is not available at the scheduled exam time.");
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
            }

            DB::commit();

            $totalAllocated = $exam->fresh()->total_allocated_seats;
            $totalStudents = $exam->convocations()->count();

            if ($totalAllocated >= $totalStudents) {
                return redirect()
                    ->route('admin.exam-scheduling.room-allocation', $examId)
                    ->with('success', "Room allocation saved successfully! All {$totalStudents} students are now allocated.");
            } else {
                $remaining = $totalStudents - $totalAllocated;
                return redirect()
                    ->route('admin.exam-scheduling.room-allocation', $examId)
                    ->with('success', "Room allocation saved! {$remaining} students still need room assignment.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error allocating rooms: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
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

                if (!empty($scheduledModuleIds)) {
                    $query->whereNotIn('id', $scheduledModuleIds);
                }
            }

            $modules = $query->orderBy('code')->get(['id', 'code', 'label as label']);

            return response()->json($modules);
        } catch (\Exception $e) {
            \Log::error('Error loading modules: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load modules'], 500);
        }
    }
}
