<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->unique_code)) {
                do {
                    $code = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
                } while (static::where('unique_code', $code)->exists());
                $employee->unique_code = $code;
            }
        });
    }
}
