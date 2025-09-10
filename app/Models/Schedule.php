<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject',
        'block',
        'time',
        'day',
        'room',
        'instructor_id',
        'assigned_checker_id',
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'assigned_checker_id');
    }
}