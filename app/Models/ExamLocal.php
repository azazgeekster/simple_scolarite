<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamLocal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'bloc',
        'capacity',
        'rows',
        'seats_per_row',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'rows' => 'integer',
        'seats_per_row' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships

    /**
     * Exams that use this local
     */
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_local', 'exam_local_id', 'exam_id')
            ->withPivot('allocated_seats', 'seat_start', 'seat_end')
            ->withTimestamps();
    }

    /**
     * Seat assignments in this local
     */
    public function seatAssignments()
    {
        return $this->hasMany(ExamSeatAssignment::class, 'exam_local_id');
    }

    // Scopes

    /**
     * Only active locals
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter by type (salle or amphi)
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Filter by bloc
     */
    public function scopeInBloc($query, $bloc)
    {
        return $query->where('bloc', $bloc);
    }

    /**
     * Order by capacity
     */
    public function scopeOrderByCapacity($query, $direction = 'desc')
    {
        return $query->orderBy('capacity', $direction);
    }

    // Accessors & Helper Methods

    /**
     * Get the full display name (code + name)
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /**
     * Get type label in French
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'salle' ? 'Salle' : 'Amphithéâtre';
    }

    /**
     * Get bloc label in French
     */
    public function getBlocLabelAttribute(): ?string
    {
        return match($this->bloc) {
            'F' => 'Bloc F',
            'E' => 'Bloc E',
            'AMPHI' => 'Amphithéâtre',
            default => null,
        };
    }

    /**
     * Check if local is available for a specific exam
     * (considering date/time conflicts)
     */
    public function isAvailableForExam(Exam $exam): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check for time conflicts with other exams
        $conflicts = $this->exams()
            ->where('exam_date', $exam->exam_date)
            ->where('exams.id', '!=', $exam->id)
            ->where(function($query) use ($exam) {
                // Overlapping time ranges
                $query->where(function($q) use ($exam) {
                    $q->where('start_time', '<', $exam->end_time)
                      ->where('end_time', '>', $exam->start_time);
                });
            })
            ->exists();

        return !$conflicts;
    }

    /**
     * Get available capacity for an exam
     */
    public function getAvailableCapacity(Exam $exam): int
    {
        $allocated = $this->seatAssignments()
            ->where('exam_id', $exam->id)
            ->count();

        return max(0, $this->capacity - $allocated);
    }

    /**
     * Get currently allocated seats for an exam
     */
    public function getAllocatedSeats(Exam $exam): int
    {
        return $this->seatAssignments()
            ->where('exam_id', $exam->id)
            ->count();
    }
}
