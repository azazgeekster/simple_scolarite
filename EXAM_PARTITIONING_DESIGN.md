# Exam Partitioning System - Professional Design Document

## 1. Overview
This document outlines the complete architecture for an intelligent exam seat allocation system that optimally distributes students across examination halls while minimizing resource waste.

## 2. Database Schema

### 2.1 Tables Created
- **exam_sessions**: Manages exam periods (Normal/Rattrapage sessions)
- **exam_locals**: Defines available examination rooms with capacities
- **exam_schedules**: Links modules to specific exam dates/times within a session
- **exam_schedule_local**: Pivot table managing which locals are assigned to which exams
- **exam_seat_assignments**: Individual student seat assignments

### 2.2 Key Relationships
```
ExamSession (1) → (M) ExamSchedule (M) → (M) ExamLocal
ExamSchedule (1) → (M) ExamSeatAssignment (M) → (1) Student
ExamSeatAssignment (M) → (1) ExamLocal
```

## 3. Seat Allocation Algorithm

### 3.1 Core Principles
1. **Capacity Optimization**: Minimize number of locals used
2. **Waste Minimization**: Don't allocate entire rooms for small remainders
3. **Sequential Assignment**: Maintain ordered seat numbering
4. **Fairness**: Alphabetical or random distribution options

### 3.2 Algorithm Steps

```php
function allocateSeats(ExamSchedule $schedule) {
    // 1. Get all students enrolled in the module
    $students = getEnrolledStudents($schedule->module_id);
    $totalStudents = $students->count();

    // 2. Get available locals sorted by capacity (ascending)
    $locals = ExamLocal::where('is_active', true)
        ->orderBy('capacity', 'asc')
        ->get();

    // 3. Find optimal combination of locals
    $allocation = findOptimalCombination($totalStudents, $locals);

    // 4. Assign seats sequentially
    assignSeatsToStudents($students, $allocation);
}

function findOptimalCombination($totalStudents, $locals) {
    $remaining = $totalStudents;
    $selectedLocals = [];

    // First, try to use larger locals
    foreach ($locals->sortByDesc('capacity') as $local) {
        if ($remaining >= $local->capacity) {
            $selectedLocals[] = [
                'local' => $local,
                'seats' => $local->capacity
            ];
            $remaining -= $local->capacity;
        }
    }

    // Handle remainder with optimal smaller local
    if ($remaining > 0) {
        // Find smallest local that can fit remaining students
        $bestFit = $locals->filter(fn($l) => $l->capacity >= $remaining)
            ->sortBy('capacity')
            ->first();

        if ($bestFit) {
            $selectedLocals[] = [
                'local' => $bestFit,
                'seats' => $remaining
            ];
        } else {
            // Use multiple smaller locals if needed
            foreach ($locals->sortByDesc('capacity') as $local) {
                if ($remaining > 0) {
                    $use = min($remaining, $local->capacity);
                    $selectedLocals[] = [
                        'local' => $local,
                        'seats' => $use
                    ];
                    $remaining -= $use;
                }
            }
        }
    }

    return $selectedLocals;
}
```

### 3.3 Optimization Strategies

#### Strategy 1: Best Fit Decreasing (BFD)
- Sort locals by capacity (descending)
- Use largest locals first
- Find best-fit local for remainder

#### Strategy 2: Bin Packing
- Treat as bin packing problem
- Minimize number of bins (locals) used
- Maximize utilization per local

#### Strategy 3: Threshold-Based
```php
const WASTE_THRESHOLD = 10; // Don't waste local if < 10 seats remain

if ($remainder < WASTE_THRESHOLD) {
    // Add to existing local if space available
    // Otherwise, find smallest fitting local
}
```

## 4. Workflow

### 4.1 Admin Workflow
1. **Create Exam Session**
   - Name, Type (Normale/Rattrapage)
   - Academic Year, Period
   - Date Range

2. **Schedule Exams**
   - Select Session
   - Import CSV/Excel with columns:
     - module_code
     - exam_date
     - start_time
     - end_time
   - Or manually add each exam

3. **Auto-Allocate Seats**
   - System calculates enrolled students per module
   - Runs allocation algorithm
   - Creates seat assignments
   - Generates reports

4. **Review & Adjust**
   - View allocation summary
   - Manually reassign if needed
   - Lock/Publish when ready

5. **Generate Documents**
   - Student convocations (PDF)
   - Local occupation sheets
   - Attendance sheets
   - Excel exports

### 4.2 Student Workflow
1. View their exam schedule
2. See assigned local & seat number
3. Download convocation (PDF)
4. Print or save convocation

## 5. Excel Import Format

### 5.1 Minimal Import (Recommended)
```csv
module_code,exam_date,start_time,end_time
MAT101,2025-01-15,09:00,11:00
PHY201,2025-01-15,14:00,16:00
```

### 5.2 Full Import (Optional)
```csv
module_code,module_label,exam_date,start_time,end_time,expected_students
MAT101,Mathématiques I,2025-01-15,09:00,11:00,150
PHY201,Physique II,2025-01-15,14:00,16:00,95
```

