<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Student;
use App\Models\StudentModuleEnrollment;
use App\Models\StudentProgramEnrollment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class StudentManagementController extends Controller
{
    /**
     * Display student search and listing page.
     */
    public function index(Request $request)
    {
        $query = Student::with(['programEnrollments.filiere', 'programEnrollments.academicYear']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('cne', 'like', "%{$search}%")
                    ->orWhere('apogee', 'like', "%{$search}%")
                    ->orWhere('cin', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by filiere
        if ($request->filled('filiere_id')) {
            $query->whereHas('programEnrollments', function ($q) use ($request) {
                $q->where('filiere_id', $request->filiere_id);
            });
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->whereHas('programEnrollments', function ($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by scholarship
        if ($request->filled('boursier')) {
            $query->where('boursier', $request->boursier === '1');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $students = $query->paginate(20)->withQueryString();

        // Get filter options
        $filieres = Filiere::orderBy('label_fr')->get();
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();

        // Statistics
        $stats = [
            'total' => Student::count(),
            'active' => Student::where('is_active', true)->count(),
            'inactive' => Student::where('is_active', false)->count(),
            'boursiers' => Student::where('boursier', true)->count(),
        ];

        return view('admin.students.index', compact(
            'students',
            'filieres',
            'academicYears',
            'stats'
        ));
    }

    /**
     * Display student details with full management options.
     */
    public function show($id)
    {
        $student = Student::with([
            'bacInfo',
            'family',
            'programEnrollments' => function ($q) {
                $q->with(['filiere', 'academicYear'])->orderBy('academic_year', 'desc');
            },
            'moduleEnrollments' => function ($q) {
                $q->with(['module.filiere', 'module.professor', 'grades', 'programEnrollment'])
                    ->orderBy('registration_year', 'desc')
                    ->orderBy('semester');
            },
            'demandes' => function ($q) {
                $q->with('document')->orderBy('created_at', 'desc')->limit(10);
            },
            'examConvocations' => function ($q) {
                $q->with(['exam.module', 'exam.locals'])->orderBy('created_at', 'desc')->limit(10);
            },
        ])->findOrFail($id);

        // Get available modules for enrollment (based on current program)
        $currentEnrollment = $student->currentProgramEnrollment();
        $availableModules = collect();

        if ($currentEnrollment) {
            $enrolledModuleIds = $student->moduleEnrollments->pluck('module_id')->toArray();
            $availableModules = Module::where('filiere_id', $currentEnrollment->filiere_id)
                ->whereNotIn('id', $enrolledModuleIds)
                ->orderBy('semester')
                ->orderBy('label')
                ->get();
        }

        // Get all filieres for program enrollment
        $filieres = Filiere::orderBy('label_fr')->get();
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();

        // Calculate some stats
        $moduleStats = [
            'total' => $student->moduleEnrollments->count(),
            'passed' => $student->moduleEnrollments->where('final_result', 'validé')->count() +
                       $student->moduleEnrollments->where('final_result', 'validé après rattrapage')->count(),
            'failed' => $student->moduleEnrollments->where('final_result', 'non validé')->count(),
            'pending' => $student->moduleEnrollments->whereNull('final_result')->count(),
        ];

        return view('admin.students.show', compact(
            'student',
            'availableModules',
            'filieres',
            'academicYears',
            'moduleStats'
        ));
    }

    /**
     * Toggle student active status.
     */
    public function toggleStatus($id)
    {
        $student = Student::findOrFail($id);

        try {
            $student->is_active = !$student->is_active;
            $student->save();

            $status = $student->is_active ? 'activé' : 'désactivé';
            return back()->with('success', "Compte étudiant {$status} avec succès.");

        } catch (\Exception $e) {
            \Log::error('Error toggling student status: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la modification du statut.');
        }
    }

    /**
     * Reset student password.
     */
    public function resetPassword(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $student->password = Hash::make($validated['password']);
            $student->save();

            return back()->with('success', 'Mot de passe réinitialisé avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error resetting student password: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la réinitialisation du mot de passe.');
        }
    }

    /**
     * Update student basic info.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'tel' => 'nullable|string|max:20',
            'tel_urgence' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:500',
            'boursier' => 'boolean',
        ]);

        try {
            $student->update($validated);

            return back()->with('success', 'Informations mises à jour avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error updating student: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Enroll student in a module.
     */
    public function enrollModule(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'semester' => 'required|string',
        ]);

        $currentEnrollment = $student->currentProgramEnrollment();

        if (!$currentEnrollment) {
            return back()->with('error', 'L\'étudiant n\'a pas d\'inscription programme active.');
        }

        // Check if already enrolled
        $existing = StudentModuleEnrollment::where('student_id', $id)
            ->where('module_id', $validated['module_id'])
            ->where('program_enrollment_id', $currentEnrollment->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'L\'étudiant est déjà inscrit à ce module.');
        }

        try {
            StudentModuleEnrollment::create([
                'student_id' => $id,
                'program_enrollment_id' => $currentEnrollment->id,
                'module_id' => $validated['module_id'],
                'semester' => $validated['semester'],
                'registration_year' => now()->year,
                'attempt_number' => 1,
            ]);

            return back()->with('success', 'Étudiant inscrit au module avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error enrolling student in module: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'inscription au module.');
        }
    }

    /**
     * Unenroll student from a module.
     */
    public function unenrollModule($studentId, $enrollmentId)
    {
        $enrollment = StudentModuleEnrollment::where('student_id', $studentId)
            ->where('id', $enrollmentId)
            ->firstOrFail();

        // Check if there are grades
        if ($enrollment->grades()->exists()) {
            return back()->with('error', 'Impossible de désinscrire : des notes existent pour ce module.');
        }

        try {
            $enrollment->delete();

            return back()->with('success', 'Étudiant désinscrit du module avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error unenrolling student from module: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la désinscription.');
        }
    }

    /**
     * Add program enrollment.
     */
    public function enrollProgram(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'academic_year' => 'required|integer',
            'year_in_program' => 'required|integer|min:1|max:5',
            'diploma_level' => 'required|in:deug,licence,master,doctorat',
            'enrollment_status' => 'required|in:active,completed,suspended,withdrawn',
        ]);

        // Check for existing enrollment in same year
        $existing = StudentProgramEnrollment::where('student_id', $id)
            ->where('academic_year', $validated['academic_year'])
            ->first();

        if ($existing) {
            return back()->with('error', 'L\'étudiant a déjà une inscription pour cette année académique.');
        }

        try {
            StudentProgramEnrollment::create([
                'student_id' => $id,
                'filiere_id' => $validated['filiere_id'],
                'academic_year' => $validated['academic_year'],
                'registration_year' => now()->year,
                'year_in_program' => $validated['year_in_program'],
                'diploma_level' => $validated['diploma_level'],
                'diploma_year' => $validated['year_in_program'],
                'enrollment_status' => $validated['enrollment_status'],
                'enrollment_date' => now(),
            ]);

            return back()->with('success', 'Inscription programme ajoutée avec succès.');

        } catch (\Exception $e) {
            \Log::error('Error adding program enrollment: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'ajout de l\'inscription.');
        }
    }

    /**
     * Update program enrollment status.
     */
    public function updateProgramEnrollment(Request $request, $studentId, $enrollmentId)
    {
        $enrollment = StudentProgramEnrollment::where('student_id', $studentId)
            ->where('id', $enrollmentId)
            ->firstOrFail();

        $validated = $request->validate([
            'enrollment_status' => 'required|in:active,completed,suspended,withdrawn',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $enrollment->update($validated);

            return back()->with('success', 'Inscription programme mise à jour.');

        } catch (\Exception $e) {
            \Log::error('Error updating program enrollment: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Export student data.
     */
    public function export(Request $request)
    {
        $query = Student::with(['programEnrollments.filiere']);

        // Apply same filters as index
        if ($request->filled('filiere_id')) {
            $query->whereHas('programEnrollments', function ($q) use ($request) {
                $q->where('filiere_id', $request->filiere_id);
            });
        }

        if ($request->filled('academic_year')) {
            $query->whereHas('programEnrollments', function ($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        $students = $query->orderBy('nom')->get();

        $filename = 'students_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'CNE',
                'Apogée',
                'Nom',
                'Prénom',
                'Email',
                'CIN',
                'Téléphone',
                'Filière',
                'Statut',
                'Boursier',
            ]);

            foreach ($students as $student) {
                $currentEnrollment = $student->programEnrollments->first();
                fputcsv($file, [
                    $student->cne,
                    $student->apogee,
                    $student->nom,
                    $student->prenom,
                    $student->email,
                    $student->cin,
                    $student->tel,
                    $currentEnrollment?->filiere?->label_fr ?? 'N/A',
                    $student->is_active ? 'Actif' : 'Inactif',
                    $student->boursier ? 'Oui' : 'Non',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk activate students.
     */
    public function bulkActivate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:students,id',
        ]);

        try {
            Student::whereIn('id', $validated['ids'])->update(['is_active' => true]);

            return back()->with('success', count($validated['ids']) . ' étudiant(s) activé(s).');

        } catch (\Exception $e) {
            \Log::error('Error bulk activating students: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'activation en masse.');
        }
    }

    /**
     * Bulk deactivate students.
     */
    public function bulkDeactivate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:students,id',
        ]);

        try {
            Student::whereIn('id', $validated['ids'])->update(['is_active' => false]);

            return back()->with('success', count($validated['ids']) . ' étudiant(s) désactivé(s).');

        } catch (\Exception $e) {
            \Log::error('Error bulk deactivating students: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la désactivation en masse.');
        }
    }

    /**
     * Download convocation PDF for a specific exam.
     */
    public function downloadConvocation($studentId, $convocationId)
    {
        $student = Student::findOrFail($studentId);

        $convocation = $student->examConvocations()
            ->with(['exam.module', 'local', 'studentModuleEnrollment.programEnrollment.filiere.department', 'studentModuleEnrollment.programEnrollment.academicYear'])
            ->where('exam_convocations.id', $convocationId)
            ->firstOrFail();

        $enrollment = $convocation->studentModuleEnrollment->programEnrollment;

        if (!$enrollment) {
            abort(404, 'Aucune inscription trouvée');
        }

        // Get all exams for this student in the same session/season
        $exam = $convocation->exam;
        $exams = $this->getStudentExamsForSession($student, $enrollment, $exam->session_type, $exam->season);

        if ($exams->isEmpty()) {
            abort(404, 'Aucun examen disponible');
        }

        // Clean/encode exam labels
        $exams = $exams->map(function ($examItem) {
            $examItem['module_label'] = $this->fixEncoding($examItem['module_label'] ?? '');
            if (!empty($examItem['module_label_ar'])) {
                $examItem['module_label_ar'] = $this->fixEncoding($examItem['module_label_ar']);
            }
            return $examItem;
        });

        // Determine main session/season
        $mainSession = $exam->session_type === 'normal' ? 'Normale' : 'Rattrapage';
        $mainSeason = $exam->season === 'autumn' ? 'Automne' : 'Printemps';

        $sessionAr = $mainSession === 'Normale' ? 'العادية' : 'الاستدراكية';
        $seasonAr = $mainSeason === 'Automne' ? 'الخريفية' : 'الربيعية';

        // Generate QR code
        $qrCode = new QrCode($student->apogee);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($result->getString());

        // Get logo
        $logoPath = public_path('storage/logos/logo_fac_fr.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoMime = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $logoMime . ';base64,' . $logoData;
        }

        // Get student photo
        $photoBase64 = '';
        if (!empty($student->avatar)) {
            $photoPath = public_path('storage/' . $student->avatar);
            if (file_exists($photoPath)) {
                try {
                    $photoData = base64_encode(file_get_contents($photoPath));
                    $photoMime = mime_content_type($photoPath);
                    $photoBase64 = 'data:' . $photoMime . ';base64,' . $photoData;
                } catch (\Exception $e) {
                    Log::error('Error encoding avatar: ' . $e->getMessage());
                }
            }
        }

        // Render HTML
        try {
            $html = view('student.exams.convocation-pdf', [
                'student' => $student,
                'enrollment' => $enrollment,
                'exams' => $exams,
                'session' => $mainSession,
                'season' => $mainSeason,
                'session_ar' => $sessionAr,
                'season_ar' => $seasonAr,
                'generatedAt' => now(),
                'logoBase64' => $logoBase64,
                'photoBase64' => $photoBase64,
                'qrCodeBase64' => $qrCodeBase64
            ])->render();
        } catch (\Throwable $e) {
            Log::error('Error rendering convocation view: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Erreur lors de la génération du document.');
        }

        // Setup mPDF
        $tempDir = storage_path('app/mpdf_temp');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }

        $filename = sprintf(
            'Convocation_%s_%s_%s.pdf',
            $student->apogee ?? $student->cne,
            $mainSession,
            now()->format('Ymd')
        );

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'tempDir' => $tempDir,
                'fontDir' => array_merge($fontDirs, [
                    public_path('fonts'),
                ]),
                'fontdata' => $fontData + [
                    'roboto' => [
                        'R' => 'brawler/brawler-regular.ttf',
                        'B' => 'brawler/brawler-bold.ttf',
                    ]
                ],
                'default_font' => 'roboto',
            ]);

            $mpdf->showImageErrors = true;
            $mpdf->SetDisplayMode('fullpage');

            if (ob_get_level()) {
                while (ob_get_level()) {
                    ob_end_clean();
                }
            }

            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output($filename, Destination::STRING);

            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"{$filename}\"",
                'Content-Length' => strlen($pdfContent),
            ];

            return response($pdfContent, 200, $headers);
        } catch (\Mpdf\MpdfException $e) {
            Log::error('mPDF error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Erreur lors de la génération du PDF.');
        } catch (\Throwable $e) {
            Log::error('General error generating PDF: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Erreur interne lors de la génération du PDF.');
        }
    }

    /**
     * Get exams for a specific session and season for a student.
     */
    private function getStudentExamsForSession($student, $enrollment, $sessionType, $season)
    {
        $dbExams = \App\Models\Exam::published()
            ->where('session_type', $sessionType)
            ->where('season', $season)
            ->where('academic_year', $enrollment->academic_year)
            ->whereHas('convocations.studentModuleEnrollment', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->with(['module', 'academicYear', 'convocations' => function($query) use ($student) {
                $query->whereHas('studentModuleEnrollment', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                })->with('local');
            }])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        $exams = [];
        foreach ($dbExams as $exam) {
            $startTime = \Carbon\Carbon::parse($exam->start_time);
            $endTime = \Carbon\Carbon::parse($exam->end_time);
            $durationMinutes = $startTime->diffInMinutes($endTime);
            $hours = floor($durationMinutes / 60);
            $minutes = $durationMinutes % 60;
            $duration = sprintf('%dh%02d', $hours, $minutes);

            $convocation = $exam->convocations->first();
            $roomName = 'À définir';
            if ($convocation && $convocation->local) {
                $roomName = $convocation->local->code;
            }

            $exams[] = [
                'id' => $exam->id,
                'module_id' => $exam->module_id,
                'module_code' => $exam->module->code,
                'module_label' => $exam->module->label,
                'module_label_ar' => $exam->module->label_ar ?? $exam->module->label,
                'semester' => $exam->semester,
                'session' => $exam->session_type === 'normal' ? 'Normale' : 'Rattrapage',
                'session_ar' => $exam->session_type === 'normal' ? 'العادية' : 'الاستدراكية',
                'season' => $exam->season === 'autumn' ? 'Automne' : 'Printemps',
                'season_ar' => $exam->season === 'autumn' ? 'الخريفية' : 'الربيعية',
                'date' => $exam->exam_date,
                'time' => $startTime->format('H:i'),
                'stime' => $startTime->format('H:i'),
                'etime' => $endTime->format('H:i'),
                'duration' => $duration,
                'room' => $roomName,
                'building' => 'Bâtiment A',
                'exam_number' => $convocation->n_examen ?? $exam->id,
            ];
        }

        return collect($exams);
    }

    /**
     * Fix UTF-8 encoding issues.
     */
    private function fixEncoding($string)
    {
        if (empty($string)) {
            return $string;
        }

        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);

        if ($encoding && $encoding !== 'UTF-8') {
            $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        }

        if (mb_detect_encoding($string, 'UTF-8', true) === false) {
            $string = utf8_encode($string);
        }

        return $string;
    }
}
