<?php
// app/Models/StudentSemesterEnrollment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSemesterEnrollment extends Model
{
    use HasFactory;

    protected $table = 'student_semester_enrollments';

    protected $fillable = [
        'program_enrollment_id',
        'semester',
        'enrollment_status',
    ];

    // Relationships
    public function programEnrollment()
    {
        return $this->belongsTo(StudentProgramEnrollment::class, 'program_enrollment_id');
    }

    public function moduleEnrollments()
    {
        return $this->hasMany(StudentModuleEnrollment::class, 'semester_enrollment_id');
    }

    // Access student through program enrollment
    public function getStudentAttribute()
    {
        return $this->programEnrollment?->student;
    }

    // Access academic year through program enrollment
    public function getAcademicYearAttribute()
    {
        return $this->programEnrollment?->academic_year;
    }

    // Scopes
    public function scopeForSemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeInProgress($query)
    {
        return $query->where('enrollment_status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('enrollment_status', 'completed');
    }

    // Helper Methods
    public function calculateAverage(): ?float
    {
        $grades = $this->moduleEnrollments()
            ->with('grade')
            ->get()
            ->pluck('grade.final_grade')
            ->filter()
            ->reject(fn($value) => is_null($value));

        return $grades->isEmpty() ? null : round($grades->average(), 2);
    }

    public function getModuleCount(): int
    {
        return $this->moduleEnrollments()->count();
    }

    public function getPassedModuleCount(): int
    {
        return $this->moduleEnrollments()
            ->whereHas('grade', function($query) {
                $query->where('final_grade', '>=', 10);
            })
            ->count();
    }
}