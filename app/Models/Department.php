<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'current_name',
        'faculty_id',
    ];

    public function faculty(){
        return $this->belongsTo(Faculty::class);
    }

    public function visions()
    {
        return $this->hasMany(DepartmentVision::class);
    }
}
