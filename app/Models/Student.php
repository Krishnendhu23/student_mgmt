<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'admission_no',
        'name',
        'gender',
        'age',
        'mark',
        'result'
    ];

    // constant marks for result calculation
    const PASS_MARK = 40;
    const MIN_MARK  = 0;
    const MAX_MARK  = 100;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            $student->admission_no = self::generateAdmissionNo();
        });
    }

    /**
     * Function to generate admission number
     * @return string format: SXXXX ; eg:S0001
     */
    public static function generateAdmissionNo(): string
    {
        $lastId = self::withTrashed()->max('id') ?? 0;
        $nextId = $lastId + 1;

        return 'S' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /*
    * Calculate result based on mark
    * @param int $mark
    * @return string
    */
    public static function calculateResult(int $mark): string
    {
        if ($mark < self::MIN_MARK || $mark > self::MAX_MARK) {
            throw new \InvalidArgumentException('Invalid mark value');
        }

        return $mark >= self::PASS_MARK ? 'Pass' : 'Fail';
    }
}
