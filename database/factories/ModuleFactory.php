<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->bothify('M###')),
            'label' => fake()->sentence(3),
            'label_ar' => null,
            'semester_id' => 1,
            'year_in_program' => 1,
            'filiere_id' => null,
            'professor_id' => null,
            'prerequisite_id' => null,
        ];
    }
}
