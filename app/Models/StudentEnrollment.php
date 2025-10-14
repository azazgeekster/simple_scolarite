<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    // StudentEnrollment.php

public function student()
{
    return $this->belongsTo(Student::class);
}

public function module()
{
    return $this->belongsTo(Module::class);
}

public function semester()
{
    return $this->belongsTo(Semester::class);
}

public function academicYear()
{
    return $this->belongsTo(AcademicYear::class);
}

// Optional: backtrace to filiere via student_academic_filiere
public function studentFiliere()
{
    return $this->hasOne(StudentAcademicFiliere::class, 'student_id', 'student_id')
        ->whereColumn('academic_year_id', 'academic_year_id');
}

}
