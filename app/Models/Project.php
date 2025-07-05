<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'project_title',
        'project_description',
        'icon_image',
    ];

    /**
     * Get the photos for this project
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ProjectPhoto::class);
    }

    /**
     * Get the icon image URL
     */
    public function getIconImageUrlAttribute()
    {
        if ($this->icon_image) {
            return asset('storage/' . $this->icon_image);
        }
        return null;
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
