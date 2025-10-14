<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $startYear = 2015;
        $endYear = 2030;
        $currentYear = 2025; // Set which year is current

        for ($year = $startYear; $year <= $endYear; $year++) {
            AcademicYear::create([
                'start_year' => $year,
                'is_current' => ($year === $currentYear),
            ]);
        }

        $this->command->info("Seeded academic years from $startYear to $endYear");
        $this->command->info("Current year: $currentYear-" . ($currentYear + 1));
    }
}