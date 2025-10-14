<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $table = 'professors';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'phone',
        'specialization',
        'department_id',
    ];

    protected $casts = [
        'department_id' => 'integer',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * The department this professor BELONGS to
     * ONE professor belongs to ONE department
     */
    public function department()
    {
        return $this->belongsTo(Departement::class, 'department_id');
    }

    /**
     * The department this professor HEADS (if any)
     * ONE professor heads AT MOST ONE department
     * Changed from hasMany to hasOne
     */
    public function headedDepartment()
    {
        return $this->hasOne(Departement::class, 'head_id');
    }

    /**
     * The filiere this professor COORDINATES (if any)
     * ONE professor coordinates AT MOST ONE filiere
     * Changed from hasMany to hasOne
     */
    public function coordinatedFiliere()
    {
        return $this->hasOne(Filiere::class, 'professor_id');
    }

    /**
     * Modules taught by this professor
     * ONE professor can teach MANY modules
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'professor_id');
    }

    /**
     * Module grades given by this professor
     * ONE professor can grade MANY modules
     */
    public function gradedModules()
    {
        return $this->hasMany(ModuleGrade::class, 'graded_by');
    }

    // ==========================================
    // ACCESSOR ATTRIBUTES
    // ==========================================

    public function getFullNameAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getDepartmentNameAttribute(): string
    {
        return $this->department?->label ?? 'Aucun département';
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Check if professor heads a department
     */
    public function isDepartmentHead(): bool
    {
        return $this->headedDepartment()->exists();
    }

    /**
     * Check if professor coordinates a filiere
     */
    public function isFiliereCoordinator(): bool
    {
        return $this->coordinatedFiliere()->exists();
    }

    /**
     * Check if professor heads their own department
     */
    public function headsOwnDepartment(): bool
    {
        if (!$this->isDepartmentHead()) {
            return false;
        }
        return $this->headedDepartment->id === $this->department_id;
    }

    /**
     * Get the department this professor heads (null if none)
     */
    public function getHeadedDepartmentAttribute()
    {
        return $this->headedDepartment()->first();
    }

    /**
     * Get the filiere this professor coordinates (null if none)
     */
    public function getCoordinatedFiliereAttribute()
    {
        return $this->coordinatedFiliere()->first();
    }

    /**
     * Assign as department head
     */
    public function assignAsDepartmentHead(Departement $department): bool
    {
        if ($this->department_id !== $department->id) {
            throw new \Exception("Professor must belong to the department before being assigned as head.");
        }

        if ($this->isDepartmentHead() && $this->headed_department->id !== $department->id) {
            throw new \Exception("Professor already heads another department. Remove from current position first.");
        }

        $department->head_id = $this->id;
        return $department->save();
    }

    /**
     * Remove from department head position
     */
    public function removeFromDepartmentHead(): bool
    {
        if (!$this->isDepartmentHead()) {
            return false;
        }

        $department = $this->headed_department;
        $department->head_id = null;
        return $department->save();
    }

    /**
     * Assign as filiere coordinator
     */
    public function assignAsFiliereCoordinator(Filiere $filiere): bool
    {
        // Check if filiere is in professor's department
        if ($filiere->department_id !== $this->department_id) {
            throw new \Exception("Professor must coordinate a filiere in their own department.");
        }

        if ($this->isFiliereCoordinator() && $this->coordinated_filiere->id !== $filiere->id) {
            throw new \Exception("Professor already coordinates another filiere. Remove from current position first.");
        }

        $filiere->professor_id = $this->id;
        return $filiere->save();
    }

    /**
     * Remove from filiere coordinator position
     */
    public function removeFromFiliereCoordinator(): bool
    {
        if (!$this->isFiliereCoordinator()) {
            return false;
        }

        $filiere = $this->coordinated_filiere;
        $filiere->professor_id = null;
        return $filiere->save();
    }

    /**
     * Get professor statistics
     */
    public function getStatistics(): array
    {
        return [
            'full_name' => $this->full_name,
            'department' => $this->department_name,
            'is_department_head' => $this->isDepartmentHead(),
            'headed_department' => $this->headed_department?->label,
            'is_coordinator' => $this->isFiliereCoordinator(),
            'coordinated_filiere' => $this->coordinated_filiere?->label_fr,
            'modules_count' => $this->modules()->count(),
        ];
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeDepartmentHeads($query)
    {
        return $query->has('headedDepartment');
    }

    public function scopeCoordinators($query)
    {
        return $query->has('coordinatedFiliere');
    }

    public function scopeInDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('prenom', 'LIKE', "%{$search}%")
              ->orWhere('nom', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('specialization', 'LIKE', "%{$search}%");
        });
    }

    public function scopeTeaching($query)
    {
        return $query->has('modules');
    }

    // ==========================================
    // MODEL EVENTS
    // ==========================================

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($professor) {
            // Remove as department head if applicable
            if ($professor->isDepartmentHead()) {
                $professor->removeFromDepartmentHead();
            }

            // Remove as filiere coordinator if applicable
            if ($professor->isFiliereCoordinator()) {
                $professor->removeFromFiliereCoordinator();
            }
        });
    }
}
