<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'code',
        'label_ar',
        'label_fr',
        'label_en',
        'level',
        'department_id',
        'professor_id',
    ];

    protected $casts = [
        'department_id' => 'integer',
        'professor_id' => 'integer',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * The department this filiere belongs to
     * ONE filiere belongs to ONE department
     */
    public function department()
    {
        return $this->belongsTo(Departement::class, 'department_id');
    }

    /**
     * The professor who coordinates this filiere
     * ONE filiere has AT MOST ONE coordinator
     */
    public function coordinator()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    /**
     * All modules in this filiere
     * ONE filiere has MANY modules
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'filiere_id');
    }

    /**
     * All program enrollments in this filiere
     * ONE filiere has MANY enrollments
     */
    public function programEnrollments()
    {
        return $this->hasMany(StudentProgramEnrollment::class, 'filiere_id');
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    public function hasCoordinator(): bool
    {
        return !is_null($this->professor_id);
    }

    public function assignCoordinator(Professor $professor): bool
    {
        if ($professor->department_id !== $this->department_id) {
            throw new \Exception("Coordinator must be from the same department as the filiere.");
        }

        $this->professor_id = $professor->id;
        return $this->save();
    }

    public function removeCoordinator(): bool
    {
        $this->professor_id = null;
        return $this->save();
    }

    public function getDisplayNameAttribute(): string
    {
        return sprintf('%s (%s)', $this->label_fr, strtoupper($this->code));
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeWithCoordinator($query)
    {
        return $query->whereNotNull('professor_id');
    }

    public function scopeInDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}