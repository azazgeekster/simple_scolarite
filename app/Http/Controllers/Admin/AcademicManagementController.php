<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AcademicManagementController extends Controller
{
    /**
     * Display academic management dashboard.
     */
    public function index()
    {
        $stats = [
            'departments' => Departement::count(),
            'filieres' => Filiere::count(),
            'modules' => Module::count(),
            'professors' => Professor::count(),
        ];

        return view('admin.academic.index', compact('stats'));
    }

    // ==================== DEPARTMENTS ====================

    /**
     * Display departments list.
     */
    public function departments(Request $request)
    {
        $query = Departement::query();

        if ($request->filled('search')) {
            $query->where('label', 'like', '%' . $request->search . '%');
        }

        $departments = $query->withCount(['professors', 'filieres'])->latest()->paginate(20);

        // Statistics for the view
        $filieresCount = Filiere::count();
        $professorsCount = Professor::count();
        $modulesCount = Module::count();

        return view('admin.academic.departments', compact('departments', 'filieresCount', 'professorsCount', 'modulesCount'));
    }

    /**
     * Store new department.
     */
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255|unique:departments,label',
        ]);

        try {
            Departement::create($validated);
            return back()->with('success', 'Département créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error creating department: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création du département.');
        }
    }

    /**
     * Update department.
     */
    public function updateDepartment(Request $request, $id)
    {
        $department = Departement::findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:255|unique:departments,label,' . $id,
        ]);

        try {
            $department->update($validated);
            return back()->with('success', 'Département mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating department: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Delete department.
     */
    public function destroyDepartment($id)
    {
        try {
            $department = Departement::findOrFail($id);

            if ($department->professors()->count() > 0) {
                return back()->with('error', 'Impossible de supprimer un département avec des professeurs associés.');
            }

            $department->delete();
            return back()->with('success', 'Département supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Export departments to Excel.
     */
    public function exportDepartments()
    {
        try {
            $departments = Departement::withCount('professors')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Départements');

            // Headers
            $headers = ['Libellé', 'Nombre de Professeurs'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            // Style header
            $headerStyle = $sheet->getStyle('A1:B1');
            $headerStyle->getFont()->setBold(true)->setSize(11);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
            $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Data rows
            $row = 2;
            foreach ($departments as $dept) {
                $sheet->setCellValue('A' . $row, $dept->label);
                $sheet->setCellValue('B' . $row, $dept->professors_count);

                $sheet->getStyle('A' . $row . ':B' . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'B') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->freezePane('A2');

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'departments');
            $writer->save($temp);

            return response()->download($temp, 'departements_' . date('Y-m-d_His') . '.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting departments: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'exportation.');
        }
    }

    /**
     * Download departments import template.
     */
    public function downloadDepartmentsTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['Libellé *'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

            // Example row
            $sheet->setCellValue('A2', 'Département Informatique');

            $sheet->getColumnDimension('A')->setAutoSize(true);

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'departments_template');
            $writer->save($temp);

            return response()->download($temp, 'template_departements.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle.');
        }
    }

    /**
     * Import departments from Excel.
     */
    public function importDepartments(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            array_shift($rows); // Skip header

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                if (empty($row[0])) {
                    continue;
                }

                $label = trim($row[0]);

                // Check for duplicates
                if (Departement::where('label', $label)->exists()) {
                    $errors[] = "Ligne {$rowNum}: Libellé '{$label}' existe déjà";
                    continue;
                }

                Departement::create([
                    'label' => $label,
                ]);

                $imported++;
            }

            DB::commit();

            $message = "{$imported} département(s) importé(s) avec succès.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " erreur(s).";
            }

            return back()->with('success', $message)->with('import_errors', $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing departments: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    // ==================== FILIERES ====================

    /**
     * Display filieres list.
     */
    public function filieres(Request $request)
    {
        $query = Filiere::with('department');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('label_fr', 'like', '%' . $request->search . '%')
                    ->orWhere('label_ar', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('departement_id')) {
            $query->where('department_id', $request->departement_id);
        }

        $filieres = $query->withCount('modules')->latest()->paginate(20)->withQueryString();
        $departements = Departement::orderBy('label')->get();

        // Statistics for the view
        $departementsCount = Departement::count();
        $modulesCount = Module::count();

        return view('admin.academic.filieres', compact('filieres', 'departements', 'departementsCount', 'modulesCount'));
    }

    /**
     * Store new filiere.
     */
    public function storeFiliere(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:filieres,code',
            'label_fr' => 'required|string|max:255',
            'label_ar' => 'nullable|string|max:255',
            'departement_id' => 'nullable|exists:departments,id',
            'duration_years' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            Filiere::create([
                'code' => $validated['code'],
                'label_fr' => $validated['label_fr'],
                'label_ar' => $validated['label_ar'],
                'department_id' => $validated['departement_id'] ?? null,
                'duration_years' => $validated['duration_years'] ?? null,
            ]);
            return back()->with('success', 'Filière créée avec succès.');
        } catch (\Exception $e) {
            Log::error('Error creating filiere: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création de la filière.');
        }
    }

    /**
     * Update filiere.
     */
    public function updateFiliere(Request $request, $id)
    {
        $filiere = Filiere::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:filieres,code,' . $id,
            'label_fr' => 'required|string|max:255',
            'label_ar' => 'nullable|string|max:255',
            'departement_id' => 'nullable|exists:departments,id',
            'duration_years' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            $filiere->update([
                'code' => $validated['code'],
                'label_fr' => $validated['label_fr'],
                'label_ar' => $validated['label_ar'],
                'department_id' => $validated['departement_id'] ?? null,
                'duration_years' => $validated['duration_years'] ?? null,
            ]);
            return back()->with('success', 'Filière mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating filiere: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Delete filiere.
     */
    public function destroyFiliere($id)
    {
        try {
            $filiere = Filiere::findOrFail($id);

            if ($filiere->modules()->count() > 0) {
                return back()->with('error', 'Impossible de supprimer une filière avec des modules associés.');
            }

            $filiere->delete();
            return back()->with('success', 'Filière supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting filiere: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Export filieres to Excel.
     */
    public function exportFilieres()
    {
        try {
            $filieres = Filiere::with('department')->withCount('modules')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Filières');

            $headers = ['Code', 'Libellé FR', 'Libellé AR', 'Département', 'Durée (années)', 'Nombre de Modules'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1:F1');
            $headerStyle->getFont()->setBold(true)->setSize(11);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
            $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $row = 2;
            foreach ($filieres as $filiere) {
                $sheet->setCellValue('A' . $row, $filiere->code);
                $sheet->setCellValue('B' . $row, $filiere->label_fr);
                $sheet->setCellValue('C' . $row, $filiere->label_ar);
                $sheet->setCellValue('D' . $row, $filiere->department?->label);
                $sheet->setCellValue('E' . $row, $filiere->duration_years);
                $sheet->setCellValue('F' . $row, $filiere->modules_count);

                $sheet->getStyle('A' . $row . ':F' . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }

            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->freezePane('A2');

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'filieres');
            $writer->save($temp);

            return response()->download($temp, 'filieres_' . date('Y-m-d_His') . '.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting filieres: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'exportation.');
        }
    }

    /**
     * Download filieres import template.
     */
    public function downloadFilieresTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['Code *', 'Libellé FR *', 'Libellé AR', 'Code Département', 'Durée (années)'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1:E1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

            $sheet->setCellValue('A2', 'INFO');
            $sheet->setCellValue('B2', 'Informatique');
            $sheet->setCellValue('C2', 'المعلوميات');
            $sheet->setCellValue('D2', 'INFO');
            $sheet->setCellValue('E2', '3');

            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'filieres_template');
            $writer->save($temp);

            return response()->download($temp, 'template_filieres.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle.');
        }
    }

    /**
     * Import filieres from Excel.
     */
    public function importFilieres(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            array_shift($rows);

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                if (empty($row[0]) || empty($row[1])) {
                    continue;
                }

                $code = trim($row[0]);
                $labelFr = trim($row[1]);
                $labelAr = $row[2] ?? null;
                $deptCode = $row[3] ?? null;
                $duration = $row[4] ?? null;

                if (Filiere::where('code', $code)->exists()) {
                    $errors[] = "Ligne {$rowNum}: Code '{$code}' existe déjà";
                    continue;
                }

                $departementId = null;
                if ($deptCode) {
                    $dept = Departement::where('code', $deptCode)->first();
                    if (!$dept) {
                        $errors[] = "Ligne {$rowNum}: Département '{$deptCode}' introuvable";
                        continue;
                    }
                    $departementId = $dept->id;
                }

                Filiere::create([
                    'code' => $code,
                    'label_fr' => $labelFr,
                    'label_ar' => $labelAr,
                    'departement_id' => $departementId,
                    'duration_years' => $duration,
                ]);

                $imported++;
            }

            DB::commit();

            $message = "{$imported} filière(s) importée(s) avec succès.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " erreur(s).";
            }

            return back()->with('success', $message)->with('import_errors', $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing filieres: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }
}
