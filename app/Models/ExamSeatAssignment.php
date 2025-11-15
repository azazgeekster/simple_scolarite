<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSeatAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'exam_local_id',
        'seat_number',
        'seat_row',
        'seat_position',
        'is_present',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'student_id' => 'integer',
        'exam_local_id' => 'integer',
        'seat_number' => 'integer',
        'seat_position' => 'integer',
        'is_present' => 'boolean',
    ];

    // Relationships

    /**
     * The exam this assignment belongs to
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * The student assigned to this seat
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * The examination local
     */
    public function examLocal()
    {
        return $this->belongsTo(ExamLocal::class, 'exam_local_id');
    }

    // Scopes

    /**
     * Filter by exam
     */
    public function scopeForExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    /**
     * Filter by local
     */
    public function scopeInLocal($query, $localId)
    {
        return $query->where('exam_local_id', $localId);
    }

    /**
     * Filter by student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Only present students
     */
    public function scopePresent($query)
    {
        return $query->where('is_present', true);
    }

    /**
     * Only absent students
     */
    public function scopeAbsent($query)
    {
        return $query->where('is_present', false);
    }

    /**
     * Order by seat number
     */
    public function scopeOrderBySeat($query)
    {
        return $query->orderBy('seat_number');
    }

    // Accessors & Helper Methods

    /**
     * Get full seat designation (e.g., "A-15" or just "15")
     */
    public function getSeatDesignationAttribute(): string
    {
        if ($this->seat_row) {
            return "{$this->seat_row}-{$this->seat_number}";
        }
        return (string) $this->seat_number;
    }

    /**
     * Get attendance status label
     */
    public function getAttendanceStatusAttribute(): ?string
    {
        if (is_null($this->is_present)) {
            return null;
        }
        return $this->is_present ? 'Présent' : 'Absent';
    }

    /**
     * Mark student as present
     */
    public function markPresent(): void
    {
        $this->update(['is_present' => true]);
    }

    /**
     * Mark student as absent
     */
    public function markAbsent(): void
    {
        $this->update(['is_present' => false]);
    }

    /**
     * Toggle attendance status
     */
    public function toggleAttendance(): void
    {
        $this->update(['is_present' => !$this->is_present]);
    }
}
