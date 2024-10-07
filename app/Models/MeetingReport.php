<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'report_content',
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
