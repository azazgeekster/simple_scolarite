<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeImportError extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'admin_id',
        'import_type',
        'filename',
        'total_rows',
        'processed_rows',
        'successful_rows',
        'error_rows',
        'academic_year',
        'session',
        'exam_period_id',
        'row_number',
        'apogee',
        'module_code',
        'error_type',
        'error_message',
        'row_data',
        'status',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'row_data' => 'array',
        'total_rows' => 'integer',
        'processed_rows' => 'integer',
        'successful_rows' => 'integer',
        'error_rows' => 'integer',
        'row_number' => 'integer',
        'academic_year' => 'integer',
        'admin_id' => 'integer',
        'exam_period_id' => 'integer',
        'resolved_by' => 'integer',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function examPeriod()
    {
        return $this->belongsTo(ExamPeriod::class, 'exam_period_id');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(Admin::class, 'resolved_by');
    }

    // Scopes
    public function scopeForBatch($query, string $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helper Methods
    public function markAsResolved($adminId = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $adminId ?? auth('admin')->id(),
        ]);
    }

    public static function createBatchSummary(string $batchId): array
    {
        $errors = static::forBatch($batchId)->get();

        return [
            'batch_id' => $batchId,
            'total_errors' => $errors->count(),
            'by_type' => $errors->groupBy('error_type')->map->count(),
            'failed' => $errors->where('status', 'failed')->count(),
            'resolved' => $errors->where('status', 'resolved')->count(),
        ];
    }
}
