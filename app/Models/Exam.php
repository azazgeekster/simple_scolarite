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
}
