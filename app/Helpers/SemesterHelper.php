<?php

namespace App\Helpers;

class SemesterHelper
{
    /**
     * Get semester label (1 → "S1")
     */
    public static function getLabel(int $semester): string
    {
        return 'S' . $semester;
    }

    /**
     * Get year in program from semester number
     */
    public static function getYearInProgram(int $semester): int
    {
        return (int) ceil($semester / 2);
    }

    /**
     * Get semesters for a specific year
     */
    public static function getSemestersForYear(int $yearInProgram): array
    {
        return match($yearInProgram) {
            1 => [1, 2], // L1
            2 => [3, 4], // L2
            3 => [5, 6], // L3
            4 => [7, 8], // M1
            5 => [9, 10], // M2 (if needed)
            default => [],
        };
    }

    /**
     * Get corresponding semester (LMD correspondence)
     */
    public static function getCorrespondingSemester(int $semester): ?int
    {
        return match($semester) {
            1 => 3, 3 => 1,  // S1 ↔ S3
            2 => 4, 4 => 2,  // S2 ↔ S4
            5 => 7, 7 => 5,  // S5 ↔ S7
            6 => 8, 8 => 6,  // S6 ↔ S8
            default => null,
        };
    }

    /**
     * Check if semester is odd (S1, S3, S5, S7)
     */
    public static function isOdd(int $semester): bool
    {
        return $semester % 2 === 1;
    }

    /**
     * Check if semester is even (S2, S4, S6, S8)
     */
    public static function isEven(int $semester): bool
    {
        return $semester % 2 === 0;
    }

    /**
     * Validate semester number
     */
    public static function isValid(int $semester): bool
    {
        return $semester >= 1 && $semester <= 8;
    }

    /**
     * Get display name in French
     */
    public static function getDisplayName(int $semester): string
    {
        return 'Semestre ' . $semester;
    }

    /**
     * Get display name in Arabic
     */
    public static function getDisplayNameAr(int $semester): string
    {
        return 'الفصل ' . $semester;
    }

    /**
     * Get all valid semester numbers
     */
    public static function all(): array
    {
        return range(1, 8);
    }
}