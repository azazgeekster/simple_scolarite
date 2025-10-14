<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacInfo extends Model
{
    use HasFactory;

    protected $table = 'bac_info';

    protected $fillable = [
        'student_id',
        'type_bac',
        'annee_bac',
        'code_serie_bac',
        'serie_du_bac',
        'moyenne_generale',
        'moyenne_arabe',
        'moyenne_francais',
        'moyenne_deuxieme_langue',
        'moyenne_sciences_physiques',
        'moyenne_des_maths',
        'moyenne_national',
        'deuxieme_langue',
        'academie',
        'province_bac',
        'photo_bac',
        'numero_archivage',
    ];

    protected $casts = [
        'annee_bac' => 'integer',
        'moyenne_generale' => 'decimal:2',
        'moyenne_arabe' => 'decimal:2',
        'moyenne_francais' => 'decimal:2',
        'moyenne_deuxieme_langue' => 'decimal:2',
        'moyenne_sciences_physiques' => 'decimal:2',
        'moyenne_des_maths' => 'decimal:2',
        'moyenne_national' => 'decimal:2',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}