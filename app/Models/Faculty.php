<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'current_name',
    ];

    public function departments(){
        return $this->hasMany(Department::class);
    }

    public function visions()
    {
        return $this->hasMany(FacultyVision::class);
    }
}
