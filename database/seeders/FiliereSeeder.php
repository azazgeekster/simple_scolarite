<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = [
            [
                'label_fr' => 'Génie Informatique',
                'label_ar' =>' علوم الحاسوب',
                'code' => 'GI',
                'department_id' => 1,
                // 'semester_id' => 1,
            ],
            [
                'label_fr' => 'Mathématiques Appliquées',
                'label_ar' => null,
                'code' => 'MA',
                'department_id' => 2,
                // 'semester_id' => 3,
            ],
            [
                'label_fr' => 'Physique Fondamentale',
                'label_ar' => null,
                'code' => 'PH',
                'department_id' => 3,
                // 'semester_id' => 5,
            ],
        ];

        \DB::table('filieres')->insert($filieres);
    }
}