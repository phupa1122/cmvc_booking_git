<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingReport extends Model
{
    use HasFactory;

    
    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
