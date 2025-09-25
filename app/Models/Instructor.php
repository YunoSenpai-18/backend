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

    /**
     * Automatically append computed attributes when model is serialized to JSON.
     */
    protected $appends = ['photo_url'];

    /**
     * Returns a full URL for the stored photo path (or null if none).
     */
    public function getPhotoUrlAttribute()
    {
        if (! $this->photo) {
            return null;
        }

        // asset('storage/...') expects you've run `php artisan storage:link`
        return asset('storage/' . $this->photo);
    }
}