### 5.3 Processing Logic
```php
foreach ($rows as $row) {
    $module = Module::where('code', $row['module_code'])->first();

    ExamSchedule::create([
        'exam_session_id' => $sessionId,
        'module_id' => $module->id,
        'exam_date' => $row['exam_date'],
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time'],
        'expected_students' => $row['expected_students']
            ?? getEnrolledStudentsCount($module->id)
    ]);
}
```

## 6. Seat Numbering System

### 6.1 Options

#### Option A: Sequential per Local
```
Local FS2: Seats 1-90
Local FS3: Seats 1-80
```

#### Option B: Global Sequential
```
Local FS2: Seats 1-90
Local FS3: Seats 91-170
```

#### Option C: Row-Based
```
Local FS2:
  Row A: Seats A1-A15
  Row B: Seats B1-B15
```

**Recommended**: Option B for convocation clarity

### 6.2 Implementation
```php
$globalSeatNumber = 1;

foreach ($allocation as $localAssignment) {
    $local = $localAssignment['local'];
    $seatsToUse = $localAssignment['seats'];

    for ($i = 0; $i < $seatsToUse; $i++) {
        $student = $students[$studentIndex++];

        ExamSeatAssignment::create([
            'exam_schedule_id' => $schedule->id,
            'student_id' => $student->id,
            'exam_local_id' => $local->id,
            'seat_number' => $globalSeatNumber++,
            'seat_row' => calculateRow($i, $local),
            'seat_position' => calculatePosition($i, $local)
        ]);
    }
}
```

## 7. Optimization Examples

### Example 1: Perfect Fit
```
Students: 90
Available: [FS2: 90, FS3: 80, AMPHI8: 200]
Result: Use FS2 only (100% utilization)
```

### Example 2: Remainder Handling
```
Students: 95
Available: [FS2: 90, FS3: 80, FE1: 40]
Traditional: FS2 (90) + FE1 (40) = Waste 35 seats
Optimal: FS2 (90) + FS3 (5) = Waste 75 seats

Decision: Use FE1 if remainder < threshold
Otherwise combine with next local
```

### Example 3: Large Exam
```
Students: 350
Available: [AMPHI8: 200, AMPHI9: 200, FS2: 90]
Result: AMPHI8 (200) + AMPHI9 (150) = 350
```

## 8. Reports & Exports

### 8.1 Student Convocation (PDF)
```
CONVOCATION D'EXAMEN

Étudiant: [NOM Prénom]
CNE: [CNE] | Apogée: [APOGEE]

Session: [Session Name]
Module: [Code] - [Label]

Date: [DD/MM/YYYY]
Heure: [HH:MM] - [HH:MM]

Local: [Local Name]
Numéro de place: [SEAT_NUMBER]

Veuillez vous présenter 15 minutes avant le début de l'épreuve.
```

### 8.2 Local Occupation Sheet (PDF/Excel)
```
LOCAL: FS2 - Salle 2 Bloc F
EXAM: MAT101 - Mathématiques I
DATE: 15/01/2025 | HEURE: 09:00-11:00

Place | CNE | Nom | Prénom | Signature
------|-----|-----|--------|----------
  1   | ... | ... | ...    |
  2   | ... | ... | ...    |
```

### 8.3 Global Session Report
- Total exams scheduled
- Total students
- Locals utilization rate
- Conflicts detection
- Statistics per filiere

## 9. Key Features to Implement

### Phase 1: Core System
- [x] Database schema
- [ ] Models with relationships
- [ ] Seat allocation algorithm
- [ ] Basic CRUD for sessions/schedules

### Phase 2: Admin Interface
- [ ] Session management UI
- [ ] Excel import
- [ ] Manual exam scheduling
- [ ] Auto-allocation button
- [ ] Review & adjustment interface

### Phase 3: Student Interface
- [ ] My exams view
- [ ] Convocation download
- [ ] Calendar integration

### Phase 4: Advanced Features
- [ ] Conflict detection (same student, multiple exams same time)
- [ ] Local availability check
- [ ] Historical data & analytics
- [ ] Attendance tracking
- [ ] Seat swap functionality
- [ ] Disability accommodations

## 10. Technical Considerations

### 10.1 Performance
- Use database transactions for bulk inserts
- Queue allocation job for large sessions
- Cache local availability
- Eager load relationships

### 10.2 Validation
- Prevent double-booking of locals
- Validate time conflicts
- Check student eligibility
- Verify local capacity

### 10.3 Security
- Admin-only access to allocation
- Student can only view own assignments
- Audit trail for changes
- PDF watermarks

### 10.4 Error Handling
- Handle insufficient capacity
- Manage concurrent modifications
- Graceful degradation
- Rollback on failures

## 11. Next Steps

1. Create Eloquent models
2. Implement allocation service class
3. Build admin controllers
4. Create admin UI views
5. Implement Excel import/export
6. Generate PDF convocations
7. Create student views
8. Add comprehensive testing

---

**Status**: Database schema completed ✅
**Next**: Create models and allocation service
