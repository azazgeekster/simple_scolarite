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
        'diploma_level',
        'diploma_year',
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
        'diploma_year' => 'integer',
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

    public function moduleEnrollments()
    {
        return $this->hasMany(StudentModuleEnrollment::class, 'program_enrollment_id');
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

    public function getSemestersForDiplomaYear(): array
    {
        // Based on diploma_year if available, otherwise fallback to year_in_program
        $year = $this->diploma_year ?? $this->year_in_program;

        return match($year) {
            1 => ['S1', 'S2'],
            2 => ['S3', 'S4'],
            3 => ['S5', 'S6'],
            4 => ['S7', 'S8'],
            5 => ['S9', 'S10'],
            default => [],
        };
    }

    /**
     * Alias for getSemestersForDiplomaYear() for backward compatibility
     */
    public function getSemestersForYear(): array
    {
        return $this->getSemestersForDiplomaYear();
    }

    public function getDiplomaLevelLabelAttribute(): string
    {
        return match($this->diploma_level) {
            'deug' => 'DEUG',
            'licence' => 'Licence',
            'master' => 'Master',
            'doctorat' => 'Doctorat',
            default => 'Non défini',
        };
    }

    /**
     * Get cycle label from diploma_level or filiere level (Licence/Master/Doctorat)
     */
    public function getCycleLabelAttribute(): string
    {
        // Use diploma_level if set, otherwise fall back to filiere level
        $level = $this->diploma_level ?? ($this->filiere ? strtolower($this->filiere->level) : '');

        return match($level) {
            'licence', 'license' => 'Licence',
            'deug', 'DEUG' => 'DEUG',
            'master' => 'Master',
            'doctorat', 'doctorate' => 'Doctorat',
            default => ucfirst($level)
        };
    }

    public function getAcademicYearLabelAttribute(): string
    {
        return sprintf('%d-%d', $this->academic_year, $this->academic_year + 1);
    }
    public function getYearLabelAttribute(): string
    {
        $year = $this->diploma_year ?? $this->year_in_program;

        if (!$year) {
            return 'Année non définie';
        }

        $ordinal = match($year) {
            1 => '1ère',
            2 => '2ème',
            3 => '3ème',
            4 => '4ème',
            5 => '5ème',
            default => "{$year}ème"
        };

        // Use diploma_level if set, otherwise fall back to filiere level
        $level = $this->diploma_level
            ? $this->diploma_level_label
            : ($this->filiere ? ucfirst($this->filiere->level) : '');

        return $level ? "{$ordinal} année {$level}" : "{$ordinal} année";
    }
}
