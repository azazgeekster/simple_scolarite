<?php

namespace App\Exports;

class JustificationTemplateExport
{
    public function headings(): array
    {
        return [
            'apogee',
            'code_module',
            'justification_reason'
        ];
    }

    public function array(): array
    {
        return [
            ['12345678', 'M101', 'Certificat médical'],
            ['87654321', 'M102', 'Urgence familiale'],
        ];
    }
}
