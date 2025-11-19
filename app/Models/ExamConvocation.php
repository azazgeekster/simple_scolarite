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
        'local_id',
        'n_examen',
        'observations',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'student_module_enrollment_id' => 'integer',
        'local_id' => 'integer',
        'n_examen' => 'string',
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

    public function local()
    {
        return $this->belongsTo(Local::class);
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
