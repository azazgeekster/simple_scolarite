<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'building',
        'capacity',
        'type',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function examRoomAllocations()
    {
        return $this->hasMany(ExamRoomAllocation::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_room_allocations')
            ->withPivot('allocated_seats')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Check if local is available for a given date/time
    public function isAvailable($date, $startTime, $endTime, $excludeExamId = null)
    {
        return !$this->exams()
            ->where('exam_date', $date)
            ->when($excludeExamId, function ($q) use ($excludeExamId) {
                $q->where('exams.id', '!=', $excludeExamId);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                // Check for time overlap
                $query->where(function ($q) use ($startTime, $endTime) {
                    // New exam starts during existing exam
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New exam ends during existing exam
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                })->orWhere(function ($q) use ($startTime, $endTime) {
                    // New exam encompasses existing exam
                    $q->where('start_time', '>=', $startTime)
                      ->where('end_time', '<=', $endTime);
                });
            })
            ->exists();
    }
}
