<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use App\Models\Equipment;
//use App\Models\BookingEquipment;
//use App\Models\Participant;
use Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
    }

    public function create()
    {
        $rooms = MeetingRoom::all(); // Get all meeting rooms
        $equipment = Equipment::all();
        $users = User::all();   // Get all meeting rooms
        $bookings = Booking::with('meetingRoom', 'user')->get();
        return view('bookings.create', compact('rooms', 'equipment', 'users', 'bookings'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'booking_start_date' => 'required|date',
            'booking_end_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:255',
            'equipments' => 'nullable|array',
            'equipments.*' => 'exists:equipment,id',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id'
        ]);

        if (strtotime($validatedData['booking_start_date']) > strtotime($validatedData['booking_end_date'])) {
            return redirect()->back()->withErrors(['booking_start_date' => 'วันที่เริ่มต้นต้องไม่มากกว่าวันที่สิ้นสุด'])->withInput();
        }

        // ตรวจสอบการจองที่ทับซ้อนกัน
        $overlappingBookings = Booking::where('meeting_room_id', $request->meeting_room_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<', $request->start_time)
                            ->where('end_time', '>', $request->end_time);
                    });
            })
            ->where('booking_start_date', '<=', $request->booking_end_date)
            ->where('booking_end_date', '>=', $request->booking_start_date)
            ->exists();

        if ($overlappingBookings) {
            return redirect()->back()->withErrors(['error' => 'ไม่สามารถจองห้องประชุมได้เนื่องจากมีการจองที่ทับซ้อนกัน'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'meeting_room_id' => $request->meeting_room_id,
            'booking_start_date' => $request->booking_start_date,
            'booking_end_date' => $request->booking_end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
            'purpose' => $request->purpose,
        ]);

        if (!empty($validatedData['equipments'])) {
            foreach ($validatedData['equipments'] as $equipmentId) {
                $booking->bookingEquipments()->create([
                    'equipment_id' => $equipmentId,
                    'quantity' => '10',
                ]);
            }
        }

        if (!empty($validatedData['participants'])) {
            foreach ($validatedData['participants'] as $participantId) {
                $booking->participants()->create([
                    'user_id' => $participantId,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('bookings.create')->with('success', 'การจองสำเร็จ');
    }
}