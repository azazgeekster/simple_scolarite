<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['label' => 'Informatique', 'head_id'=>1],
            ['label' => 'Mathématiques', 'head_id'=>null],
            ['label' => 'Physique', 'head_id'=>null],
        ];

        \DB::table('departments')->insert($departments);
    }
}