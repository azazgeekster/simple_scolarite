<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $table = 'student_families';

    protected $fillable = [
        'student_id',
        'father_firstname',
        'father_lastname',
        'father_cin',
        'father_birth_date',
        'father_death_date',
        'father_profession',
        'mother_firstname',
        'mother_lastname',
        'mother_cin',
        'mother_birth_date',
        'mother_death_date',
        'mother_profession',
        'spouse_cin',
        'spouse_death_date',
        'handicap_code',
        'handicap_type',
        'handicap_card_number',
    ];

    protected $casts = [
        'father_birth_date' => 'date',
        'father_death_date' => 'date',
        'mother_birth_date' => 'date',
        'mother_death_date' => 'date',
        'spouse_death_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
