<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReclamationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'session',
        'filiere_id',
        'semester',
        'module_id',
        'is_active',
        'starts_at',
        'ends_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // Relationships
    public function academicYearRelation()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year', 'start_year');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSession($query, $session)
    {
        return $query->where('session', $session);
    }

    // Check if reclamations are currently allowed
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    // Get label for this setting
    public function getLabel(): string
    {
        $parts = [];

        if ($this->filiere) {
            $parts[] = $this->filiere->label_fr;
        }

        if ($this->semester) {
            $parts[] = $this->semester;
        }

        if ($this->module) {
            $parts[] = $this->module->code;
        }

        return empty($parts) ? 'Global' : implode(' - ', $parts);
    }

    // Check if reclamations are enabled for a specific module
    public static function isReclamationEnabled($academicYear, $session, $filiereId, $semester, $moduleId): bool
    {
        // Check module-specific setting first
        $moduleSetting = self::where('academic_year', $academicYear)
            ->where('session', $session)
            ->where('module_id', $moduleId)
            ->first();

        if ($moduleSetting) {
            return $moduleSetting->isCurrentlyActive();
        }

        // Check semester-specific setting
        $semesterSetting = self::where('academic_year', $academicYear)
            ->where('session', $session)
            ->where('filiere_id', $filiereId)
            ->where('semester', $semester)
            ->whereNull('module_id')
            ->first();

        if ($semesterSetting) {
            return $semesterSetting->isCurrentlyActive();
        }

        // Check filiere-specific setting
        $filiereSetting = self::where('academic_year', $academicYear)
            ->where('session', $session)
            ->where('filiere_id', $filiereId)
            ->whereNull('semester')
            ->whereNull('module_id')
            ->first();

        if ($filiereSetting) {
            return $filiereSetting->isCurrentlyActive();
        }

        // Check global setting for session
        $globalSetting = self::where('academic_year', $academicYear)
            ->where('session', $session)
            ->whereNull('filiere_id')
            ->whereNull('semester')
            ->whereNull('module_id')
            ->first();

        if ($globalSetting) {
            return $globalSetting->isCurrentlyActive();
        }

        // Default: not enabled
        return false;
    }
}
