<?php

namespace App\Services;

use App\Models\Demande;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class DechargeService
{
    /**
     * Generate QR code for the demande
     */
    protected function generateQrCode(Demande $demande): string
    {
        $qrContent = implode("\n", [
            "Ref: {$demande->reference_number}",
            "CNE: {$demande->student->cne}",
            "Date: " . ($demande->decharge_generated_at ? $demande->decharge_generated_at->format('d/m/Y') : now()->format('d/m/Y')),
        ]);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($qrContent)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->size(150)
            ->margin(5)
            ->build();

        return base64_encode($result->getString());
    }

    /**
     * Create mPDF instance with proper configuration
     */
    protected function createMpdf(): Mpdf
    {
        $fontConfig = config('mpdf_fonts');

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'default_font' => 'brawler',
            'fontDir' => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                $fontConfig['fontDir']
            ),
            'fontdata' => array_merge(
                (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'],
                $fontConfig['fontdata']
            ),
        ]);
    }

    /**
     * Generate a décharge (liability release) PDF for a document withdrawal
     */
    public function generate(Demande $demande): string
    {
        $data = [
            'demande' => $demande,
            'student' => $demande->student,
            'document' => $demande->document,
            'admin' => auth('admin')->user(),
            'date' => now()->format('d/m/Y'),
            'time' => now()->format('H:i'),
            'qrCode' => $this->generateQrCode($demande),
        ];

        try {
            $mpdf = $this->createMpdf();

            $html = view('admin.document-requests.decharge', $data)->render();
            $mpdf->WriteHTML($html);

            // Generate filename
            $filename = 'decharge_' . $demande->reference_number . '_' . now()->format('Ymd_His') . '.pdf';
            $path = 'decharges/' . $filename;

            // Store PDF
            Storage::disk('public')->put($path, $mpdf->Output('', 'S'));

            return $path;
        } catch (MpdfException $e) {
            throw new \Exception('Failed to generate decharge PDF: ' . $e->getMessage());
        }
    }

    /**
     * Stream the décharge PDF for download
     */
    public function stream(Demande $demande)
    {
        $data = [
            'demande' => $demande,
            'student' => $demande->student,
            'document' => $demande->document,
            'admin' => $demande->dechargeSignedBy ?? auth('admin')->user(),
            'date' => $demande->decharge_generated_at ? $demande->decharge_generated_at->format('d/m/Y') : now()->format('d/m/Y'),
            'time' => $demande->decharge_generated_at ? $demande->decharge_generated_at->format('H:i') : now()->format('H:i'),
            'qrCode' => $this->generateQrCode($demande),
        ];

        try {
            $mpdf = $this->createMpdf();

            $html = view('admin.document-requests.decharge', $data)->render();
            $mpdf->WriteHTML($html);

            $filename = 'Decharge_' . $demande->reference_number . '.pdf';

            return response($mpdf->Output($filename, 'I'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (MpdfException $e) {
            throw new \Exception('Failed to stream decharge PDF: ' . $e->getMessage());
        }
    }
}
