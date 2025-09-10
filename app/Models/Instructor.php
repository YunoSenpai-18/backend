<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'instructor_id',
        'department',
        'email',
        'phone',
        'photo',
    ];
}
