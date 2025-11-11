<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamConvocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_module_enrollment_id',
        'n_examen',
        'location',
        'observations',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'student_module_enrollment_id' => 'integer',
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function studentModuleEnrollment()
    {
        return $this->belongsTo(StudentModuleEnrollment::class);
    }

    // Helper to get student through enrollment
    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            StudentModuleEnrollment::class,
            'id', // Foreign key on student_module_enrollment
            'id', // Foreign key on students
            'student_module_enrollment_id', // Local key on exam_convocations
            'student_id' // Local key on student_module_enrollment
        );
    }
}
