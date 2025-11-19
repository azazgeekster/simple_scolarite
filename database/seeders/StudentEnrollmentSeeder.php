<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\StudentProgramEnrollment;
use App\Models\StudentModuleEnrollment;
use App\Models\AcademicYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get current academic year
        $currentYear = AcademicYear::where('is_current', true)->first();
        if (!$currentYear) {
            $this->command->error('No current academic year found!');
            return;
        }

        // Get Info and Math filières
        $infoFiliere = Filiere::where('code', 'INFO-L')->first();
        $mathFiliere = Filiere::where('code', 'MATH-L')->first();

        if (!$infoFiliere || !$mathFiliere) {
            $this->command->error('Info or Math filière not found!');
            return;
        }

        // Get modules for each filière
        $infoModules = Module::where('filiere_id', $infoFiliere->id)->get();
        $mathModules = Module::where('filiere_id', $mathFiliere->id)->get();

        $this->command->info("Creating students and enrollments...");

        // Create 15 students for Info
        $this->createStudentsForFiliere($infoFiliere, $infoModules, 15, $currentYear, 'INFO');

        // Create 10 students for Math
        $this->createStudentsForFiliere($mathFiliere, $mathModules, 10, $currentYear, 'MATH');

        $this->command->info("Successfully created and enrolled students!");
    }

    private function createStudentsForFiliere(Filiere $filiere, $modules, int $count, AcademicYear $academicYear, string $prefix): void
    {
        $firstNames = ['Ahmed', 'Mohamed', 'Youssef', 'Ali', 'Omar', 'Hamza', 'Anas', 'Mehdi', 'Karim', 'Rachid',
                      'Fatima', 'Khadija', 'Aicha', 'Salma', 'Nour', 'Yasmine', 'Imane', 'Safaa', 'Hanane', 'Zineb'];
        $lastNames = ['Alami', 'Bennani', 'Chraibi', 'Filali', 'Hamdaoui', 'Idrissi', 'Jamal', 'Kadiri', 'Lamrani', 'Mansouri',
                     'Nassiri', 'Ouazzani', 'Rachidi', 'Saidi', 'Tazi', 'Benjelloun', 'Berrada', 'Elalami', 'Fassi', 'Kettani'];

        $cities = ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Tanger', 'Agadir', 'Meknès', 'Oujda', 'Kenitra', 'Tétouan'];

        for ($i = 1; $i <= $count; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $gender = rand(0, 1) ? 'M' : 'F';

            // Generate unique identifiers
            $cneBase = sprintf('%s%04d', $prefix, 1000 + $i);
            $apogeeBase = sprintf('%d%04d', 20240000 + rand(1, 999), $i);
            $cinBase = sprintf('%s%05d', substr($prefix, 0, 2), 10000 + $i);

            // Check if student with this CNE already exists
            $existingStudent = Student::where('cne', $cneBase)->first();
            if ($existingStudent) {
                $this->command->warn("Student with CNE {$cneBase} already exists, skipping...");
                continue;
            }

            $student = Student::create([
                'cne' => $cneBase,
                'prenom' => $firstName,
                'nom' => $lastName,
                'prenom_ar' => $firstName,
                'nom_ar' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . $i . '@student.edu'),
                'password' => Hash::make('password'),
                'apogee' => $apogeeBase,
                'cin' => $cinBase,
                'sexe' => $gender,
                'tel' => sprintf('06%08d', rand(10000000, 99999999)),
                'tel_urgence' => sprintf('06%08d', rand(10000000, 99999999)),
                'date_naissance' => now()->subYears(rand(18, 25))->format('Y-m-d'),
                'lieu_naissance' => $cities[array_rand($cities)],
                'nationalite' => 'MA',
                'situation_familiale' => 'Célibataire',
                'situation_professionnelle' => 'Étudiant',
                'adresse' => rand(1, 999) . ' Rue ' . $lastNames[array_rand($lastNames)] . ', ' . $cities[array_rand($cities)],
                'pays' => 'Maroc',
                'boursier' => rand(0, 1) ? true : false,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Randomly assign year (1 or 2 for available modules)
            $yearInProgram = rand(1, 1); // Since we only have year 1 modules

            // Create program enrollment
            $programEnrollment = StudentProgramEnrollment::create([
                'student_id' => $student->id,
                'filiere_id' => $filiere->id,
                'academic_year' => $academicYear->start_year,
                'registration_year' => $academicYear->start_year,
                'year_in_program' => $yearInProgram,
                'diploma_level' => 'licence',
                'diploma_year' => $yearInProgram,
                'enrollment_status' => 'active',
                'enrollment_date' => $academicYear->start_date,
            ]);

            // Enroll student in modules for their year
            $semestersForYear = $this->getSemestersForYear($yearInProgram);

            foreach ($modules as $module) {
                if (in_array($module->semester, $semestersForYear)) {
                    StudentModuleEnrollment::create([
                        'student_id' => $student->id,
                        'program_enrollment_id' => $programEnrollment->id,
                        'module_id' => $module->id,
                        'semester' => $module->semester,
                        'registration_year' => $academicYear->start_year,
                        'attempt_number' => 1,
                    ]);
                }
            }

            $this->command->info("Created student: {$student->full_name} (CNE: {$student->cne}) - {$filiere->label_fr} - Year {$yearInProgram}");
        }
    }

    private function getSemestersForYear(int $year): array
    {
        return match($year) {
            1 => ['S1', 'S2'],
            2 => ['S3', 'S4'],
            3 => ['S5', 'S6'],
            default => [],
        };
    }
}
