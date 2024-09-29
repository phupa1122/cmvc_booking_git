<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'des',
        'location',
        'capacity',
        //'equipment',
        'image'

    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function equipment()
    {
        return $this->hasMany(MeetingRoomEquipment::class, 'meeting_room_id');
    }
}
