<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'face_id',
        'name',
        'signature',
        'facial_image',
        'registered_at',
    ];

    protected $casts = [
        'signature' => 'array',
        'registered_at' => 'datetime',
    ];

    protected $appends = ['facial_image_url'];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function getFacialImageUrlAttribute()
    {
        return $this->facial_image
            ? asset('storage/' . $this->facial_image)
            : null;
    }
}
