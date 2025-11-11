<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'module_id',
        'session_type',
        'exam_date',
        'start_time',
        'end_time',
        'local',
        'academic_year',
        'semester',
        'season',
        'is_published',
        'published_at',
    ];

    protected $casts = [
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
     * Legacy relationship - kept for backward compatibility during transition
     * Students who are convocated to this exam
     * @deprecated Use convocations() instead
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'exam_student')
            ->withPivot('n_examen', 'local', 'observations')
            ->withTimestamps();
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
