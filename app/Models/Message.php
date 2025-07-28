<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'subject',
        'message',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
} 