<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'checker_id',
        'message',
        'status',
        'admin_response',
    ];

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
