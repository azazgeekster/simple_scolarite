<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'recipient_type',
        'filiere_id',
        'year_in_program',
        'semester',
        'subject',
        'message',
        'priority',
        'category',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(Admin::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Student::class, 'recipient_id');
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    // Scopes
    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('recipient_id', $studentId)
            ->orWhere('recipient_type', 'all');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // Helper Methods
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function isUrgent(): bool
    {
        return $this->priority === 'urgent';
    }

    public function isHigh(): bool
    {
        return $this->priority === 'high';
    }

    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
            'normal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getCategoryBadgeClass(): string
    {
        return match($this->category) {
            'important' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'exam' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'grade' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'administrative' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'general' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getRecipientDescription(): string
    {
        return match($this->recipient_type) {
            'individual' => $this->recipient ? "Étudiant: {$this->recipient->prenom} {$this->recipient->nom}" : 'Étudiant individuel',
            'filiere' => $this->filiere ? "Filière: {$this->filiere->label_fr}" : 'Filière spécifique',
            'year' => "Année {$this->year_in_program}",
            'semester' => "Semestre {$this->semester}",
            'filiere_year' => ($this->filiere ? "{$this->filiere->label_fr} - " : '') . "Année {$this->year_in_program}",
            'filiere_semester' => ($this->filiere ? "{$this->filiere->label_fr} - " : '') . "Semestre {$this->semester}",
            'all' => 'Tous les étudiants',
            default => 'Destinataire inconnu',
        };
    }
}
