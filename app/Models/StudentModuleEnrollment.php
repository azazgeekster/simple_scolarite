<?php
// app/Models/StudentModuleEnrollment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentModuleEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_module_enrollments';

    protected $fillable = [
        'student_id',
        'semester_enrollment_id',
        'module_id',
        'attempt_number',
    ];

    protected $casts = [
        'semester_enrollment_id' => 'integer',
        'module_id' => 'integer',
        'attempt_number' => 'integer',
    ];

    // Relationships
    public function semesterEnrollment()
    {
        return $this->belongsTo(StudentSemesterEnrollment::class, 'semester_enrollment_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function grade()
    {
        return $this->hasOne(ModuleGrade::class, 'module_enrollment_id');
    }

    // Access student through semester enrollment
    public function getStudentAttribute()
    {
        return $this->semesterEnrollment?->student;
    }

    // Scopes
    public function scopeForModule($query, int $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }

    public function scopeFirstAttempt($query)
    {
        return $query->where('attempt_number', 1);
    }

    public function scopeRetakes($query)
    {
        return $query->where('attempt_number', '>', 1);
    }

    // Helper Methods
    public function isPassed(): bool
    {
        return $this->grade && $this->grade->final_grade >= 10;
    }

    public function isFailed(): bool
    {
        return $this->grade && $this->grade->final_grade < 10;
    }

    public function isRetake(): bool
    {
        return $this->attempt_number > 1;
    }

    public function getFinalGrade(): ?float
    {
        return $this->grade?->final_grade;
    }
}
