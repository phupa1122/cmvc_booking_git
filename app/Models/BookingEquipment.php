<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'equipment_id','quantity'];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }
}
