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

class ModuleProfessorManagementController extends Controller
{
    // ==================== MODULES ====================

    /**
     * Display modules list.
     */
    public function modules(Request $request)
    {
        $query = Module::with(['filiere', 'professor']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                    ->orWhere('label', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('filiere')) {
            $query->where('filiere_id', $request->filiere);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $modules = $query->latest()->paginate(20);
        $filieres = Filiere::orderBy('label_fr')->get();
        $professors = Professor::orderBy('nom')->get();

        // Statistics for the view
        $filieresCount = Filiere::count();
        $professorsCount = Professor::count();

        return view('admin.academic.modules', compact('modules', 'filieres', 'professors', 'filieresCount', 'professorsCount'));
    }

    /**
     * Store new module.
     */
    public function storeModule(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:modules,code',
            'label' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'semester' => 'required|string|max:10',
            'professor_id' => 'nullable|exists:professors,id',
            'coefficient' => 'nullable|numeric|min:0|max:10',
            'credits' => 'nullable|integer|min:0|max:20',
            'hours' => 'nullable|integer|min:0',
        ]);

        try {
            Module::create($validated);
            return back()->with('success', 'Module créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error creating module: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création du module.');
        }
    }

    /**
     * Update module.
     */
    public function updateModule(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:modules,code,' . $id,
            'label' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'semester' => 'required|string|max:10',
            'professor_id' => 'nullable|exists:professors,id',
            'coefficient' => 'nullable|numeric|min:0|max:10',
            'credits' => 'nullable|integer|min:0|max:20',
            'hours' => 'nullable|integer|min:0',
        ]);

        try {
            $module->update($validated);
            return back()->with('success', 'Module mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating module: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Delete module.
     */
    public function destroyModule($id)
    {
        try {
            $module = Module::findOrFail($id);
            $module->delete();
            return back()->with('success', 'Module supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting module: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Export modules to Excel.
     */
    public function exportModules()
    {
        try {
            $modules = Module::with(['filiere', 'professor'])->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Modules');

            $headers = ['Code', 'Libellé', 'Filière', 'Semestre', 'Professeur', 'Coefficient', 'Crédits', 'Heures'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1:H1');
            $headerStyle->getFont()->setBold(true)->setSize(11);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
            $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $row = 2;
            foreach ($modules as $module) {
                $sheet->setCellValue('A' . $row, $module->code);
                $sheet->setCellValue('B' . $row, $module->label);
                $sheet->setCellValue('C' . $row, $module->filiere?->label_fr);
                $sheet->setCellValue('D' . $row, $module->semester);
                $sheet->setCellValue('E' . $row, $module->professor ? $module->professor->nom . ' ' . $module->professor->prenom : '');
                $sheet->setCellValue('F' . $row, $module->coefficient);
                $sheet->setCellValue('G' . $row, $module->credits);
                $sheet->setCellValue('H' . $row, $module->hours);

                $sheet->getStyle('A' . $row . ':H' . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->freezePane('A2');

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'modules');
            $writer->save($temp);

            return response()->download($temp, 'modules_' . date('Y-m-d_His') . '.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting modules: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'exportation.');
        }
    }

    /**
     * Download modules import template.
     */
    public function downloadModulesTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['Code *', 'Libellé *', 'Code Filière *', 'Semestre *', 'CIN Professeur', 'Coefficient', 'Crédits', 'Heures'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1:H1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

            $sheet->setCellValue('A2', 'M101');
            $sheet->setCellValue('B2', 'Programmation C');
            $sheet->setCellValue('C2', 'INFO');
            $sheet->setCellValue('D2', 'S1');
            $sheet->setCellValue('E2', 'AB123456');
            $sheet->setCellValue('F2', '3');
            $sheet->setCellValue('G2', '6');
            $sheet->setCellValue('H2', '60');

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'modules_template');
            $writer->save($temp);

            return response()->download($temp, 'template_modules.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle.');
        }
    }

    /**
     * Import modules from Excel.
     */
    public function importModules(Request $request)
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

                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                    continue;
                }

                $code = trim($row[0]);
                $label = trim($row[1]);
                $filiereCode = trim($row[2]);
                $semester = trim($row[3]);
                $professorCin = $row[4] ?? null;
                $coefficient = $row[5] ?? null;
                $credits = $row[6] ?? null;
                $hours = $row[7] ?? null;

                if (Module::where('code', $code)->exists()) {
                    $errors[] = "Ligne {$rowNum}: Code '{$code}' existe déjà";
                    continue;
                }

                $filiere = Filiere::where('code', $filiereCode)->first();
                if (!$filiere) {
                    $errors[] = "Ligne {$rowNum}: Filière '{$filiereCode}' introuvable";
                    continue;
                }

                $professorId = null;
                if ($professorCin) {
                    $professor = Professor::where('cin', $professorCin)->first();
                    if (!$professor) {
                        $errors[] = "Ligne {$rowNum}: Professeur '{$professorCin}' introuvable";
                        continue;
                    }
                    $professorId = $professor->id;
                }

                Module::create([
                    'code' => $code,
                    'label' => $label,
                    'filiere_id' => $filiere->id,
                    'semester' => $semester,
                    'professor_id' => $professorId,
                    'coefficient' => $coefficient,
                    'credits' => $credits,
                    'hours' => $hours,
                ]);

                $imported++;
            }

            DB::commit();

            $message = "{$imported} module(s) importé(s) avec succès.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " erreur(s).";
            }

            return back()->with('success', $message)->with('import_errors', $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing modules: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    // ==================== PROFESSORS ====================

    /**
     * Display professors list.
     */
    public function professors(Request $request)
    {
        $query = Professor::with('department');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                    ->orWhere('prenom', 'like', '%' . $request->search . '%')
                    ->orWhere('cin', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('departement')) {
            $query->where('department_id', $request->departement);
        }

        $professors = $query->withCount('modules')->latest()->paginate(20);
        $departements = Departement::orderBy('label')->get();

        // Statistics for the view
        $departementsCount = Departement::count();
        $assignedModulesCount = Module::whereNotNull('professor_id')->count();
        $unassignedProfessorsCount = Professor::doesntHave('modules')->count();

        return view('admin.academic.professors', compact('professors', 'departements', 'departementsCount', 'assignedModulesCount', 'unassignedProfessorsCount'));
    }

    /**
     * Store new professor.
     */
    public function storeProfessor(Request $request)
    {
        $validated = $request->validate([
            'cin' => 'required|string|max:20|unique:professors,cin',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:professors,email',
            'phone' => 'nullable|string|max:20',
            'departement_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:100',
        ]);

        try {
            Professor::create([
                'cin' => $validated['cin'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'department_id' => $validated['departement_id'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'grade' => $validated['grade'] ?? null,
            ]);
            return back()->with('success', 'Professeur créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error creating professor: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création du professeur.');
        }
    }

    /**
     * Update professor.
     */
    public function updateProfessor(Request $request, $id)
    {
        $professor = Professor::findOrFail($id);

        $validated = $request->validate([
            'cin' => 'required|string|max:20|unique:professors,cin,' . $id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:professors,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'departement_id' => 'nullable|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'grade' => 'nullable|string|max:100',
        ]);

        try {
            $professor->update([
                'cin' => $validated['cin'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'department_id' => $validated['departement_id'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'grade' => $validated['grade'] ?? null,
            ]);
            return back()->with('success', 'Professeur mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Error updating professor: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Delete professor.
     */
    public function destroyProfessor($id)
    {
        try {
            $professor = Professor::findOrFail($id);

            if ($professor->modules()->count() > 0) {
                return back()->with('error', 'Impossible de supprimer un professeur avec des modules associés.');
            }

            $professor->delete();
            return back()->with('success', 'Professeur supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting professor: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Export professors to Excel.
     */
    public function exportProfessors()
    {
        try {
            $professors = Professor::with('departement')->withCount('modules')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Professeurs');

            $headers = ['CIN', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Département', 'Spécialisation', 'Grade', 'Nb Modules'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1:I1');
            $headerStyle->getFont()->setBold(true)->setSize(11);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4F81BD');
            $headerStyle->getFont()->getColor()->setARGB('FFFFFFFF');
            $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $row = 2;
            foreach ($professors as $prof) {
                $sheet->setCellValue('A' . $row, $prof->cin);
                $sheet->setCellValue('B' . $row, $prof->nom);
                $sheet->setCellValue('C' . $row, $prof->prenom);
                $sheet->setCellValue('D' . $row, $prof->email);
                $sheet->setCellValue('E' . $row, $prof->phone);
                $sheet->setCellValue('F' . $row, $prof->departement?->name);
                $sheet->setCellValue('G' . $row, $prof->specialization);
                $sheet->setCellValue('H' . $row, $prof->grade);
                $sheet->setCellValue('I' . $row, $prof->modules_count);

                $sheet->getStyle('A' . $row . ':I' . $row)
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $row++;
            }

            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->freezePane('A2');

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'professors');
            $writer->save($temp);

            return response()->download($temp, 'professeurs_' . date('Y-m-d_His') . '.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting professors: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'exportation.');
        }
    }

    /**
     * Download professors import template.
     */
    public function downloadProfessorsTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = ['CIN *', 'Nom *', 'Prénom *', 'Email *', 'Téléphone', 'Code Département', 'Spécialisation', 'Grade'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }

            $headerStyle = $sheet->getStyle('A1:H1');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');

            $sheet->setCellValue('A2', 'AB123456');
            $sheet->setCellValue('B2', 'Alami');
            $sheet->setCellValue('C2', 'Ahmed');
            $sheet->setCellValue('D2', 'a.alami@university.ma');
            $sheet->setCellValue('E2', '0612345678');
            $sheet->setCellValue('F2', 'INFO');
            $sheet->setCellValue('G2', 'Intelligence Artificielle');
            $sheet->setCellValue('H2', 'Professeur');

            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $temp = tempnam(sys_get_temp_dir(), 'professors_template');
            $writer->save($temp);

            return response()->download($temp, 'template_professeurs.xlsx')->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors du téléchargement du modèle.');
        }
    }

    /**
     * Import professors from Excel.
     */
    public function importProfessors(Request $request)
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

                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                    continue;
                }

                $cin = trim($row[0]);
                $nom = trim($row[1]);
                $prenom = trim($row[2]);
                $email = trim($row[3]);
                $phone = $row[4] ?? null;
                $deptCode = $row[5] ?? null;
                $specialization = $row[6] ?? null;
                $grade = $row[7] ?? null;

                if (Professor::where('cin', $cin)->exists()) {
                    $errors[] = "Ligne {$rowNum}: CIN '{$cin}' existe déjà";
                    continue;
                }

                if (Professor::where('email', $email)->exists()) {
                    $errors[] = "Ligne {$rowNum}: Email '{$email}' existe déjà";
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

                Professor::create([
                    'cin' => $cin,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'phone' => $phone,
                    'departement_id' => $departementId,
                    'specialization' => $specialization,
                    'grade' => $grade,
                ]);

                $imported++;
            }

            DB::commit();

            $message = "{$imported} professeur(s) importé(s) avec succès.";
            if (count($errors) > 0) {
                $message .= " " . count($errors) . " erreur(s).";
            }

            return back()->with('success', $message)->with('import_errors', $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing professors: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }
}
