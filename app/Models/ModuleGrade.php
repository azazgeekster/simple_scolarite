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
        return $query->whereIn('result', ['V', 'VR', 'AC']);
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('result', ['NV', 'AJ', 'ABI']);
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
        return in_array($this->result, ['V', 'VR', 'AC']);
    }

    public function isFailed(): bool
    {
        return in_array($this->result, ['NV', 'AJ', 'ABI']);
    }

    public function isWaitingRattrapage(): bool
    {
        return $this->result === 'RATT';
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
     * Check if réclamation period is still open
     * Checks both time window and admin settings
     */
    public function isReclamationPeriodOpen(): bool
    {
        if (!$this->is_published || !$this->published_at) {
            \Log::info('Reclamation check failed: not published', [
                'grade_id' => $this->id,
                'is_published' => $this->is_published,
                'published_at' => $this->published_at,
            ]);
            return false;
        }

        // Check if admin has enabled reclamations for this module
        $moduleEnrollment = $this->moduleEnrollment;
        if (!$moduleEnrollment) {
            \Log::info('Reclamation check failed: no module enrollment', ['grade_id' => $this->id]);
            return false;
        }

        $module = $moduleEnrollment->module;
        $programEnrollment = $moduleEnrollment->programEnrollment;
        
        if (!$module || !$programEnrollment) {
            \Log::info('Reclamation check failed: no module or program enrollment', [
                'grade_id' => $this->id,
                'has_module' => !is_null($module),
                'has_program' => !is_null($programEnrollment),
            ]);
            return false;
        }

        // Check if reclamation setting exists and is active
        $reclamationSetting = \App\Models\ReclamationSetting::where('academic_year', $programEnrollment->academic_year)
            ->where('session', $this->session)
            ->where('module_id', $module->id)
            ->where('is_active', true)
            ->first();

        if (!$reclamationSetting) {
            \Log::info('Reclamation check failed: no active setting', [
                'grade_id' => $this->id,
                'module_id' => $module->id,
                'academic_year' => $programEnrollment->academic_year,
                'session' => $this->session,
            ]);
            return false; // Admin hasn't enabled reclamations for this module
        }

        // Réclamations open for 24 hours after grade publication
        $deadline = $this->published_at->copy()->addHours(24);
        $isWithinDeadline = now() <= $deadline;
        
        \Log::info('Reclamation check result', [
            'grade_id' => $this->id,
            'published_at' => $this->published_at,
            'deadline' => $deadline,
            'now' => now(),
            'is_within_deadline' => $isWithinDeadline,
            'setting_found' => true,
        ]);
        
        return $isWithinDeadline;
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

    public function getValidationStatusAttribute(): array
    {
        return match($this->result) {
            'V' => ['label' => 'V', 'color' => 'green'],
            'VR' => ['label' => 'VR', 'color' => 'green'],
            'AC' => ['label' => 'AC', 'color' => 'blue'],
            'NV' => ['label' => 'NV', 'color' => 'red'],
            'AJ' => ['label' => 'AJ', 'color' => 'red'],
            'ABI' => ['label' => 'ABI', 'color' => 'gray'],
            'RATT' => ['label' => 'RATT', 'color' => 'yellow'],
            default => ['label' => $this->result ?? '-', 'color' => 'gray'],
        };
    }

    /**
     * Rattrapage Management Methods
     */
    
    /**
     * Check if student is eligible for rattrapage
     */
    public function isEligibleForRattrapage(): bool
    {
        return $this->result === 'RATT';
    }

    /**
     * Justify an absence - changes ABI to RATT
     */
    public function justifyAbsence(string $reason = null, string $document = null, int $justifiedBy = null): bool
    {
        if ($this->result !== 'ABI') {
            return false; // Only ABI can be justified
        }

        if ($this->is_absence_justified) {
            return false; // Already justified
        }

        // Update grade result
        $this->result = 'RATT';
        $this->is_absence_justified = true;
        $this->save();

        // Create justification record
        $moduleEnrollment = $this->moduleEnrollment;
        if ($moduleEnrollment && $moduleEnrollment->programEnrollment) {
            JustifiedAbsence::create([
                'student_id' => $moduleEnrollment->programEnrollment->student_id,
                'module_id' => $moduleEnrollment->module_id,
                'academic_year' => $moduleEnrollment->programEnrollment->academic_year,
                'session' => $this->session,
                'justification_reason' => $reason,
                'justification_document' => $document,
                'justified_at' => now(),
                'justified_by' => $justifiedBy ?? auth('admin')->id(),
            ]);
        }

        return true;
    }

    /**
     * Unjustify an absence - changes RATT back to ABI (if was justified)
     */
    public function unjustifyAbsence(): bool
    {
        if (!$this->is_absence_justified) {
            return false; // Not justified
        }

        // Update grade result
        $this->result = 'ABI';
        $this->is_absence_justified = false;
        $this->save();

        // Delete justification record
        $moduleEnrollment = $this->moduleEnrollment;
        if ($moduleEnrollment && $moduleEnrollment->programEnrollment) {
            JustifiedAbsence::where('student_id', $moduleEnrollment->programEnrollment->student_id)
                ->where('module_id', $moduleEnrollment->module_id)
                ->where('session', $this->session)
                ->delete();
        }

        return true;
    }

    /**
     * Get justification record
     */
    public function justification()
    {
        $moduleEnrollment = $this->moduleEnrollment;
        if (!$moduleEnrollment || !$moduleEnrollment->programEnrollment) {
            return null;
        }

        return JustifiedAbsence::where('student_id', $moduleEnrollment->programEnrollment->student_id)
            ->where('module_id', $moduleEnrollment->module_id)
            ->where('session', $this->session)
            ->first();
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
