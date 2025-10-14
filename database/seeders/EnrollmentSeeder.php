<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Module;
use App\Models\StudentAcademicFiliere;
use App\Models\StudentEnrollment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $filiere = Filiere::firstOrCreate([
            'label_fr' => 'Licence Informatique',
            'code' => 'INFO-LIC',
            'level' => 'licence',
        ]);
        $studentFiliere = StudentAcademicFiliere::create([
            'student_id' => 1,
            'filiere_id' => 1,
            'academic_year_id' => 10,
            // 'year_in_program' => 1,
        ]);

        $modules = Module::factory()->count(3)->create([
            'filiere_id' => $filiere->id,
            'semester_id' => 1,
            'year_in_program' => 1,
        ]);

        foreach ($modules as $module) {
            StudentEnrollment::create([
                'student_id' => 1,
                'academic_year_id' => 10,
                'semester_id' => 1,
                'module_id' => $module->id,
                'status' => 'enrolled',
                // 'grade' => null,
            ]);
        }
    }
}
