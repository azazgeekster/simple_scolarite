<?php

namespace App\Services;

use App\Models\Demande;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $pdf = Pdf::loadView('admin.document-requests.decharge', $data);
        $pdf->setPaper('a4');

        // Generate filename
        $filename = 'decharge_' . $demande->reference_number . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'decharges/' . $filename;

        // Store PDF
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
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

        $pdf = Pdf::loadView('admin.document-requests.decharge', $data);
        $pdf->setPaper('a4');

        $filename = 'Decharge_' . $demande->reference_number . '.pdf';

        return $pdf->stream($filename);
    }
}
