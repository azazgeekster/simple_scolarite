<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class StudentSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $students = [
            [
                'prenom' => 'Ahmed',
                'nom' => 'Benali',
                'prenom_ar' => 'أحمد',
                'nom_ar' => 'بنعلي',
                'email' => 'ahmed.benali@gmail.com',
                'password' => Hash::make('123456'),
                'cne' => 'G123456789',
                'apogee' => '20012345',
                'cin' => 'AB123456',
                'sexe' => 'm',
                'tel' => '0612345678',
                'tel_urgence' => '0619876543',
                'date_naissance' => '2000-05-15',
                'nationalite' => 'MA',
                'situation_familiale' => 'célibataire',
                'situation_professionnelle' => 'étudiant',
                'adresse' => '123 Rue Principale, Casablanca',
                'adresse_ar' => '123 شارع الرئيسي، الدار البيضاء',
                'code_postal' => '20000',
                'pays' => 'Maroc',
                'boursier' => true,
                'is_active' => true,
                'email_verified_at' => $now,
                'avatar' => 'avatars/ahmed.jpg',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        \DB::table('students')->insert($students);
    }
}
