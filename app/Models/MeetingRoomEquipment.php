<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomEquipment extends Model
{
    use HasFactory;

    protected $table = 'meeting_room_equipment';

    protected $fillable = [
        'meeting_room_id',
        'equipment_id',
        'quantity'
    ];
     // ความสัมพันธ์กับ MeetingRoom
     public function meetingRoom()
     {
         return $this->belongsTo(MeetingRoom::class);
     }
 
     // ความสัมพันธ์กับ Equipment
     public function equipment()
     {
         return $this->belongsTo(Equipment::class);
     }
}
