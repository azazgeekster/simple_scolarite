<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professors = [
            'prenom' => 'Azzedine',
             'nom' => 'Dliou',
             'specialization' =>'Informatique',
        ];
        \DB::table('professors')->insert($professors);

    }
}
