<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'slug' => 'bac',
                'label_fr' => 'Baccalauréat',
                'label_ar' => 'شهادة البكالوريا',
                'label_en' => 'Baccalaureate',
            ],
            [
                'slug' => 'attestation_reussite',
                'label_fr' => 'Attestation de réussite',
                'label_ar' => 'شهادة النجاح',
                'label_en' => 'Certificate of Success',
            ],
            [
                'slug' => 'attestation_scolarite',
                'label_fr' => 'Attestation de scolarité',
                'label_ar' => 'شهادة مدرسية',
                'label_en' => 'Enrollment Certificate',
            ],
            [
                'slug' => 'attestation_langue',
                'label_fr' => 'Attestation de langue',
                'label_ar' => 'شهادة اللغة',
                'label_en' => 'Language Certificate',
            ],
            [
                'slug' => 'attestation_classement',
                'label_fr' => 'Attestation de classement',
                'label_ar' => 'شهادة الترتيب',
                'label_en' => 'Ranking Certificate',
            ],
            [
                'slug' => 'releve_notes',
                'label_fr' => 'Relevé de notes',
                'label_ar' => 'كشف النقط',
                'label_en' => 'Transcript',
            ],
            [
                'slug' => 'carte_etudiant',
                'label_fr' => "Carte d'étudiant",
                'label_ar' => 'بطاقة الطالب',
                'label_en' => 'Student Card',
            ],
        ];

        \DB::table('documents')->insert($documents);

    }
}
