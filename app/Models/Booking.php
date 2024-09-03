<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'booking_start_date',
        'booking_end_date',
        'start_time',
        'end_time',
        'status',
        'purpose',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    public function bookingEquipments()
    {
        return $this->hasMany(BookingEquipment::class);
    }

    // public function equipments()
    // {
    //     return $this->belongsToMany(Equipment::class);
    // }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function meetingReport()
    {
        return $this->hasOne(MeetingReport::class);
    }
}
