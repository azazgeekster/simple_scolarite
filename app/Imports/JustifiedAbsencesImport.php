<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Module;
use App\Models\ModuleGrade;
use App\Models\StudentModuleEnrollment;

class JustifiedAbsencesImport
{
    protected $academicYear;
    protected $session;
    protected $justifiedBy;
    protected $summary = [
        'success' => 0,
        'failed' => 0,
        'already_justified' => 0,
        'details' => []
    ];

    public function __construct($academicYear, $session, $justifiedBy)
    {
        $this->academicYear = $academicYear;
        $this->session = $session;
        $this->justifiedBy = $justifiedBy;
    }

    public function collection($rows)
    {
        foreach ($rows as $row) {
            $this->processRow($row);
        }
    }

    protected function processRow($row)
    {
        $apogee = $row[0] ?? null;
        $codeModule = $row[1] ?? null;
        $reason = $row[2] ?? null;

        if (!$apogee || !$codeModule) {
            $this->summary['failed']++;
            $this->summary['details'][] = "Ligne invalide: apogee ou code_module manquant";
            return;
        }

        // Find student
        $student = Student::where('apogee', $apogee)->first();
        if (!$student) {
            $this->summary['failed']++;
            $this->summary['details'][] = "Étudiant non trouvé: $apogee";
            return;
        }

        // Find module
        $module = Module::where('code', $codeModule)->first();
        if (!$module) {
            $this->summary['failed']++;
            $this->summary['details'][] = "Module non trouvé: $codeModule";
            return;
        }

        // Find grade
        $grade = ModuleGrade::whereHas('moduleEnrollment', function($q) use ($student, $module) {
                $q->where('module_id', $module->id)
                  ->whereHas('programEnrollment', function($q2) use ($student) {
                      $q2->where('student_id', $student->id)
                         ->where('academic_year', $this->academicYear);
                  });
            })
            ->where('session', $this->session)
            ->where('result', 'ABI')
            ->first();

        if (!$grade) {
            $this->summary['failed']++;
            $this->summary['details'][] = "Aucune absence (ABI) trouvée pour $apogee - $codeModule";
            return;
        }

        if ($grade->is_absence_justified) {
            $this->summary['already_justified']++;
            return;
        }

        // Justify absence
        $success = $grade->justifyAbsence($reason, null, $this->justifiedBy);
        
        if ($success) {
            $this->summary['success']++;
        } else {
            $this->summary['failed']++;
            $this->summary['details'][] = "Échec justification: $apogee - $codeModule";
        }
    }

    public function getSummary()
    {
        return $this->summary;
    }
}
