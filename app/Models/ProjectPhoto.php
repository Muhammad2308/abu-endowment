<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectPhoto extends Model
{
    protected $fillable = [
        'project_id',
        'body_image',
        'title',
        'description',
    ];

    /**
     * Get the project that owns this photo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->body_image) {
            // Use asset() helper which works better with symlinks and avoids CORS issues
            // This generates a URL like: /storage/projects/photos/filename.jpg
            return asset('storage/' . $this->body_image);
        }
        return null;
    }
}
