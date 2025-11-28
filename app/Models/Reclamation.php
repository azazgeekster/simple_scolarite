<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $table = 'reclamations';

    // Status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';
    const STATUS_RESOLVED = 'RESOLVED';
    const STATUS_REJECTED = 'REJECTED';

    protected $fillable = [
        'reference',
        'module_grade_id',
        'reclamation_type',
        'reason',
        'session',
        'admin_response',
        'status',
        'original_grade',
        'revised_grade',
        'corrected_by',
        'corrector_type',
        'corrected_at',
    ];

    protected $casts = [
        'module_grade_id' => 'integer',
        'original_grade' => 'decimal:2',
        'revised_grade' => 'decimal:2',
        'corrected_at' => 'datetime',
    ];

    // Boot method for auto-generating reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reclamation) {
            if (empty($reclamation->reference)) {
                $reclamation->reference = self::generateReference();
            }
        });
    }

    // Generate unique reference number (format: REC-XXXXXX)
    public static function generateReference(): string
    {
        do {
            $reference = date('Y') . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    // Polymorphic relationship for corrector (can be Admin or Professor)
    public function corrector()
    {
        return $this->morphTo('corrector', 'corrector_type', 'corrected_by');
    }

    // Relationships
    public function moduleGrade()
    {
        return $this->belongsTo(ModuleGrade::class, 'module_grade_id');
    }

    // Access student through module grade
    public function getStudentAttribute()
    {
        return $this->moduleGrade?->student;
    }

    // Access module through module grade
    public function getModuleAttribute()
    {
        return $this->moduleGrade?->module;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeForSession($query, $session)
    {
        return $query->where('session', $session);
    }

    // Status Check Methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isResolved(): bool
    {
        return $this->status === self::STATUS_RESOLVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_REJECTED]);
    }

    public function hasGradeChange(): bool
    {
        return !is_null($this->revised_grade) && $this->revised_grade != $this->original_grade;
    }

    public function hasGradeChanged(): bool
    {
        return $this->hasGradeChange();
    }

    // Status Transition Methods
    public function markAsUnderReview(string $adminResponse = null): self
    {
        $this->update([
            'status' => self::STATUS_UNDER_REVIEW,
            'admin_response' => $adminResponse ?? $this->admin_response,
        ]);

        return $this;
    }

    public function resolve(float $revisedGrade, string $adminResponse): self
    {
        // Get the authenticated admin (for now, only admins can resolve reclamations)
        $correctorId = auth('admin')->id();
        $correctorType = \App\Models\Admin::class;

        $this->update([
            'status' => self::STATUS_RESOLVED,
            'revised_grade' => $revisedGrade,
            'admin_response' => $adminResponse,
            'corrected_by' => $correctorId,
            'corrector_type' => $correctorType,
            'corrected_at' => now(),
        ]);

        return $this;
    }

    public function reject(string $adminResponse): self
    {
        // Get the authenticated admin (for now, only admins can reject reclamations)
        $correctorId = auth('admin')->id();
        $correctorType = \App\Models\Admin::class;

        $this->update([
            'status' => self::STATUS_REJECTED,
            'admin_response' => $adminResponse,
            'corrected_by' => $correctorId,
            'corrector_type' => $correctorType,
            'corrected_at' => now(),
        ]);

        return $this;
    }

    // UI Helper Methods
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_UNDER_REVIEW => 'En cours d\'examen',
            self::STATUS_RESOLVED => 'Résolue',
            self::STATUS_REJECTED => 'Rejetée',
            default => $this->status,
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_UNDER_REVIEW => 'bg-blue-100 text-blue-800',
            self::STATUS_RESOLVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getReclamationTypeLabel(): string
    {
        return match($this->reclamation_type) {
            'grade_calculation_error' => 'Erreur de calcul de note',
            'missing_grade' => 'Note manquante',
            'transcription_error' => 'Erreur de saisie',
            'exam_paper_review' => 'Révision de copie d\'examen',
            'other' => 'Autre',
            default => $this->reclamation_type,
        };
    }
}
