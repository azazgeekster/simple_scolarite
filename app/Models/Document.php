<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'slug',
        'description',
        'label_ar',
        'label_fr',
        'label_en',
        'template_path',
        'requires_return'
    ];

    // Relationships
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'document_id');
    }
}
