<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'name',
        'other_name',
        'reg_number',
        'lga',
        'nationality',
        'state',
        'address',
        'email',
        'profile_image',
        'phone',
        'entry_year',
        'graduation_year',
        'donor_type',
        'ranking',
        'faculty_id',
        'department_id',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function successfulDonations()
    {
        // Return all donations, regardless of status
        return $this->hasMany(\App\Models\Donation::class);
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return trim("{$this->surname} {$this->name} {$this->other_name}");
    }
}
