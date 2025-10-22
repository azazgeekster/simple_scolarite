<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Demande extends Model
{
    use HasFactory;

    protected $table = 'demandes';

    protected $fillable = [
        'reference_number',
        'student_id',
        'document_id',
        'academic_year',
        'semester',
        'status',
        'reason',
        'retrait_type',
        'processed_by',
        'processed_at',
        'ready_at',
        'must_return_by',
        'returned_at',
        'extension_requested_at',
        'extension_days',
        'collected_at',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'document_id' => 'integer',
        'academic_year' => 'integer',
        'processed_by' => 'integer',
        'extension_days' => 'integer',
        'processed_at' => 'datetime',
        'ready_at' => 'datetime',
        'must_return_by' => 'datetime',
        'returned_at' => 'datetime',
        'extension_requested_at' => 'datetime',
        'collected_at' => 'datetime',
    ];

    // ==========================================
    // MODEL EVENTS
    // ==========================================

    protected static function boot()
    {
        parent::boot();

        // Auto-generate reference number before creating
        static::creating(function ($demande) {
            if (empty($demande->reference_number)) {
                $demande->reference_number = static::generateReferenceNumber();
            }
        });
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * The student who made this request
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * The document type requested
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * The academic year for this request
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'start_year');
    }

    /**
     * The admin who processed this request
     */
    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    // ==========================================
    // REFERENCE NUMBER GENERATION
    // ==========================================

    /**
     * Generate unique reference number
     * Format: REQ-YYYY-NNNNN (e.g., REQ-2025-00001)
     */
    public static function generateReferenceNumber(): string
    {
        $year = now()->year;
        $prefix = "REQ-{$year}-";

        // Get the last reference number for this year
        $lastDemande = static::where('reference_number', 'LIKE', "{$prefix}%")
            ->orderBy('reference_number', 'desc')
            ->first();

        if ($lastDemande) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastDemande->reference_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            // First request of the year
            $newNumber = 1;
        }

        // Format: REQ-2025-00001
        return $prefix.str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Alternative: Generate reference with student info
     * Format: REQ-YYYY-CNE-NN (e.g., REQ-2025-R123456-01)
     */
    public static function generateReferenceNumberWithCNE(Student $student): string
    {
        $year = now()->year;
        $cne = substr($student->cne, 0, 8); // Take first 8 chars of CNE
        $prefix = "REQ-{$year}-{$cne}-";

        // Count existing requests for this student this year
        $count = static::where('student_id', $student->id)
            ->whereYear('created_at', $year)
            ->count();

        $newNumber = $count + 1;

        return $prefix.str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Alternative: Generate reference with document code
     * Format: ATT-2025-00001 (ATT for attestation, REL for relevé, etc.)
     */
    public static function generateReferenceNumberWithDocCode(Document $document): string
    {
        $year = now()->year;
        $docCode = strtoupper(substr($document->slug, 0, 3));
        $prefix = "{$docCode}-{$year}-";

        $lastDemande = static::where('reference_number', 'LIKE', "{$prefix}%")
            ->orderBy('reference_number', 'desc')
            ->first();

        if ($lastDemande) {
            $lastNumber = (int) substr($lastDemande->reference_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix.str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    // ==========================================
    // STATUS HELPER METHODS
    // ==========================================

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    /**
     * Check if request is ready for pickup
     */
    public function isReady(): bool
    {
        return $this->status === 'READY';
    }

    /**
     * Check if document was picked up
     */
    public function isPicked(): bool
    {
        return $this->status === 'PICKED';
    }

    /**
     * Check if request is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }

    /**
     * Mark as ready for pickup
     */
    public function markAsReady(?int $adminId = null): bool
    {
        $this->status = 'READY';
        $this->ready_at = now();

        if ($adminId) {
            $this->processed_by = $adminId;
            $this->processed_at = now();
        }

        return $this->save();
    }

    /**
     * Mark as picked up
     */
    public function markAsPicked(): bool
    {
        $this->status = 'PICKED';
        $this->collected_at = now();
        return $this->save();
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): bool
    {
        $this->status = 'COMPLETED';

        if ($this->isTemporaire() && ! $this->returned_at) {
            $this->returned_at = now();
        }

        return $this->save();
    }

    // ==========================================
    // RETRAIT TYPE HELPER METHODS
    // ==========================================

    /**
     * Check if retrait is temporary
     */
    public function isTemporaire(): bool
    {
        return $this->retrait_type === 'temporaire';
    }

    /**
     * Check if retrait is permanent
     */
    public function isPermanent(): bool
    {
        return $this->retrait_type === 'permanent';
    }

    /**
     * Check if document is overdue (for temporary retraits)
     */
    public function isOverdue(): bool
    {
        if (! $this->isTemporaire() || ! $this->must_return_by) {
            return false;
        }

        return now()->isAfter($this->must_return_by) && is_null($this->returned_at);
    }

    /**
     * Get days until return deadline
     */
    public function getDaysUntilReturn(): ?int
    {
        if (! $this->isTemporaire() || ! $this->must_return_by || $this->returned_at) {
            return null;
        }

        return now()->diffInDays($this->must_return_by, false);
    }

    /**
     * Get days overdue
     */
    public function getDaysOverdue(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->must_return_by);
    }

    /**
     * Set return deadline (for temporary retraits)
     */
    public function setReturnDeadline(int $days = 2): bool
    {
        if (! $this->isTemporaire()) {
            return false;
        }

        $this->must_return_by = now()->addDays($days);
        return $this->save();
    }

    /**
     * Request extension
     */
    public function requestExtension(int $additionalDays): bool
    {
        if (! $this->isTemporaire() || $this->returned_at) {
            return false;
        }

        $this->extension_requested_at = now();
        $this->extension_days = $additionalDays;
        return $this->save();
    }

    /**
     * Approve extension
     */
    public function approveExtension(): bool
    {
        if (! $this->extension_requested_at || ! $this->extension_days) {
            return false;
        }

        $this->must_return_by = $this->must_return_by->addDays($this->extension_days);
        $this->extension_requested_at = null;
        $this->extension_days = null;

        return $this->save();
    }

    // ==========================================
    // ACCESSOR ATTRIBUTES
    // ==========================================

    /**
     * Get status label in French
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'En attente',
            'READY' => 'Prêt',
            'PICKED' => 'Retiré',
            'COMPLETED' => 'Terminé',
            default => 'Inconnu',
        };
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'PENDING' => 'yellow',
            'READY' => 'blue',
            'PICKED' => 'green',
            'COMPLETED' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get processing time in days
     */
    public function getProcessingTimeAttribute(): ?int
    {
        if (! $this->processed_at) {
            return null;
        }

        return $this->created_at->diffInDays($this->processed_at);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    /**
     * Scope for ready requests
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'READY');
    }

    /**
     * Scope for picked requests
     */
    public function scopePicked($query)
    {
        return $query->where('status', 'PICKED');
    }

    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    /**
     * Scope for temporary retraits
     */
    public function scopeTemporaire($query)
    {
        return $query->where('retrait_type', 'temporaire');
    }

    /**
     * Scope for permanent retraits
     */
    public function scopePermanent($query)
    {
        return $query->where('retrait_type', 'permanent');
    }

    /**
     * Scope for overdue documents
     */
    public function scopeOverdue($query)
    {
        return $query->where('retrait_type', 'temporaire')
            ->whereNotNull('must_return_by')
            ->where('must_return_by', '<', now())
            ->whereNull('returned_at');
    }

    /**
     * Scope for requests with extension requested
     */
    public function scopeExtensionRequested($query)
    {
        return $query->whereNotNull('extension_requested_at');
    }

    /**
     * Scope for specific student
     */
    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope for specific document type
     */
    public function scopeForDocument($query, int $documentId)
    {
        return $query->where('document_id', $documentId);
    }

    /**
     * Scope for specific academic year
     */
    public function scopeForAcademicYear($query, int $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope to search by reference number or student
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('reference_number', 'LIKE', "%{$search}%")
                ->orWhereHas('student', function ($sq) use ($search) {
                    $sq->where('cne', 'LIKE', "%{$search}%")
                        ->orWhere('prenom', 'LIKE', "%{$search}%")
                        ->orWhere('nom', 'LIKE', "%{$search}%");
                });
        });
    }

    /**
     * Scope for recent requests (last 30 days)
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }


    /**
     * Check if demande can still be cancelled
     */
    public function canBeCancelled(): bool
    {
        if ($this->status !== 'PENDING') {
            return false;
        }

        // Can cancel within 30 minutes of creation
        $cancellationDeadline = $this->created_at->addMinutes(5);
        return now()->isBefore($cancellationDeadline);
    }

    /**
     * Get remaining time to cancel in minutes
     */
    public function getRemainingCancellationTime(): int
    {
        if ($this->status !== 'PENDING') {
            return 0;
        }

        $cancellationDeadline = $this->created_at->addMinutes(5);
        $remaining = now()->diffInMinutes($cancellationDeadline, false);

        return max(0, $remaining);
    }

    /**
     * Check if document requires return
     */
    public function requiresReturn(): bool
    {
        return $this->document && $this->document->requires_return;
    }
}