<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ModuleGrade extends Model
{
    use HasFactory;

    protected $table = 'module_grades';

    protected $fillable = [
        'module_enrollment_id',
        'student_id',
        'module_id',

        'continuous_assessment',
        'exam_grade',
        'final_grade',
        'exam_session',
        'graded_date',
        'graded_by',
        'is_final',
        'remarks',
    ];

    protected $casts = [
        'module_enrollment_id' => 'integer',
        'module_id' => 'integer',
        'continuous_assessment' => 'decimal:2',
        'exam_grade' => 'decimal:2',
        'final_grade' => 'decimal:2',
        'graded_date' => 'date',
        'graded_by' => 'integer',
        'is_final' => 'boolean',
    ];

    // Relationships
    public function moduleEnrollment()
    {
        return $this->belongsTo(StudentModuleEnrollment::class, 'module_enrollment_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function gradedByProfessor()
    {
        return $this->belongsTo(Professor::class, 'graded_by');
    }

    // Access student through module enrollment
    public function getStudentAttribute()
    {
        return $this->moduleEnrollment?->student;
    }

    // Scopes
    public function scopePassed($query)
    {
        return $query->where('final_grade', '>=', 10);
    }

    public function scopeFailed($query)
    {
        return $query->where('final_grade', '<', 10);
    }

    public function scopeNormalSession($query)
    {
        return $query->where('exam_session', 'normal');
    }

    public function scopeRattrapageSession($query)
    {
        return $query->where('exam_session', 'rattrapage');
    }

    public function scopeFinalOnly($query)
    {
        return $query->where('is_final', true);
    }

    // Grade Calculation
    public function calculateFinalGrade(): ?float
    {
        if (is_null($this->continuous_assessment) || is_null($this->exam_grade)) {
            return null;
        }

        $module = $this->module;
        $ccWeight = $module ? ($module->cc_percentage / 100) : 0.4;
        $examWeight = $module ? ($module->exam_percentage / 100) : 0.6;

        return round(
            ($this->continuous_assessment * $ccWeight) +
            ($this->exam_grade * $examWeight),
            2
        );
    }

    // Helper Methods
    public function isPassed(): bool
    {
        return !is_null($this->final_grade) && $this->final_grade >= 10;
    }

    public function isFailed(): bool
    {
        return !is_null($this->final_grade) && $this->final_grade < 10;
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

    public function needsRattrapage(): bool
    {
        return $this->exam_session === 'normal'
            && $this->isFailed()
            && $this->final_grade >= 6;
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($grade) {
            if ($grade->isDirty(['continuous_assessment', 'exam_grade'])) {
                $grade->final_grade = $grade->calculateFinalGrade();
            }
        });
    }
}
