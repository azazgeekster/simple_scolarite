<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'field_name',
        'old_value',
        'new_value',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper Methods
    public function getFieldLabelAttribute(): string
    {
        $labels = [
            'prenom' => 'First Name',
            'nom' => 'Last Name',
            'prenom_ar' => 'First Name (Arabic)',
            'nom_ar' => 'Last Name (Arabic)',
            'cin' => 'CIN',
            'tel' => 'Phone',
            'tel_urgence' => 'Emergency Contact',
            'date_naissance' => 'Date of Birth',
            'lieu_naissance' => 'Place of Birth',
            'lieu_naissance_ar' => 'Place of Birth (Arabic)',
            'nationalite' => 'Nationality',
            'situation_familiale' => 'Marital Status',
            'situation_professionnelle' => 'Professional Status',
            'adresse' => 'Address',
            'adresse_ar' => 'Address (Arabic)',
            'pays' => 'Country',
            'sexe' => 'Gender',
            // Family fields
            'family.father_firstname' => 'Father First Name',
            'family.father_lastname' => 'Father Last Name',
            'family.father_cin' => 'Father CIN',
            'family.father_birth_date' => 'Father Birth Date',
            'family.father_death_date' => 'Father Death Date',
            'family.father_profession' => 'Father Profession',
            'family.mother_firstname' => 'Mother First Name',
            'family.mother_lastname' => 'Mother Last Name',
            'family.mother_cin' => 'Mother CIN',
            'family.mother_birth_date' => 'Mother Birth Date',
            'family.mother_death_date' => 'Mother Death Date',
            'family.mother_profession' => 'Mother Profession',
            'family.spouse_cin' => 'Spouse CIN',
            'family.spouse_death_date' => 'Spouse Death Date',
            'family.handicap_code' => 'Handicap Code',
            'family.handicap_type' => 'Handicap Type',
            'family.handicap_card_number' => 'Handicap Card Number',
        ];

        return $labels[$this->field_name] ?? $this->field_name;
    }
}
