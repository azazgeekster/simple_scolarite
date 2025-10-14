<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Document;
use App\Models\Filiere;
use App\Models\Student;
use Database\Seeders\DocumentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranscriptRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_releve_dropdown_shows_student_years_and_majors(): void
    {
        $student = Student::factory()->create();
        $majorA = Filiere::factory()->create(['label_fr' => 'Major A']);
        $majorB = Filiere::factory()->create(['label_fr' => 'Major B']);

        $y2019 = AcademicYear::factory()->create(['label' => '2019-2020', 'start_date' => 2019, 'end_date' => 2020]);
        $y2020 = AcademicYear::factory()->create(['label' => '2020-2021', 'start_date' => 2020, 'end_date' => 2021]);
        $y2021 = AcademicYear::factory()->create(['label' => '2021-2022', 'start_date' => 2021, 'end_date' => 2022]);
        $y2023 = AcademicYear::factory()->create(['label' => '2023-2024', 'start_date' => 2023, 'end_date' => 2024]);

        $student->academicMajors()->attach($y2019->id, ['filiere_id' => $majorA->id]);
        $student->academicMajors()->attach($y2020->id, ['filiere_id' => $majorA->id]);
        $student->academicMajors()->attach($y2021->id, ['filiere_id' => $majorA->id]);
        $student->academicMajors()->attach($y2023->id, ['filiere_id' => $majorB->id]);

        $response = $this->actingAs($student, 'student')->get('/student/releve');

        $response->assertStatus(200);
        $response->assertSee('2019-2020 (Major A)');
        $response->assertSee('2020-2021 (Major A)');
        $response->assertSee('2021-2022 (Major A)');
        $response->assertSee('2023-2024 (Major B)');
    }

    public function test_student_can_request_transcript_and_get_pdf(): void
    {
        $this->seed(DocumentSeeder::class);

        $student = Student::factory()->create();
        $major = Filiere::factory()->create();
        $year = AcademicYear::factory()->create(['label' => '2019-2020', 'start_date' => 2019, 'end_date' => 2020]);

        $student->academicMajors()->attach($year->id, ['filiere_id' => $major->id]);

        $documentId = Document::where('slug', 'releve_notes')->first()->id;

        $response = $this->actingAs($student, 'student')
            ->post('/student/releve', ['years' => [$year->id]]);

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));

        $this->assertDatabaseHas('demandes', [
            'student_id' => $student->id,
            'document_id' => $documentId,
            'academic_year_id' => $year->id,
            'status' => 'PENDING',
        ]);
    }
}
