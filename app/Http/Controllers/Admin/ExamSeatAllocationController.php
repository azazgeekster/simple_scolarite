<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamLocal;
use App\Models\ExamPeriod;
use App\Models\AcademicYear;
use App\Services\ExamSeatAllocationService;
use Illuminate\Http\Request;
use Exception;

class ExamSeatAllocationController extends Controller
{
    protected $allocationService;

    public function __construct(ExamSeatAllocationService $allocationService)
    {
        $this->allocationService = $allocationService;
    }

    /**
     * Display exam allocation dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $selectedYear = $request->get('year');

        if ($selectedYear) {
            $currentAcademicYear = $academicYears->firstWhere('start_year', $selectedYear);
        } else {
            $currentAcademicYear = $academicYears->firstWhere('is_current', true)
                                ?? $academicYears->first();
        }

        // Get exam periods for selected year
        $examPeriods = ExamPeriod::where('academic_year', $currentAcademicYear->start_year ?? date('Y'))
            ->orderBy('start_date', 'desc')
            ->get();

        // Get selected period
        $selectedPeriodId = $request->get('period');
        $selectedPeriod = $selectedPeriodId
            ? $examPeriods->find($selectedPeriodId)
            : $examPeriods->first();

        // Get exams for selected period
        $exams = collect();
        $stats = [
            'total_exams' => 0,
            'allocated_exams' => 0,
            'pending_exams' => 0,
            'total_students' => 0,
        ];

        if ($selectedPeriod) {
            $exams = Exam::where('exam_period_id', $selectedPeriod->id)
                ->with(['module', 'seatAssignments', 'examLocals'])
                ->orderBy('exam_date')
                ->orderBy('start_time')
                ->get();

            // Calculate stats
            $stats['total_exams'] = $exams->count();
            $stats['allocated_exams'] = $exams->filter(fn($exam) => $exam->hasSeatsAllocated())->count();
            $stats['pending_exams'] = $stats['total_exams'] - $stats['allocated_exams'];
            $stats['total_students'] = $exams->sum(fn($exam) => $exam->getAllocatedSeatsCount());
        }

        return view('admin.exam-seat-allocation.index', compact(
            'academicYears',
            'currentAcademicYear',
            'examPeriods',
            'selectedPeriod',
            'exams',
            'stats'
        ));
    }

    /**
     * Show allocation interface for a specific exam
     */
    public function show(Exam $exam)
    {
        $exam->load(['module', 'examPeriod', 'seatAssignments.student', 'seatAssignments.examLocal']);

        // Get available locals
        $availableLocals = ExamLocal::active()
            ->orderBy('capacity', 'desc')
            ->get();

        // Get allocation stats
        $stats = $this->allocationService->getAllocationStats($exam);

        // Get students who need seats
        $requiredStudents = $exam->getRequiredSeatsCount();

        return view('admin.exam-seat-allocation.show', compact(
            'exam',
            'availableLocals',
            'stats',
            'requiredStudents'
        ));
    }

    /**
     * Allocate seats for a specific exam
     */
    public function allocate(Request $request, Exam $exam)
    {
        $request->validate([
            'local_ids' => 'nullable|array',
            'local_ids.*' => 'exists:exam_locals,id',
            'clear_existing' => 'boolean',
        ]);

        try {
            $localIds = $request->input('local_ids');
            $clearExisting = $request->boolean('clear_existing', true);

            $result = $this->allocationService->allocateSeats(
                $exam,
                $localIds,
                $clearExisting
            );

            return redirect()
                ->route('admin.exam-seat-allocation.show', $exam)
                ->with('success', $result['message']);

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de l\'allocation: ' . $e->getMessage());
        }
    }

    /**
     * Clear all seat allocations for an exam
     */
    public function clear(Exam $exam)
    {
        try {
            $exam->clearSeatAllocations();

            return redirect()
                ->route('admin.exam-seat-allocation.show', $exam)
                ->with('success', 'Toutes les allocations ont été supprimées');

        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Bulk allocate seats for all exams in a period
     */
    public function bulkAllocate(Request $request, ExamPeriod $examPeriod)
    {
        $request->validate([
            'clear_existing' => 'boolean',
        ]);

        $clearExisting = $request->boolean('clear_existing', true);
        $exams = Exam::where('exam_period_id', $examPeriod->id)->get();

        $successCount = 0;
        $failedExams = [];

        foreach ($exams as $exam) {
            try {
                $this->allocationService->allocateSeats($exam, null, $clearExisting);
                $successCount++;
            } catch (Exception $e) {
                $failedExams[] = [
                    'exam' => $exam->module->code ?? 'Unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        $message = "{$successCount} examen(s) alloué(s) avec succès";
        if (!empty($failedExams)) {
            $message .= ". " . count($failedExams) . " échec(s)";
        }

        return redirect()
            ->route('admin.exam-seat-allocation.index', ['period' => $examPeriod->id])
            ->with($successCount > 0 ? 'success' : 'error', $message)
            ->with('failed_exams', $failedExams);
    }
}
