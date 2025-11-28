<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclammation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'module_grade_id',
        'module_id',
        'reclamation_type',
        'reason',
        // 'admin_response',
        'status',
        'original_grade',
        'revised_grade',
        // 'reviewed_by',
        // 'reviewed_at',
    ];

    protected $table = "reclamations";

    protected $casts = [
        'student_id' => 'integer',
        'module_grade_id' => 'integer',
        'module_id' => 'integer',
        'original_grade' => 'decimal:2',
        'revised_grade' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function moduleGrade()
    {
        return $this->belongsTo(ModuleGrade::class, 'module_grade_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }


    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'UNDER_REVIEW');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'RESOLVED');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'REJECTED');
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Status check methods
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'UNDER_REVIEW';
    }

    public function isResolved(): bool
    {
        return $this->status === 'RESOLVED';
    }

    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }

    public function isClosed(): bool
    {
        return in_array($this->status, ['RESOLVED', 'REJECTED']);
    }

    // Action methods
    public function markAsUnderReview(int $reviewerId): void
    {
        $this->update([
            'status' => 'UNDER_REVIEW',
            'corrected_by' => $reviewerId,
            'corrected_at' => now(),
        ]);
    }

    public function resolve(float $revisedGrade, string $response, int $reviewerId): void
    {
        $this->update([
            'status' => 'RESOLVED',
            'revised_grade' => $revisedGrade,
            'admin_response' => $response,
            'corrected_by' => $reviewerId,
            'corrected_at' => now(),
        ]);

        // Update the actual module grade
        $this->moduleGrade->update([
            'final_grade' => $revisedGrade,
            'remarks' => "Note révisée suite à réclamation. Ancienne note: {$this->original_grade}",
        ]);
    }

    public function reject(string $response, int $reviewerId): void
    {
        $this->update([
            'status' => 'REJECTED',
            'admin_response' => $response,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);
    }

    // Helper methods
    public function getReclamationTypeLabel(): string
    {
        return match($this->reclamation_type) {
            'grade_calculation_error' => 'Erreur de calcul de note',
            'missing_grade' => 'Note manquante',
            'transcription_error' => 'Erreur de transcription',
            'exam_paper_review' => 'Révision de copie d\'examen',
            'other' => 'Autre',
            default => $this->reclamation_type,
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'PENDING' => 'En attente',
            'UNDER_REVIEW' => 'En cours d\'examen',
            'RESOLVED' => 'Résolue',
            'REJECTED' => 'Rejetée',
            default => $this->status,
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'PENDING' => 'bg-yellow-100 text-yellow-800',
            'UNDER_REVIEW' => 'bg-blue-100 text-blue-800',
            'RESOLVED' => 'bg-green-100 text-green-800',
            'REJECTED' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function hasGradeChanged(): bool
    {
        return $this->isResolved() &&
               $this->revised_grade !== null &&
               $this->original_grade != $this->revised_grade;
    }

    public function getGradeDifference(): ?float
    {
        if (!$this->hasGradeChanged()) {
            return null;
        }

        return round($this->revised_grade - $this->original_grade, 2);
    }

    // Model events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reclamation) {
            // Store original grade when creating reclamation
            if (!$reclamation->original_grade) {
                $reclamation->original_grade = $reclamation->moduleGrade->final_grade;
            }
        });
    }
}