<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JustifiedAbsence extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'module_id',
        'academic_year',
        'session',
        'justification_reason',
        'justification_document',
        'justified_at',
        'justified_by',
    ];

    protected $casts = [
        'justified_at' => 'datetime',
        'academic_year' => 'integer',
    ];

    /**
     * Relationships
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function justifiedBy()
    {
        return $this->belongsTo(Admin::class, 'justified_by');
    }

    public function moduleGrade()
    {
        return $this->hasOne(ModuleGrade::class)
            ->where('module_enrollment_id', function($query) {
                $query->select('id')
                    ->from('student_module_enrollments')
                    ->whereColumn('student_id', 'justified_absences.student_id')
                    ->whereColumn('module_id', 'justified_absences.module_id');
            })
            ->whereColumn('session', 'justified_absences.session');
    }
}
