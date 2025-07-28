<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_title',
        'project_description',
        'icon_image',
    ];

    protected $dates = ['deleted_at'];
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

    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amount');
    }
}
