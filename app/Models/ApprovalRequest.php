<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'subtype',
        'description',
        'current_data',
        'requested_data',
        'faculty_comment',
        'admin_notes',
        'status',
    ];

    protected $casts = [
        'current_data' => 'array',
        'requested_data' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
