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
        'grade',
        'result',
        'session',
        'exam_status',
        'is_published',
        'published_at',
        'published_by',
        'remarks',
    ];

    protected $casts = [
        'module_enrollment_id' => 'integer',
        'grade' => 'decimal:2',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'published_by' => 'integer',
    ];

    // Relationships
    public function moduleEnrollment()
    {
        return $this->belongsTo(StudentModuleEnrollment::class, 'module_enrollment_id');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'module_grade_id');
    }

    // Access module through enrollment
    public function getModuleAttribute()
    {
        return $this->moduleEnrollment?->module;
    }

    // Access student through module enrollment
    public function getStudentAttribute()
    {
        return $this->moduleEnrollment?->student;
    }

    // Scopes
    public function scopePassed($query)
    {
        return $query->whereIn('result', ['validé', 'validé après rattrapage']);
    }

    public function scopeFailed($query)
    {
        return $query->where('result', 'non validé');
    }

    public function scopeNormalSession($query)
    {
        return $query->where('session', 'normal');
    }

    public function scopeRattrapageSession($query)
    {
        return $query->where('session', 'rattrapage');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('is_published', false);
    }

    // Helper Methods
    public function isPassed(): bool
    {
        return in_array($this->result, ['validé', 'validé après rattrapage']);
    }

    public function isFailed(): bool
    {
        return $this->result === 'non validé';
    }

    public function isWaitingRattrapage(): bool
    {
        return $this->result === 'en attente rattrapage';
    }

    public function getMention(): string
    {
        if (is_null($this->grade)) {
            return 'Non noté';
        }

        return match(true) {
            $this->grade >= 16 => 'Très Bien',
            $this->grade >= 14 => 'Bien',
            $this->grade >= 12 => 'Assez Bien',
            $this->grade >= 10 => 'Passable',
            default => 'Insuffisant',
        };
    }

    public function isAbsent(): bool
    {
        return in_array($this->exam_status, ['absent', 'absent justifié']);
    }

    public function canSubmitReclamation(): bool
    {
        return $this->is_published
            && !$this->hasActiveReclamation()
            && $this->isReclamationPeriodOpen();
    }

    /**
     * Check if réclamation period is still open (24h after publication)
     */
    public function isReclamationPeriodOpen(): bool
    {
        if (!$this->is_published || !$this->published_at) {
            return false;
        }

        // Réclamations open for 24 hours after grade publication
        $deadline = $this->published_at->addHours(24);
        return now() <= $deadline;
    }

    /**
     * Get réclamation deadline
     */
    public function getReclamationDeadline(): ?\Carbon\Carbon
    {
        return $this->published_at?->addHours(24);
    }

    public function hasActiveReclamation(): bool
    {
        return $this->reclamations()
            ->whereIn('status', ['PENDING', 'UNDER_REVIEW'])
            ->exists();
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        // Automatically update enrollment final grade when grade changes
        static::saved(function ($moduleGrade) {
            if ($moduleGrade->moduleEnrollment) {
                $moduleGrade->moduleEnrollment->calculateFinalGrade();
            }
        });

        // Also update when grade is deleted
        static::deleted(function ($moduleGrade) {
            if ($moduleGrade->moduleEnrollment) {
                $moduleGrade->moduleEnrollment->calculateFinalGrade();
            }
        });
    }
}
