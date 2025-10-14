<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'label',
        'head_id',
    ];

    protected $casts = [
        'head_id' => 'integer',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * The professor who heads this department
     * ONE department has AT MOST ONE head
     */
    public function head()
    {
        return $this->belongsTo(Professor::class, 'head_id');
    }

    /**
     * All professors in this department
     * ONE department has MANY professors
     */
    public function professors()
    {
        return $this->hasMany(Professor::class, 'department_id');
    }

    /**
     * All filieres in this department
     * ONE department has MANY filieres
     */
    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'department_id');
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    public function hasHead(): bool
    {
        return !is_null($this->head_id);
    }

    public function assignHead(Professor $professor): bool
    {
        if ($professor->department_id !== $this->id) {
            throw new \Exception("Professor must belong to this department before being assigned as head.");
        }

        $this->head_id = $professor->id;
        return $this->save();
    }

    public function removeHead(): bool
    {
        $this->head_id = null;
        return $this->save();
    }

    public function getStatistics(): array
    {
        return [
            'label' => $this->label,
            'has_head' => $this->hasHead(),
            'head_name' => $this->head?->full_name ?? 'Aucun',
            'professors_count' => $this->professors()->count(),
            'filieres_count' => $this->filieres()->count(),
        ];
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeWithHead($query)
    {
        return $query->whereNotNull('head_id');
    }

    public function scopeWithoutHead($query)
    {
        return $query->whereNull('head_id');
    }
}