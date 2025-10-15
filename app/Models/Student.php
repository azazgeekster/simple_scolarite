<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected $guard_name = 'student';

    // FIXED: Primary key is 'id' not 'cne' (based on migration)
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';

    protected $fillable = [
        'cne',
        'prenom',
        'nom',
        'prenom_ar',
        'nom_ar',
        'email',
        'password',
        'apogee',
        'cin',
        'sexe',
        'tel',
        'tel_urgence',
        'date_naissance',
        'lieu_naissance',
        'lieu_naissance_ar',
        'nationalite',
        'situation_familiale',
        'situation_professionnelle',
        'adresse',
        'adresse_ar',
        'pays',
        'boursier',
        'is_active',
        'activation_token',
        'last_login',
        'email_verified_at',
        'avatar',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'date_naissance' => 'date',
        'is_active' => 'boolean',
        'boursier' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'activation_token',
    ];

    // Accessors
    public function setAvatar(): string
    {
        $gender = $this->sexe === 'M' ? 'boy' : 'girl';

        if ($this->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://avatar.iran.liara.run/public/' . $gender;
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getFullNameArAttribute(): string
    {
        return trim($this->prenom_ar . ' ' . $this->nom_ar);
    }

    // Relationships
    public function bacInfo()
    {
        return $this->hasOne(BacInfo::class, 'student_id', 'id');
    }

    public function family()
    {
        return $this->hasOne(Family::class, 'student_id', 'id');
    }

    public function programEnrollments()
    {
        return $this->hasMany(StudentProgramEnrollment::class, 'student_id', 'id');
    }

    public function semesterEnrollments()
    {
        return $this->hasMany(StudentSemesterEnrollment::class, 'student_id', 'id');
    }

    public function moduleEnrollments()
    {
        return $this->hasMany(StudentModuleEnrollment::class, 'student_id', 'id');
    }

    public function grades()
    {
        return $this->hasMany(ModuleGrade::class, 'student_id', 'id');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'student_id', 'id');
    }

    // Helper Methods
    public function currentProgramEnrollment()
    {
        return $this->programEnrollments()
            ->whereHas('academicYear', function($query) {
                $query->where('is_current', true);
            })
            ->first();
    }

    public function currentFiliere(): ?Filiere
    {
        $enrollment = $this->currentProgramEnrollment();
        return $enrollment?->filiere;
    }
}