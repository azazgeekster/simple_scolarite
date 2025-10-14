<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Student;
use App\Models\BacInfo;
use App\Models\Family;
use App\Models\Departement;
use App\Models\Professor;
use App\Models\Filiere;
use App\Models\AcademicYear;
use App\Models\Module;
use App\Models\StudentProgramEnrollment;
use App\Models\StudentSemesterEnrollment;
use App\Models\StudentModuleEnrollment;
use App\Models\ModuleGrade;
use App\Models\Document;
use App\Models\Demande;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admins
        $this->createAdmins();

        // 2. Create Departments and Professors
        $departments = $this->createDepartmentsAndProfessors();

        // 3. Create Filieres
        $filieres = $this->createFilieres($departments);

        // 4. Create Academic Years
        $academicYears = $this->createAcademicYears();

        // 5. Create Modules
        $modules = $this->createModules($filieres);

        // 6. Create Students
        $students = $this->createStudents();

        // 7. Create Program Enrollments
        $programEnrollments = $this->createProgramEnrollments($students, $filieres, $academicYears);

        // 8. Create Semester Enrollments
        $semesterEnrollments = $this->createSemesterEnrollments($programEnrollments);

        // 9. Create Module Enrollments
        $moduleEnrollments = $this->createModuleEnrollments($semesterEnrollments, $modules);

        // 10. Create Module Grades
        $this->createModuleGrades($moduleEnrollments);

        // 11. Create Documents
        $documents = $this->createDocuments();

        // 12. Create Demandes
        $this->createDemandes($students, $documents, $academicYears);

        $this->command->info('Database seeded successfully!');
    }

    private function createAdmins(): void
    {
        $this->command->info('Creating admins...');

        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@university.ma',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'name' => 'Mohamed Alaoui',
            'email' => 'malaoui@university.ma',
            'password' => Hash::make('password'),
        ]);
    }

    private function createDepartmentsAndProfessors(): array
    {
        $this->command->info('Creating departments and professors...');

        // Create Departments
        $deptInfo = Departement::create(['label' => 'Informatique']);
        $deptMath = Departement::create(['label' => 'Mathématiques']);
        $deptPhysics = Departement::create(['label' => 'Physique']);

        // Create Professors for Informatique
        $prof1 = Professor::create([
            'prenom' => 'Ahmed',
            'nom' => 'Benali',
            'email' => 'a.benali@university.ma',
            'phone' => '0612345678',
            'specialization' => 'Intelligence Artificielle',
            'department_id' => $deptInfo->id,
        ]);

        $prof2 = Professor::create([
            'prenom' => 'Fatima',
            'nom' => 'Zahra',
            'email' => 'f.zahra@university.ma',
            'phone' => '0612345679',
            'specialization' => 'Réseaux Informatiques',
            'department_id' => $deptInfo->id,
        ]);

        // Create Professors for Math
        $prof3 = Professor::create([
            'prenom' => 'Hassan',
            'nom' => 'Idrissi',
            'email' => 'h.idrissi@university.ma',
            'phone' => '0612345680',
            'specialization' => 'Analyse Mathématique',
            'department_id' => $deptMath->id,
        ]);

        // Create Professors for Physics
        $prof4 = Professor::create([
            'prenom' => 'Amina',
            'nom' => 'Tazi',
            'email' => 'a.tazi@university.ma',
            'phone' => '0612345681',
            'specialization' => 'Physique Quantique',
            'department_id' => $deptPhysics->id,
        ]);

        // Assign department heads
        $deptInfo->assignHead($prof1);
        $deptMath->assignHead($prof3);
        $deptPhysics->assignHead($prof4);

        return [
            'info' => ['dept' => $deptInfo, 'professors' => [$prof1, $prof2]],
            'math' => ['dept' => $deptMath, 'professors' => [$prof3]],
            'physics' => ['dept' => $deptPhysics, 'professors' => [$prof4]],
        ];
    }

    private function createFilieres(array $departments): array
    {
        $this->command->info('Creating filieres...');

        $filiereInfo = Filiere::create([
            'code' => 'INFO-L',
            'label_fr' => 'Licence en Informatique',
            'label_ar' => 'الإجازة في المعلوميات',
            'label_en' => 'Bachelor in Computer Science',
            'level' => 'licence',
            'department_id' => $departments['info']['dept']->id,
            'professor_id' => $departments['info']['professors'][0]->id, // Ahmed Benali
        ]);

        $filiereMath = Filiere::create([
            'code' => 'MATH-L',
            'label_fr' => 'Licence en Mathématiques',
            'label_ar' => 'الإجازة في الرياضيات',
            'label_en' => 'Bachelor in Mathematics',
            'level' => 'licence',
            'department_id' => $departments['math']['dept']->id,
            'professor_id' => $departments['math']['professors'][0]->id, // Hassan Idrissi
        ]);

        $filierePhysics = Filiere::create([
            'code' => 'PHYS-L',
            'label_fr' => 'Licence en Physique',
            'label_ar' => 'الإجازة في الفيزياء',
            'label_en' => 'Bachelor in Physics',
            'level' => 'licence',
            'department_id' => $departments['physics']['dept']->id,
            'professor_id' => $departments['physics']['professors'][0]->id, // Amina Tazi
        ]);

        return [$filiereInfo, $filiereMath, $filierePhysics];
    }

    private function createAcademicYears(): array
    {
        $this->command->info('Creating academic years...');

        $year2023 = AcademicYear::create([
            'start_year' => 2023,
            'end_year' => 2024,
            'is_current' => false,
        ]);

        $year2024 = AcademicYear::create([
            'start_year' => 2024,
            'end_year' => 2025,
            'is_current' => true,
        ]);

        return [$year2023, $year2024];
    }

    private function createModules(array $filieres): array
    {
        $this->command->info('Creating modules...');

        $modules = [];

        // Modules for Informatique - L1
        $modules[] = Module::create([
            'code' => 'INFO101',
            'label' => 'Introduction à la Programmation',
            'label_ar' => 'مقدمة في البرمجة',
            'filiere_id' => $filieres[0]->id,
            'year_in_program' => 1,
            'semester' => 'S1',
            'cc_percentage' => 40,
            'exam_percentage' => 60,
            'professor_id' => Professor::first()->id,
        ]);

        $modules[] = Module::create([
            'code' => 'INFO102',
            'label' => 'Algorithmes et Structures de Données',
            'label_ar' => 'الخوارزميات وبنيات المعطيات',
            'filiere_id' => $filieres[0]->id,
            'year_in_program' => 1,
            'semester' => 'S1',
            'cc_percentage' => 40,
            'exam_percentage' => 60,
            'professor_id' => Professor::skip(1)->first()->id,
        ]);

        $modules[] = Module::create([
            'code' => 'INFO201',
            'label' => 'Base de Données',
            'label_ar' => 'قواعد البيانات',
            'filiere_id' => $filieres[0]->id,
            'year_in_program' => 1,
            'semester' => 'S2',
            'cc_percentage' => 40,
            'exam_percentage' => 60,
            'professor_id' => Professor::first()->id,
            'prerequisite_id' => $modules[0]->id,
        ]);

        // Modules for Math - L1
        $modules[] = Module::create([
            'code' => 'MATH101',
            'label' => 'Analyse 1',
            'label_ar' => 'التحليل 1',
            'filiere_id' => $filieres[1]->id,
            'year_in_program' => 1,
            'semester' => 'S1',
            'cc_percentage' => 30,
            'exam_percentage' => 70,
            'professor_id' => Professor::skip(2)->first()->id,
        ]);

        $modules[] = Module::create([
            'code' => 'MATH102',
            'label' => 'Algèbre Linéaire',
            'label_ar' => 'الجبر الخطي',
            'filiere_id' => $filieres[1]->id,
            'year_in_program' => 1,
            'semester' => 'S1',
            'cc_percentage' => 30,
            'exam_percentage' => 70,
            'professor_id' => Professor::skip(2)->first()->id,
        ]);

        return $modules;
    }

    private function createStudents(): array
    {
        $this->command->info('Creating students...');

        $students = [];

        // Student 1
        $student1 = Student::create([
            'cne' => 'R123456789',
            'prenom' => 'Youssef',
            'nom' => 'Mansouri',
            'prenom_ar' => 'يوسف',
            'nom_ar' => 'المنصوري',
            'email' => 'y.mansouri@student.ma',
            'password' => Hash::make('password'),
            'apogee' => '20230001',
            'cin' => 'AB123456',
            'tel' => '0661234567',
            'date_naissance' => '2005-03-15',
            'lieu_naissance' => 'Casablanca',
            'nationalite' => 'MA',
            'sexe' => 'M',
            'situation_familiale' => 'Célibataire',
            'adresse' => '123 Rue Mohammed V, Casablanca',
            'pays' => 'Maroc',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        BacInfo::create([
            'student_id' => $student1->id,
            'type_bac' => 'Sciences Mathématiques',
            'annee_bac' => 2023,
            'code_serie_bac' => 'SM',
            'serie_du_bac' => 'Sciences Mathématiques A',
            'moyenne_generale' => 16.50,
            'moyenne_des_maths' => 18.00,
            'moyenne_sciences_physiques' => 17.50,
            'moyenne_national' => 15.75,
            'deuxieme_langue' => 'Anglais',
            'academie' => 'Casablanca-Settat',
            'province_bac' => 'Casablanca',
        ]);

        Family::create([
            'student_id' => $student1->id,
            'father_firstname' => 'Mohammed',
            'father_lastname' => 'Mansouri',
            'father_profession' => 'Ingénieur',
            'mother_firstname' => 'Fatima',
            'mother_lastname' => 'Alami',
            'mother_profession' => 'Professeur',
        ]);

        $students[] = $student1;

        // Student 2
        $student2 = Student::create([
            'cne' => 'R987654321',
            'prenom' => 'Salma',
            'nom' => 'Benkirane',
            'prenom_ar' => 'سلمى',
            'nom_ar' => 'بنكيران',
            'email' => 's.benkirane@student.ma',
            'password' => Hash::make('password'),
            'apogee' => '20230002',
            'cin' => 'CD789012',
            'tel' => '0662345678',
            'date_naissance' => '2005-07-22',
            'lieu_naissance' => 'Rabat',
            'nationalite' => 'MA',
            'sexe' => 'F',
            'situation_familiale' => 'Célibataire',
            'adresse' => '45 Avenue Hassan II, Rabat',
            'pays' => 'Maroc',
            'boursier' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        BacInfo::create([
            'student_id' => $student2->id,
            'type_bac' => 'Sciences Physiques',
            'annee_bac' => 2023,
            'code_serie_bac' => 'PC',
            'serie_du_bac' => 'Sciences Physiques',
            'moyenne_generale' => 15.25,
            'moyenne_des_maths' => 16.00,
            'moyenne_sciences_physiques' => 16.50,
            'moyenne_national' => 14.50,
            'deuxieme_langue' => 'Français',
            'academie' => 'Rabat-Salé-Kénitra',
            'province_bac' => 'Rabat',
        ]);

        Family::create([
            'student_id' => $student2->id,
            'father_firstname' => 'Omar',
            'father_lastname' => 'Benkirane',
            'father_profession' => 'Médecin',
            'mother_firstname' => 'Amina',
            'mother_lastname' => 'Tazi',
            'mother_profession' => 'Avocate',
        ]);

        $students[] = $student2;

        // Student 3
        $student3 = Student::create([
            'cne' => 'R456789123',
            'prenom' => 'Karim',
            'nom' => 'Elkadi',
            'prenom_ar' => 'كريم',
            'nom_ar' => 'القاضي',
            'email' => 'k.elkadi@student.ma',
            'password' => Hash::make('password'),
            'apogee' => '20230003',
            'cin' => 'EF345678',
            'tel' => '0663456789',
            'date_naissance' => '2005-11-10',
            'lieu_naissance' => 'Fès',
            'nationalite' => 'MA',
            'sexe' => 'M',
            'situation_familiale' => 'Célibataire',
            'adresse' => '78 Boulevard Mohammed VI, Fès',
            'pays' => 'Maroc',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        BacInfo::create([
            'student_id' => $student3->id,
            'type_bac' => 'Sciences Mathématiques',
            'annee_bac' => 2023,
            'code_serie_bac' => 'SM',
            'serie_du_bac' => 'Sciences Mathématiques B',
            'moyenne_generale' => 14.75,
            'moyenne_des_maths' => 15.50,
            'moyenne_sciences_physiques' => 14.00,
            'moyenne_national' => 13.80,
            'deuxieme_langue' => 'Anglais',
            'academie' => 'Fès-Meknès',
            'province_bac' => 'Fès',
        ]);

        Family::create([
            'student_id' => $student3->id,
            'father_firstname' => 'Rachid',
            'father_lastname' => 'Elkadi',
            'father_profession' => 'Commerçant',
            'mother_firstname' => 'Khadija',
            'mother_lastname' => 'Fassi',
            'mother_profession' => 'Enseignante',
        ]);

        $students[] = $student3;

        return $students;
    }

    private function createProgramEnrollments(array $students, array $filieres, array $academicYears): array
    {
        $this->command->info('Creating program enrollments...');

        $enrollments = [];

        // Enroll students in current year (2024-2025)
        $enrollments[] = StudentProgramEnrollment::create([
            'student_id' => $students[0]->id,
            'filiere_id' => $filieres[0]->id, // Informatique
            'academic_year' => $academicYears[1]->start_year,
            'registration_year' => 2024,
            'year_in_program' => 1,
            'enrollment_status' => 'active',
            'enrollment_date' => '2024-09-15',
        ]);

        $enrollments[] = StudentProgramEnrollment::create([
            'student_id' => $students[1]->id,
            'filiere_id' => $filieres[0]->id, // Informatique
            'academic_year' => $academicYears[1]->start_year,
            'registration_year' => 2024,
            'year_in_program' => 1,
            'enrollment_status' => 'active',
            'enrollment_date' => '2024-09-15',
        ]);

        $enrollments[] = StudentProgramEnrollment::create([
            'student_id' => $students[2]->id,
            'filiere_id' => $filieres[1]->id, // Mathématiques
            'academic_year' => $academicYears[1]->start_year,
            'registration_year' => 2024,
            'year_in_program' => 1,
            'enrollment_status' => 'active',
            'enrollment_date' => '2024-09-15',
        ]);

        return $enrollments;
    }

    private function createSemesterEnrollments(array $programEnrollments): array
    {
        $this->command->info('Creating semester enrollments...');

        $semesterEnrollments = [];

        foreach ($programEnrollments as $enrollment) {
            // Create S1 enrollment
            $semesterEnrollments[] = StudentSemesterEnrollment::create([
                'program_enrollment_id' => $enrollment->id,
                'semester' => 'S1',
                'enrollment_status' => 'in_progress',
            ]);

            // Create S2 enrollment
            $semesterEnrollments[] = StudentSemesterEnrollment::create([
                'program_enrollment_id' => $enrollment->id,
                'semester' => 'S2',
                'enrollment_status' => 'registered',
            ]);
        }

        return $semesterEnrollments;
    }

    private function createModuleEnrollments(array $semesterEnrollments, array $modules): array
    {
        $this->command->info('Creating module enrollments...');

        $moduleEnrollments = [];

        // Get S1 semester enrollments (first 3)
        $s1Enrollments = array_slice($semesterEnrollments, 0, 3);

        // Student 1 & 2 - Informatique S1 modules
        foreach ([$s1Enrollments[0], $s1Enrollments[1]] as $semEnrollment) {
            $student = $semEnrollment->programEnrollment->student;

            // INFO101
            $moduleEnrollments[] = StudentModuleEnrollment::create([
                'semester_enrollment_id' => $semEnrollment->id,
                'student_id' => $student->id,
                'module_id' => $modules[0]->id,
                'attempt_number' => 1,
            ]);

            // INFO102
            $moduleEnrollments[] = StudentModuleEnrollment::create([
                'semester_enrollment_id' => $semEnrollment->id,
                'student_id' => $student->id,
                'module_id' => $modules[1]->id,
                'attempt_number' => 1,
            ]);
        }

        // Student 3 - Math S1 modules
        $student3 = $s1Enrollments[2]->programEnrollment->student;

        // MATH101
        $moduleEnrollments[] = StudentModuleEnrollment::create([
            'semester_enrollment_id' => $s1Enrollments[2]->id,
            'student_id' => $student3->id,
            'module_id' => $modules[3]->id,
            'attempt_number' => 1,
        ]);

        // MATH102
        $moduleEnrollments[] = StudentModuleEnrollment::create([
            'semester_enrollment_id' => $s1Enrollments[2]->id,
            'student_id' => $student3->id,
            'module_id' => $modules[4]->id,
            'attempt_number' => 1,
        ]);

        return $moduleEnrollments;
    }

    private function createModuleGrades(array $moduleEnrollments): void
    {
        $this->command->info('Creating module grades...');

        $professors = Professor::all();

        // Grade first 4 module enrollments (leave some ungraded)
        for ($i = 0; $i < min(4, count($moduleEnrollments)); $i++) {
            $enrollment = $moduleEnrollments[$i];
            $student = $enrollment->semesterEnrollment->programEnrollment->student;

            $cc = rand(80, 190) / 10; // 8.0 to 19.0
            $exam = rand(80, 190) / 10; // 8.0 to 19.0

            ModuleGrade::create([
                'module_enrollment_id' => $enrollment->id,
                'student_id' => $student->id,
                'module_id' => $enrollment->module_id,
                'continuous_assessment' => $cc,
                'exam_grade' => $exam,
                'exam_session' => 'normal',
                'graded_date' => now()->subDays(rand(1, 30)),
                'graded_by' => $professors->random()->id,
                'is_final' => true,
            ]);
        }
    }

    private function createDocuments(): array
    {
        $this->command->info('Creating documents...');

        $documents = [];

        $documents[] = Document::create([
            'slug' => 'attestation_scolarite',
            'label_fr' => 'Attestation de scolarité',
            'label_ar' => 'شهادة التسجيل',
            'label_en' => 'Certificate of Enrollment',
            'description' => 'Document attestant que l\'étudiant est inscrit',
            'template_path' => 'documents/attestation_scolarite.blade.php',
        ]);

        $documents[] = Document::create([
            'slug' => 'releve_notes',
            'label_fr' => 'Relevé de notes',
            'label_ar' => 'كشف النقط',
            'label_en' => 'Transcript',
            'description' => 'Relevé officiel des notes de l\'étudiant',
            'template_path' => 'documents/releve_notes.blade.php',
        ]);

        $documents[] = Document::create([
            'slug' => 'attestation_reussite',
            'label_fr' => 'Attestation de réussite',
            'label_ar' => 'شهادة النجاح',
            'label_en' => 'Certificate of Success',
            'description' => 'Document attestant la réussite de l\'étudiant',
            'template_path' => 'documents/attestation_reussite.blade.php',
        ]);

        return $documents;
    }

    private function createDemandes(array $students, array $documents, array $academicYears): void
    {
        $this->command->info('Creating demandes...');

        // Demande 1 - Pending
        Demande::create([
            'student_id' => $students[0]->id,
            'document_id' => $documents[0]->id,
            'academic_year' => $academicYears[1]->start_year,
            'semester' => 'S1',
            'status' => 'PENDING',
            'reason' => 'Pour inscription à un concours',
            'retrait_type' => 'permanent',
        ]);

        // Demande 2 - Ready
        Demande::create([
            'student_id' => $students[1]->id,
            'document_id' => $documents[1]->id,
            'academic_year' => $academicYears[1]->start_year,
            'semester' => 'S1',
            'status' => 'READY',
            'reason' => 'Pour demande de bourse',
            'retrait_type' => 'temporaire',
            'processed_by' => Admin::first()->id,
            'processed_at' => now()->subDays(2),
            'ready_at' => now()->subDays(1),
            'must_return_by' => now()->addDays(14),
        ]);

        // Demande 3 - Completed
        Demande::create([
            'student_id' => $students[2]->id,
            'document_id' => $documents[0]->id,
            'academic_year' => $academicYears[1]->start_year,
            'semester' => 'S1',
            'status' => 'COMPLETED',
            'reason' => 'Pour ouverture de compte bancaire',
            'retrait_type' => 'permanent',
            'processed_by' => Admin::first()->id,
            'processed_at' => now()->subDays(5),
            'ready_at' => now()->subDays(4),
            'collected_at' => now()->subDays(3),
        ]);
    }
}