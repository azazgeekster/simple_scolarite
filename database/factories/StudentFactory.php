<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'prenom' => $this->faker->firstName(),
            'nom' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'cne' => Str::random(10),
            'apogee' => (string) $this->faker->unique()->numberBetween(1000, 9999),
            'cin' => Str::upper(Str::random(6)),
            'sexe' => 'm',
        ];
    }
}
