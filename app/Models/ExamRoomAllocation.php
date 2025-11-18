<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoomAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'local_id',
        'allocated_seats',
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'local_id' => 'integer',
        'allocated_seats' => 'integer',
    ];

    // Relationships
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function local()
    {
        return $this->belongsTo(Local::class);
    }
}
