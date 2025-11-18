<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_period_id',
        'module_id',
        'session_type',
        'exam_date',
        'start_time',
        'end_time',
        'academic_year',
        'semester',
        'season',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'exam_period_id' => 'integer',
        'module_id' => 'integer',
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'academic_year' => 'integer',
        // 'supervisor_id' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function examPeriod()
    {
        return $this->belongsTo(ExamPeriod::class, 'exam_period_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'start_year');
    }

    /**
     * Convocations for this exam
     */
    public function convocations()
    {
        return $this->hasMany(ExamConvocation::class);
    }

    /**
     * Student module enrollments convocated to this exam
     */
    public function studentEnrollments()
    {
        return $this->hasManyThrough(
            StudentModuleEnrollment::class,
            ExamConvocation::class,
            'exam_id',
            'id',
            'id',
            'student_module_enrollment_id'
        );
    }

    /**
     * Room allocations for this exam
     */
    public function roomAllocations()
    {
        return $this->hasMany(ExamRoomAllocation::class);
    }

    /**
     * Locals (rooms) assigned to this exam
     */
    public function locals()
    {
        return $this->belongsToMany(Local::class, 'exam_room_allocations')
            ->withPivot('allocated_seats')
            ->withTimestamps();
    }

    /**
     * Get total number of students registered for this exam
     */
    public function getTotalStudentsAttribute()
    {
        return $this->convocations()->count();
    }

    /**
     * Get total allocated seats across all rooms
     */
    public function getTotalAllocatedSeatsAttribute()
    {
        return $this->roomAllocations()->sum('allocated_seats');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', now()->toDateString())
                    ->orderBy('exam_date')
                    ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where('exam_date', '<', now()->toDateString())
                    ->orderBy('exam_date', 'desc');
    }

    public function scopeNormalSession($query)
    {
        return $query->where('session_type', 'normal');
    }

    public function scopeRattrapageSession($query)
    {
        return $query->where('session_type', 'rattrapage');
    }

    public function scopeAutumn($query)
    {
        return $query->where('season', 'autumn');
    }

    public function scopeSpring($query)
    {
        return $query->where('season', 'spring');
    }

    /**
     * Get season label in French
     */
    public function getSeasonLabelAttribute(): string
    {
        return $this->season === 'autumn' ? 'Automne' : 'Printemps';
    }

    /**
     * Get season label in Arabic
     */
    public function getSeasonLabelArAttribute(): string
    {
        return $this->season === 'autumn' ? 'الخريفية' : 'الربيعية';
    }

}
