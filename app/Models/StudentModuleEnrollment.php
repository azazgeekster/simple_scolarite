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
        'program_enrollment_id',
        'semester',
        'module_id',
        'registration_year',
        'attempt_number',
        'final_grade',
        'final_result',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'program_enrollment_id' => 'integer',
        'module_id' => 'integer',
        'registration_year' => 'integer',
        'attempt_number' => 'integer',
        'final_grade' => 'decimal:2',
    ];

    // Relationships
    public function programEnrollment()
    {
        return $this->belongsTo(StudentProgramEnrollment::class, 'program_enrollment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function grades()
    {
        return $this->hasMany(ModuleGrade::class, 'module_enrollment_id');
    }

    // Get normal session grade
    public function normalGrade()
    {
        return $this->hasOne(ModuleGrade::class, 'module_enrollment_id')
            ->where('session', 'normal');
    }

    // Get rattrapage session grade
    public function rattrapageGrade()
    {
        return $this->hasOne(ModuleGrade::class, 'module_enrollment_id')
            ->where('session', 'rattrapage');
    }

    public function examConvocations()
    {
        return $this->hasMany(ExamConvocation::class);
    }

    // Scopes
    public function scopeForModule($query, int $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }

    public function scopeForSemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeFirstAttempt($query)
    {
        return $query->where('attempt_number', 1);
    }

    public function scopeRetakes($query)
    {
        return $query->where('attempt_number', '>', 1);
    }

    // Scopes for final results
    public function scopePassed($query)
    {
        return $query->whereIn('final_result', ['validé', 'validé après rattrapage']);
    }

    public function scopeFailed($query)
    {
        return $query->where('final_result', 'non validé');
    }

    public function scopeWaitingRattrapage($query)
    {
        return $query->where('final_result', 'en attente rattrapage');
    }

    // Helper Methods
    public function isPassed(): bool
    {
        return in_array($this->final_result, ['validé', 'validé après rattrapage']);
    }

    public function isFailed(): bool
    {
        return $this->final_result === 'non validé';
    }

    public function isWaitingRattrapage(): bool
    {
        return $this->final_result === 'en attente rattrapage';
    }

    public function isRetake(): bool
    {
        return $this->attempt_number > 1;
    }

    public function getMention(): string
    {
        if (is_null($this->final_grade)) {
            return 'Non noté';
        }

        return match(true) {
            $this->final_grade >= 16 => 'Très Bien',
            $this->final_grade >= 14 => 'Bien',
            $this->final_grade >= 12 => 'Assez Bien',
            $this->final_grade >= 10 => 'Passable',
            default => 'Insuffisant',
        };
    }

    /**
     * Calculate and update final grade based on all session grades
     */
    public function calculateFinalGrade(): void
    {
        $normalGrade = $this->grades()->where('session', 'normal')->first();
        $rattrapageGrade = $this->grades()->where('session', 'rattrapage')->first();

        // Determine which grade counts
        $finalGrade = null;
        $finalResult = null;

        if ($rattrapageGrade && $rattrapageGrade->grade !== null) {
            // If rattrapage exists, it's the final grade
            $finalGrade = $rattrapageGrade->grade;
            $finalResult = $finalGrade >= 10 ? 'validé après rattrapage' : 'non validé';
        } elseif ($normalGrade && $normalGrade->grade !== null) {
            // Use normal session grade
            $finalGrade = $normalGrade->grade;

            if ($finalGrade >= 10) {
                $finalResult = 'validé';
            } elseif ($finalGrade >= 6) {
                $finalResult = 'en attente rattrapage';
            } else {
                $finalResult = 'non validé';
            }
        }

        // Update enrollment with final grade and result
        $this->update([
            'final_grade' => $finalGrade,
            'final_result' => $finalResult,
        ]);
    }

    /**
     * Get the grade that counts (for display purposes)
     */
    public function getCountingGrade(): ?ModuleGrade
    {
        // Rattrapage takes precedence
        $rattrapageGrade = $this->grades()->where('session', 'rattrapage')->first();
        if ($rattrapageGrade && $rattrapageGrade->grade !== null) {
            return $rattrapageGrade;
        }

        // Otherwise return normal
        return $this->grades()->where('session', 'normal')->first();
    }
}
