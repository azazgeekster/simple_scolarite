<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExamPeriodRequest;
use App\Models\ExamPeriod;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamPeriodController extends Controller
{
    /**
     * Display a listing of exam periods
     */
    public function index(Request $request)
    {
        // Get selected academic year or default to current
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $selectedYear = $request->get('year');

        if ($selectedYear) {
            $currentAcademicYear = $academicYears->firstWhere('start_year', $selectedYear);
        } else {
            $currentAcademicYear = $academicYears->firstWhere('is_current', true)
                                ?? $academicYears->first();
        }

        // Get periods for selected year
        $periods = ExamPeriod::where('academic_year', $currentAcademicYear->start_year ?? date('Y'))
            ->with(['exams' => function($q) {
                $q->select('exam_period_id', 'is_published', DB::raw('count(*) as total'))
                  ->groupBy('exam_period_id', 'is_published');
            }])
            ->orderBy('start_date', 'desc')
            ->get();

        // Add stats to each period
        $periods->each(function($period) {
            $stats = $period->getExamStats();
            $period->stats = $stats;
        });

        // Determine current period
        $activePeriod = ExamPeriod::active()->first();
        $currentPeriod = ExamPeriod::current()->first();

        $selectedYear = $currentAcademicYear->start_year ?? date('Y');

        return view('admin.exam-periods.index', compact(
            'periods',
            'academicYears',
            'selectedYear',
            'activePeriod',
            'currentPeriod'
        ));
    }

    /**
     * Show the form for creating a new period
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        return view('admin.exam-periods.create', compact('academicYears'));
    }

    /**
     * Store a newly created period
     */
    public function store(ExamPeriodRequest $request)
    {
        // Check for duplicate period
        $exists = ExamPeriod::where('academic_year', $request->academic_year)
            ->where('season', $request->season)
            ->where('session_type', $request->session_type)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('admin.exam-periods.create')
                ->withInput()
                ->with('error', 'Une période existe déjà pour cette année/saison/session.');
        }

        $period = ExamPeriod::create($request->validated());

        // Activate if requested
        if ($request->boolean('is_active')) {
            $period->activate();
        }

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', "Période d'examen créée avec succès: {$period->label}");
    }

    /**
     * Show the form for editing the specified period
     */
    public function edit(ExamPeriod $examPeriod)
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        return view('admin.exam-periods.edit', compact('examPeriod', 'academicYears'));
    }

    /**
     * Update the specified period
     */
    public function update(ExamPeriodRequest $request, ExamPeriod $examPeriod)
    {
        // Check for duplicate if academic_year/season/session_type changed
        if ($request->academic_year != $examPeriod->academic_year ||
            $request->season != $examPeriod->season ||
            $request->session_type != $examPeriod->session_type) {

            $exists = ExamPeriod::where('academic_year', $request->academic_year)
                ->where('season', $request->season)
                ->where('session_type', $request->session_type)
                ->where('id', '!=', $examPeriod->id)
                ->exists();

            if ($exists) {
                return redirect()
                    ->route('admin.exam-periods.edit', $examPeriod)
                    ->withInput()
                    ->with('error', 'Une période existe déjà pour cette année/saison/session.');
            }
        }

        $wasActive = $examPeriod->is_active;
        $examPeriod->update($request->validated());

        // Handle activation status change
        if ($request->boolean('is_active') && !$wasActive) {
            $examPeriod->activate();
        } elseif (!$request->boolean('is_active') && $wasActive) {
            $examPeriod->update(['is_active' => false]);
        }

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', 'Période mise à jour avec succès');
    }

    /**
     * Activate a period (and auto-publish exams if configured)
     */
    public function activate(ExamPeriod $examPeriod)
    {
        $examPeriod->activate();

        $message = "Période activée avec succès";
        if ($examPeriod->auto_publish_exams) {
            $count = $examPeriod->publishAllExams();
            $message .= " - {$count} examens publiés automatiquement";
        }

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', $message);
    }

    /**
     * Deactivate a period
     */
    public function deactivate(ExamPeriod $examPeriod)
    {
        $examPeriod->deactivate();

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', 'Période désactivée avec succès');
    }

    /**
     * Bulk publish all exams in period
     */
    public function publishExams(ExamPeriod $examPeriod)
    {
        $count = $examPeriod->publishAllExams();

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', "{$count} examens publiés avec succès");
    }

    /**
     * Bulk unpublish all exams in period
     */
    public function unpublishExams(ExamPeriod $examPeriod)
    {
        $count = $examPeriod->unpublishAllExams();

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', "{$count} examens dépubliés avec succès");
    }

    /**
     * Delete a period (only if no exams attached)
     */
    public function destroy(ExamPeriod $examPeriod)
    {
        if ($examPeriod->exams()->count() > 0) {
            return redirect()
                ->route('admin.exam-periods.index')
                ->with('error', 'Impossible de supprimer une période contenant des examens');
        }

        $examPeriod->delete();

        return redirect()
            ->route('admin.exam-periods.index')
            ->with('success', 'Période supprimée avec succès');
    }
}
