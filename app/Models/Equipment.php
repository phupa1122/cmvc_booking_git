<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];
    public function booking_equipment()
    {
        return $this->belongsTo(BookingEquipment::class);
    }

    public function meetingRooms()
    {
        return $this->hasMany(MeetingRoomEquipment::class);
    }
}
