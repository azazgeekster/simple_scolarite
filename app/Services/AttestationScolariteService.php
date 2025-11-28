<?php

namespace App\Services;

use App\Models\Demande;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class AttestationScolariteService
{
    /**
     * Create mPDF instance with proper configuration
     */
    protected function createMpdf(): Mpdf
    {
        $fontConfig = config('mpdf_fonts');

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 20,
            'margin_bottom' => 20,
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
     * Stream the attestation de scolarité PDF for download
     */
    public function stream(Demande $demande)
    {
        $student = $demande->student;
        $currentEnrollment = $student->currentProgramEnrollment();

        $data = [
            'demande' => $demande,
            'student' => $student,
            'currentEnrollment' => $currentEnrollment,
            'date' => now()->format('d/m/Y'),
        ];

        try {
            $mpdf = $this->createMpdf();

            $html = view('admin.document-requests.attestation-scolarite', $data)->render();
            $mpdf->WriteHTML($html);

            $filename = 'Attestation_Scolarite_' . $student->cne . '_' . now()->format('Ymd') . '.pdf';

            return response($mpdf->Output($filename, 'I'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (MpdfException $e) {
            throw new \Exception('Failed to stream attestation PDF: ' . $e->getMessage());
        }
    }
}
