<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    public function definition(): array
    {
        $start = $this->faker->numberBetween(2018, 2024);
        return [
            'label' => $start . '-' . ($start + 1),
            'start_date' => $start,
            'end_date' => $start + 1,
        ];
    }
}
