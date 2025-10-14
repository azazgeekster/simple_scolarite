<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentProgramEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_program_enrollments';

    protected $fillable = [
        'student_id',
        'filiere_id',
        'academic_year',
        'registration_year',
        'year_in_program',
        'enrollment_status',
        'enrollment_date',
        'notes',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'filiere_id' => 'integer',
        'academic_year' => 'integer',
        'registration_year' => 'integer',
        'year_in_program' => 'integer',
        'enrollment_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'start_year');
    }

    public function semesterEnrollments()
    {
        return $this->hasMany(StudentSemesterEnrollment::class, 'program_enrollment_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('enrollment_status', 'active');
    }

    public function scopeForYear($query, int $yearInProgram)
    {
        return $query->where('year_in_program', $yearInProgram);
    }

    public function scopeForAcademicYear($query, int $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->enrollment_status === 'active';
    }

    public function getSemestersForYear(): array
    {
        return match($this->year_in_program) {
            1 => ['S1', 'S2'],
            2 => ['S3', 'S4'],
            3 => ['S5', 'S6'],
            default => [],
        };
    }

    public function getAcademicYearLabelAttribute(): string
    {
        return sprintf('%d-%d', $this->academic_year, $this->academic_year + 1);
    }
}
