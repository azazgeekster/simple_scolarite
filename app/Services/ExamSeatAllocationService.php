<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamLocal;
use App\Models\ExamSeatAssignment;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class ExamSeatAllocationService
{
    /**
     * Threshold percentage for waste minimization
     * If remaining students < threshold% of local capacity, try to fit elsewhere
     */
    private const WASTE_THRESHOLD_PERCENT = 20;

    /**
     * Allocate seats for a specific exam
     *
     * @param Exam $exam
     * @param array|null $selectedLocalIds Optional array of local IDs to use (null = use all active)
     * @param bool $clearExisting Clear existing allocations before reallocating
     * @return array Statistics about the allocation
     * @throws Exception
     */
    public function allocateSeats(
        Exam $exam,
        ?array $selectedLocalIds = null,
        bool $clearExisting = true
    ): array {
        DB::beginTransaction();

        try {
            // Clear existing allocations if requested
            if ($clearExisting && $exam->hasSeatsAllocated()) {
                $exam->clearSeatAllocations();
            }

            // Get students who need seats
            $students = $this->getStudentsForExam($exam);

            if ($students->isEmpty()) {
                DB::commit();
                return [
                    'success' => true,
                    'students_count' => 0,
                    'locals_used' => 0,
                    'message' => 'No students to allocate',
                ];
            }

            // Get available locals
            $availableLocals = $this->getAvailableLocals($exam, $selectedLocalIds);

            if ($availableLocals->isEmpty()) {
                throw new Exception('No available examination locals found');
            }

            // Perform allocation
            $allocation = $this->performOptimizedAllocation($exam, $students, $availableLocals);

            // Save allocations to database
            $this->saveAllocations($exam, $allocation);

            DB::commit();

            return [
                'success' => true,
                'students_count' => $students->count(),
                'locals_used' => count($allocation['locals_used']),
                'locals_detail' => $allocation['locals_used'],
                'message' => "Successfully allocated {$students->count()} students across " . count($allocation['locals_used']) . " locals",
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get students who need seat allocation for an exam
     */
    private function getStudentsForExam(Exam $exam): Collection
    {
        // Get students enrolled in this module for the exam's semester and academic year
        return Student::whereHas('studentModuleEnrollments', function($query) use ($exam) {
            $query->where('module_id', $exam->module_id)
                  ->where('academic_year', $exam->academic_year);
        })
        ->where('current_semester', $exam->semester)
        ->orderBy('apogee')  // Order by student ID for consistent assignment
        ->get();
    }

    /**
     * Get available examination locals
     */
    private function getAvailableLocals(Exam $exam, ?array $selectedLocalIds = null): Collection
    {
        $query = ExamLocal::active()
            ->where(function($q) use ($exam) {
                // Check availability (no time conflicts)
                $q->whereDoesntHave('exams', function($subQuery) use ($exam) {
                    $subQuery->where('exam_date', $exam->exam_date)
                        ->where('exams.id', '!=', $exam->id)
                        ->where(function($timeQuery) use ($exam) {
                            $timeQuery->where(function($q) use ($exam) {
                                $q->where('start_time', '<', $exam->end_time)
                                  ->where('end_time', '>', $exam->start_time);
                            });
                        });
                });
            });

        // Filter by selected locals if provided
        if ($selectedLocalIds !== null) {
            $query->whereIn('id', $selectedLocalIds);
        }

        // Order by capacity descending (Best Fit Decreasing strategy)
        return $query->orderBy('capacity', 'desc')->get();
    }

    /**
     * Perform optimized seat allocation using Best Fit Decreasing with waste minimization
     *
     * Algorithm:
     * 1. Sort locals by capacity (descending) - Best Fit Decreasing
     * 2. Allocate students to locals sequentially
     * 3. When remainder is small relative to local capacity, try to fit in smaller locals
     * 4. Minimize wasted space
     */
    private function performOptimizedAllocation(
        Exam $exam,
        Collection $students,
        Collection $availableLocals
    ): array {
        $remainingStudents = $students->values();
        $allocations = [];
        $localsUsed = [];
        $currentSeatNumber = 1;

        foreach ($availableLocals as $local) {
            if ($remainingStudents->isEmpty()) {
                break;
            }

            $studentsToAllocate = $remainingStudents->count();
            $localCapacity = $local->capacity;

            // Check if we should use this local
            if ($studentsToAllocate > $localCapacity) {
                // Use full capacity of this local
                $studentsForThisLocal = $remainingStudents->take($localCapacity);
                $remainingStudents = $remainingStudents->slice($localCapacity)->values();
            } else {
                // This local can fit all remaining students
                // But check if it's wasteful (less than threshold% utilization)
                $utilizationPercent = ($studentsToAllocate / $localCapacity) * 100;
                $wasteThreshold = self::WASTE_THRESHOLD_PERCENT;

                // If utilization is low, try to find a better-fitting smaller local
                if ($utilizationPercent < $wasteThreshold) {
                    $betterLocal = $this->findBetterFittingLocal(
                        $studentsToAllocate,
                        $local,
                        $availableLocals,
                        $localsUsed
                    );

                    if ($betterLocal) {
                        // Skip this local and use the better-fitting one instead
                        continue;
                    }
                }

                // Use this local for all remaining students
                $studentsForThisLocal = $remainingStudents;
                $remainingStudents = collect();
            }

            // Create seat assignments for this local
            $seatStart = $currentSeatNumber;
            foreach ($studentsForThisLocal as $index => $student) {
                $seatNumber = $currentSeatNumber++;

                // Calculate row and position if local has row configuration
                $seatRow = null;
                $seatPosition = null;

                if ($local->rows && $local->seats_per_row) {
                    $zeroBasedSeat = $seatNumber - $seatStart;
                    $rowIndex = intdiv($zeroBasedSeat, $local->seats_per_row);
                    $seatPosition = ($zeroBasedSeat % $local->seats_per_row) + 1;
                    $seatRow = chr(65 + $rowIndex); // A, B, C, etc.
                }

                $allocations[] = [
                    'exam_id' => $exam->id,
                    'student_id' => $student->id,
                    'exam_local_id' => $local->id,
                    'seat_number' => $seatNumber,
                    'seat_row' => $seatRow,
                    'seat_position' => $seatPosition,
                    'is_present' => null,
                ];
            }

            $seatEnd = $currentSeatNumber - 1;

            // Track local usage
            $localsUsed[] = [
                'local_id' => $local->id,
                'local_code' => $local->code,
                'local_name' => $local->name,
                'capacity' => $local->capacity,
                'allocated' => $studentsForThisLocal->count(),
                'seat_start' => $seatStart,
                'seat_end' => $seatEnd,
                'utilization' => round(($studentsForThisLocal->count() / $local->capacity) * 100, 2),
            ];

            // Reset seat counter for next local
            $currentSeatNumber = 1;
        }

        if ($remainingStudents->isNotEmpty()) {
            throw new Exception(
                "Insufficient capacity: {$remainingStudents->count()} students could not be allocated. " .
                "Total available capacity is less than required seats."
            );
        }

        return [
            'allocations' => $allocations,
            'locals_used' => $localsUsed,
        ];
    }

    /**
     * Find a better-fitting smaller local for remaining students
     * to avoid wasting a large local's capacity
     */
    private function findBetterFittingLocal(
        int $studentsCount,
        ExamLocal $currentLocal,
        Collection $availableLocals,
        array $usedLocals
    ): ?ExamLocal {
        $usedLocalIds = array_column($usedLocals, 'local_id');

        // Find unused locals with capacity >= studentsCount but < currentLocal capacity
        $betterLocal = $availableLocals
            ->filter(function($local) use ($studentsCount, $currentLocal, $usedLocalIds) {
                return !in_array($local->id, $usedLocalIds)
                    && $local->capacity >= $studentsCount
                    && $local->capacity < $currentLocal->capacity;
            })
            ->sortBy('capacity')  // Smallest first
            ->first();

        return $betterLocal;
    }

    /**
     * Save allocations to database
     */
    private function saveAllocations(Exam $exam, array $allocation): void
    {
        // Insert seat assignments
        ExamSeatAssignment::insert(
            array_map(function($assignment) {
                $assignment['created_at'] = now();
                $assignment['updated_at'] = now();
                return $assignment;
            }, $allocation['allocations'])
        );

        // Attach locals to exam with pivot data
        foreach ($allocation['locals_used'] as $localData) {
            $exam->examLocals()->attach($localData['local_id'], [
                'allocated_seats' => $localData['allocated'],
                'seat_start' => $localData['seat_start'],
                'seat_end' => $localData['seat_end'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get allocation statistics for an exam
     */
    public function getAllocationStats(Exam $exam): array
    {
        if (!$exam->hasSeatsAllocated()) {
            return [
                'allocated' => false,
                'required_seats' => $exam->getRequiredSeatsCount(),
            ];
        }

        $assignments = $exam->seatAssignments()->with('examLocal')->get();
        $localStats = $assignments->groupBy('exam_local_id')->map(function($group) {
            $local = $group->first()->examLocal;
            return [
                'local_code' => $local->code,
                'local_name' => $local->name,
                'capacity' => $local->capacity,
                'allocated' => $group->count(),
                'utilization' => round(($group->count() / $local->capacity) * 100, 2),
            ];
        })->values();

        return [
            'allocated' => true,
            'required_seats' => $exam->getRequiredSeatsCount(),
            'allocated_seats' => $exam->getAllocatedSeatsCount(),
            'locals_used' => $localStats->count(),
            'locals_detail' => $localStats,
            'progress' => $exam->getAllocationProgress(),
        ];
    }
}
