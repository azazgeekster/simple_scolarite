<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ExamPeriod extends Model
{
    use HasFactory;

    protected $table = 'exam_periods';

    protected $fillable = [
        'academic_year',
        'season',
        'session_type',
        'start_date',
        'end_date',
        'is_active',
        'auto_publish_exams',
        'label',
        'description',
    ];

    protected $casts = [
        'academic_year' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'auto_publish_exams' => 'boolean',
    ];

    // Relationships
    public function exams()
    {
        return $this->hasMany(Exam::class, 'exam_period_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeForSeason($query, string $season)
    {
        return $query->where('season', $season);
    }

    public function scopeNormalSession($query)
    {
        return $query->where('session_type', 'normal');
    }

    public function scopeRattrapageSession($query)
    {
        return $query->where('session_type', 'rattrapage');
    }

    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    // Helper Methods
    public function isCurrentlyActive(): bool
    {
        $today = now()->toDateString();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    public function isOngoing(): bool
    {
        return $this->isCurrentlyActive();
    }

    public function isUpcoming(): bool
    {
        return now()->toDateString() < $this->start_date;
    }

    public function isEnded(): bool
    {
        return now()->toDateString() > $this->end_date;
    }

    public function hasStarted(): bool
    {
        return now()->toDateString() >= $this->start_date;
    }

    public function hasEnded(): bool
    {
        return now()->toDateString() > $this->end_date;
    }

    public function getDuration(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusLabel(): string
    {
        if ($this->hasEnded()) {
            return 'Terminé';
        }
        if ($this->hasStarted()) {
            return 'En cours';
        }
        return 'À venir';
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->getStatusLabel()) {
            'En cours' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'À venir' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'Terminé' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Bulk publish all exams in this period
     */
    public function publishAllExams(): int
    {
        return $this->exams()
            ->where('is_published', false)
            ->update([
                'is_published' => true,
                'published_at' => now(),
            ]);
    }

    /**
     * Bulk unpublish all exams in this period
     */
    public function unpublishAllExams(): int
    {
        return $this->exams()
            ->where('is_published', true)
            ->update([
                'is_published' => false,
                'published_at' => null,
            ]);
    }

    /**
     * Get count of published vs unpublished exams
     */
    public function getExamStats(): array
    {
        $total = $this->exams()->count();
        $published = $this->exams()->where('is_published', true)->count();

        // Count unique students with exams in this period
        // Join through exam_convocations -> student_module_enrollments to get student_id
        $students = $this->exams()
            ->join('exam_convocations', 'exams.id', '=', 'exam_convocations.exam_id')
            ->join('student_module_enrollments', 'exam_convocations.student_module_enrollment_id', '=', 'student_module_enrollments.id')
            ->distinct('student_module_enrollments.student_id')
            ->count('student_module_enrollments.student_id');

        return [
            'total' => $total,
            'published' => $published,
            'unpublished' => $total - $published,
            'students' => $students,
            'percentage_published' => $total > 0 ? round(($published / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Activate this period (and deactivate others in same year/season/type)
     */
    public function activate(): bool
    {
        // Deactivate other periods for same year/season/type
        self::where('academic_year', $this->academic_year)
            ->where('season', $this->season)
            ->where('session_type', $this->session_type)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        // Activate this one
        $this->update(['is_active' => true]);

        // Auto-publish exams if configured
        if ($this->auto_publish_exams) {
            $this->publishAllExams();
        }

        return true;
    }

    /**
     * Deactivate this period
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        // Auto-generate label if not provided
        static::saving(function ($period) {
            if (empty($period->label)) {
                $sessionLabel = $period->session_type === 'normal' ? 'Session Normale' : 'Session de Rattrapage';
                $seasonLabel = $period->season === 'autumn' ? 'Automne' : 'Printemps';
                $period->label = "{$sessionLabel} - {$seasonLabel} {$period->academic_year}";
            }
        });
    }
}
