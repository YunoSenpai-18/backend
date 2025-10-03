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
        'start_time',
        'end_time',
        'day',
        'room_id',
        'instructor_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // keep instructor
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    // checker now comes through room
    public function checker()
    {
        return $this->hasOneThrough(
            User::class,
            Room::class,
            'id',          // FK on rooms
            'id',          // FK on users
            'room_id',     // Local key on schedules
            'checker_id'   // Local key on rooms
        );
    }
}