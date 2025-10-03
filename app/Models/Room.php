<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['room_number', 'building_id', 'checker_id'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
