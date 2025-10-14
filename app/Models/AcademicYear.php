<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    // Primary key is 'start_year' (YEAR)
    protected $primaryKey = 'start_year';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'start_year',
        'end_year',
        'is_current',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_current' => 'boolean',
    ];

    // Relationships
    public function programEnrollments()
    {
        return $this->hasMany(StudentProgramEnrollment::class, 'academic_year', 'start_year');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'academic_year', 'start_year');
    }

    // Accessors
    public function getLabelAttribute(): string
    {
        $endYear = $this->end_year ?? ($this->start_year + 1);
        return sprintf('%d-%d', $this->start_year, $endYear);
    }

    // Helper Methods
    public function isCurrent(): bool
    {
        return $this->is_current;
    }

    public static function getCurrentYear(): ?self
    {
        return static::where('is_current', true)->first();
    }
}
