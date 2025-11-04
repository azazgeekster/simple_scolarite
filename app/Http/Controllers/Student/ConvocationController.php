<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class ConvocationController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Get current enrollment
        $enrollment = $student->programEnrollments()
            ->with(['filiere.department', 'academicYear'])
            ->whereHas('academicYear', fn ($q) => $q->where('is_current', true))
            ->first();

        if (! $enrollment) {
            return view('student.convocations.no-enrollment', compact('student'));
        }

        // Get upcoming exams for current enrollment
        $exams = $this->getUpcomingExams($student, $enrollment);

        return view('student.exams.convocation', compact('student', 'enrollment', 'exams'));
    }

    public function download(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Get current enrollment
        $enrollment = $student->programEnrollments()
            ->with(['filiere.department', 'academicYear'])
            ->whereHas('academicYear', fn ($q) => $q->where('is_current', true))
            ->first();

        if (! $enrollment) {
            abort(404, 'Aucune inscription trouvée');
        }

        // Get all upcoming exams
        $exams = $this->getUpcomingExams($student, $enrollment);

        if ($exams->isEmpty()) {
            abort(404, 'Aucun examen disponible');
        }

        // Filter by session/season if provided
        $session = $request->get('session');
        $season = $request->get('season');

        if ($session)
            $exams = $exams->where('session', $session);
        if ($season)
            $exams = $exams->where('season', $season);

        // Clean/encode exam labels
        $exams = $exams->map(function ($exam) {
            $exam['module_label'] = $this->fixEncoding($exam['module_label'] ?? '');
            if (! empty($exam['module_label_ar'])) {
                $exam['module_label_ar'] = $this->fixEncoding($exam['module_label_ar']);
            }
            return $exam;
        });

        // Determine main session/season
        $mainSession = $exams->groupBy('session')->sortByDesc(fn ($g) => $g->count())->keys()->first() ?? 'Normale';
        $mainSeason = $exams->groupBy('season')->sortByDesc(fn ($g) => $g->count())->keys()->first() ?? 'Automne';

        $sessionAr = $mainSession === 'Normale' ? 'العادية' : 'الاستدراكية';
        $seasonAr = $mainSeason === 'Automne' ? 'الخريفية' : 'الربيعية';


        $logoPath = public_path('storage/logos/logo_fac_fr.png');

        $qrCode = new QrCode($student->cne);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($result->getString());
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoMime = mime_content_type($logoPath);
            $logoBase64 = 'data:'.$logoMime.';base64,'.$logoData;
        }

        $photoPath = public_path('storage/'.$student->avatar);
        if (!empty($student->avatar)) {
            $photoPath = public_path('storage/' . $student->avatar);
            if (file_exists($photoPath)) {
                try {
                    $photoData = base64_encode(file_get_contents($photoPath));
                    $photoMime = mime_content_type($photoPath);
                    $photoBase64 = 'data:' . $photoMime . ';base64,' . $photoData;
                } catch (\Exception $e) {
                    Log::error('Error encoding avatar: ' . $e->getMessage());
                    return redirect()->route('student.exams.convocation')
                        ->with('error', 'Erreur lors du traitement de votre photo de profil.');
                }
            } else {
                return redirect()->route('student.exams.convocation')
                    ->with('error', 'Photo de profil introuvable. Veuillez la mettre à jour.');
            }
        }


        // $pdf = Pdf::loadView('student.exams.convocation-pdf', [
        //     'student' => $student,
        //     'enrollment' => $enrollment,
        //     'exams' => $exams,
        //     'session' => $mainSession,
        //     'season' => $mainSeason,
        //     'session_ar' => $sessionAr,
        //     'season_ar' => $seasonAr,
        //     'generatedAt' => now(),
        //     'logoBase64' => $logoBase64, // Pass base64 logo
        //     'photoBase64' => $photoBase64, // Pass base64 logo
        // ]);
        // return $pdf->stream('document.pdf');

        // Render Blade to HTML (no output to browser)
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
                'logoBase64' => $logoBase64, // Pass base64 logo
                'photoBase64' => $photoBase64, // Pass base64 logo,
                'qrCodeBase64'=>$qrCodeBase64
            ])->render();





        } catch (\Throwable $e) {
            Log::error('Error rendering convocation view: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Erreur lors de la génération du document (render).');
        }

        // Ensure mPDF temp dir exists and is writable
        $tempDir = storage_path('app/mpdf_temp');
        if (! file_exists($tempDir)) {
            @mkdir($tempDir, 0775, true);
        }
        if (! is_writable($tempDir)) {
            Log::warning("mPDF tempDir not writable: {$tempDir}");
            // attempt chmod
            @chmod($tempDir, 0775);
        }

        // Build filename
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

                    'roboto'=>[
                     'R'=>'roboto/roboto.ttf',
                    ]
                ],
                'default_font' => 'roboto',
            ]);


            $mpdf->showImageErrors = true;
            $mpdf->SetDisplayMode('fullpage');

            // Clean any output buffers to avoid corrupting PDF bytes
            if (ob_get_level()) {
                while (ob_get_level()) {
                    ob_end_clean();
                }
            }

            // Write HTML and get PDF as string
            $mpdf->WriteHTML($html);
            $pdfContent = $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
            // Build response with correct headers (including content-length)
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Content-Length' => strlen($pdfContent),
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public',
            ];

            return response($pdfContent, 200, $headers);
        } catch (\Mpdf\MpdfException $e) {
            Log::error('mPDF error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Erreur lors de la génération du PDF (mPDF).');
        } catch (\Throwable $e) {
            Log::error('General error generating PDF: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Erreur interne lors de la génération du PDF.');
        }
    }



    /**
     * Fix UTF-8 encoding issues
     */
    private function fixEncoding($string)
    {
        if (empty($string)) {
            return $string;
        }

        // Detect encoding
        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);

        // Convert to UTF-8 if needed
        if ($encoding && $encoding !== 'UTF-8') {
            $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        }

        // Fix double-encoded UTF-8
        if (mb_detect_encoding($string, 'UTF-8', true) === false) {
            $string = utf8_encode($string);
        }

        return $string;
    }

    /**
     * Remove accents and special characters for PDF compatibility
     */
    private function removeAccents($string)
    {
        $unwanted_array = [
            'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U',
            'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
            // 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', '''=>"'", '''=>"'", '"'=>'"', '"'=>'"'
        ];

        return strtr($string, $unwanted_array);
    }

    /**
     * Get upcoming exams for student
     */
    private function getUpcomingExams($student, $enrollment)
    {
        // Get module IDs for the student's filiere and year
        $moduleIds = \App\Models\Module::where('filiere_id', $enrollment->filiere_id)
            ->where('year_in_program', $enrollment->year_in_program)
            ->pluck('id');

        // Get published upcoming exams for these modules
        $dbExams = \App\Models\Exam::published()
            ->upcoming()
            ->whereIn('module_id', $moduleIds)
            ->with(['module', 'academicYear'])
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        // Transform to array format expected by convocation PDF
        $exams = [];
        foreach ($dbExams as $exam) {
            // Determine season based on semester (S1,S3,S5 = Automne, S2,S4,S6 = Printemps)
            $semesterNum = intval(substr($exam->semester, 1));
            $season = ($semesterNum % 2 == 1) ? 'Automne' : 'Printemps';
            $season_ar = ($semesterNum % 2 == 1) ? 'الخريفية' : 'الربيعية';

            // Calculate duration
            $startTime = \Carbon\Carbon::parse($exam->start_time);
            $endTime = \Carbon\Carbon::parse($exam->end_time);
            $durationMinutes = $startTime->diffInMinutes($endTime);
            $hours = floor($durationMinutes / 60);
            $minutes = $durationMinutes % 60;
            $duration = sprintf('%dh%02d', $hours, $minutes);

            $exams[] = [
                'id' => $exam->id,
                'module_id' => $exam->module_id,
                'module_code' => $exam->module->code,
                'module_label' => $exam->module->label,
                'module_label_ar' => $exam->module->label_ar ?? $exam->module->label,
                'semester' => $exam->semester,
                'session' => $exam->session_type === 'normal' ? 'Normale' : 'Rattrapage',
                'session_ar' => $exam->session_type === 'normal' ? 'العادية' : 'الاستدراكية',
                'season' => $season,
                'season_ar' => $season_ar,
                'date' => $exam->exam_date,
                'time' => $startTime->format('H:i'),
                'duration' => $duration,
                'room' => $exam->local ?? 'À définir',
                'building' => 'Bâtiment A', // You might want to add this to the Exam model
                'exam_number' => $exam->id, // Using exam ID as exam number
            ];
        }

        return collect($exams);
    }
}