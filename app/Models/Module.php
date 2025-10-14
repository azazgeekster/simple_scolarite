<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';

    // Primary key is 'id' (MEDIUMINT auto-increment)
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'code',
        'label',
        'label_ar',
        'filiere_id',
        'year_in_program',
        'semester',
        'cc_percentage',
        'exam_percentage',
        'professor_id',
        'prerequisite_id',
        'registration_year'
    ];

    protected $casts = [
        'filiere_id' => 'integer',
        'year_in_program' => 'integer',
        'cc_percentage' => 'integer',
        'exam_percentage' => 'integer',
        'professor_id' => 'integer',
        'prerequisite_id' => 'integer',
    ];

    // Relationships
    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'filiere_id');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    public function prerequisite()
    {
        return $this->belongsTo(Module::class, 'prerequisite_id');
    }

    public function dependentModules()
    {
        return $this->hasMany(Module::class, 'prerequisite_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentModuleEnrollment::class, 'module_id');
    }

    public function grades()
    {
        return $this->hasMany(ModuleGrade::class, 'module_id');
    }

    // Helper Methods
    public function getFullLabelAttribute(): string
    {
        return sprintf('[%s] %s', $this->code ?? 'N/A', $this->label);
    }

    public function isPrerequisiteFor(Module $module): bool
    {
        return $module->prerequisite_id === $this->id;
    }

    public function getCcWeightAttribute(): float
    {
        return $this->cc_percentage / 100;
    }

    public function getExamWeightAttribute(): float
    {
        return $this->exam_percentage / 100;
    }
}
