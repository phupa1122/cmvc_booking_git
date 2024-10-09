<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use App\Models\Equipment;
use App\Models\BookingEquipment;
use App\Models\Participant;
use Carbon\Carbon;
use App\Mail\MeetingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

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
        //dd($request->all());
        $validatedData = $request->validate([
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'booking_start_date' => 'required|date',
            'booking_end_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:255',
            'equipments' => 'nullable|array',
            'equipments.*.id' => 'exists:equipment,id',
            'equipments.*.quantity' => 'nullable|integer|min:0',
            'participants' => 'nullable|array',
            'participants.*.id' => 'exists:users,id'
        ]);

        /*if (strtotime($validatedData['booking_start_date']) > strtotime($validatedData['booking_end_date'])) {
            return redirect()->back()->withErrors(['booking_start_date' => 'วันที่เริ่มต้นต้องไม่มากกว่าวันที่สิ้นสุด'])->withInput();
        }*/

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
            ->where('booking_start_date', $request->booking_start_date)
            //->where('booking_end_date', '>=', $request->booking_start_date)
            ->exists();

        if ($overlappingBookings) {
            return redirect()->back()->withErrors(['error' => 'ไม่สามารถจองห้องประชุมได้เนื่องจากมีการจองที่ทับซ้อนกัน'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'meeting_room_id' => $request->meeting_room_id,
            'booking_start_date' => $request->booking_start_date,
            'booking_end_date' => today(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
            'purpose' => $request->purpose,
        ]);

        if (!empty($validatedData['equipments'])) {
            foreach ($validatedData['equipments'] as $equipmentId => $equipmentData) {

                $quantity = $request->input("equipment.$equipmentId.quantity") ?? 0;

                $booking->bookingEquipments()->create([
                    'equipment_id' => $equipmentId,
                    'quantity' => $quantity,
                ]);
            }
        }

        if (!empty($validatedData['participants'])) {
            foreach ($validatedData['participants'] as $participant) {
                if (isset($participant['id'])) { // ตรวจสอบว่ามีค่า id อยู่หรือไม่
                    $booking->participants()->create([
                        'user_id' => $participant['id'],
                        'status' => 'pending',
                    ]);
                }
            }
        }

        foreach ($booking->participants as $participant) {
            Mail::to($participant->user->email)->send(new MeetingNotification($booking, $participant));
        }

        return redirect()->route('bookings.create')->with('success', 'การจองสำเร็จและส่งการแจ้งเตือนแล้ว');
    }

    public function checkAvailability(Request $request)
    {
        // ตรวจสอบการจองที่ซ้อนทับกันในช่วงวันที่และเวลาที่เลือก
        $conflictingBookings = Booking::where('meeting_room_id', $request->meeting_room_id)
            ->where(function ($query) use ($request) {
                $query->where('booking_start_date', $request->booking_start_date)
                    ->where(function ($query) use ($request) {
                        $query->where('start_time', '<=', $request->end_time)
                            ->where('end_time', '>=', $request->start_time);
                    });
            })
            ->whereIn('status', ['approved', 'pending'])
            ->exists();

        // ส่งผลลัพธ์กลับไปยัง front-end
        if ($conflictingBookings) {
            return response()->json(['status' => 'unavailable']);
        } else {
            return response()->json(['status' => 'available']);
        }
    }

    public function getAvailableUsers(Request $request)
    {
        $bookingStartDate = $request->booking_start_date;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // ดึงผู้ใช้ทั้งหมด
        $users = User::where('is_admin', 0)->get();

        // ดึงผู้ใช้ที่มีสถานะ approved หรือ pending ในการประชุมช่วงเวลานั้น
        $bookedUsers = Participant::whereIn('status', ['approved', 'pending'])
            ->whereHas('booking', function ($query) use ($bookingStartDate, $startTime, $endTime) {
                $query->where('booking_start_date', $bookingStartDate)
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->where(function ($query) use ($startTime, $endTime) {
                            $query->where('start_time', '<=', $endTime)
                                ->where('end_time', '>=', $startTime);
                        });
                    });
            })
            ->with('user', 'booking.meetingRoom')
            ->get()
            ->pluck('user');

        // ข้อมูลผู้ใช้ที่ถูกจองในสถานะ approved หรือ pending
        $bookedUserDetails = $bookedUsers->map(function ($user) {
            $participant = $user->participants()->latest()->first();
            return [
                'id' => $user->id,
                'name' => $user->name,
                'meeting_room' => $participant->booking->meetingRoom->name,
                'start_time' => $participant->booking->start_time,
                'end_time' => $participant->booking->end_time
            ];
        });

        // ส่งคืนผู้ใช้ที่สามารถเพิ่มได้ (ไม่ถูกจองในสถานะ approved หรือ pending) 
        return response()->json([
            'available_users' => $users->diff($bookedUsers), // ผู้ใช้ที่ยังไม่ถูกจอง
            'booked_users' => $bookedUserDetails,           // ผู้ใช้ที่มีการประชุมอยู่ในสถานะ approved หรือ pending
        ]);
    }
    //ฟังก์ชันเพื่อดึงข้อมูลการจองและส่งกลับไปยัง AJAX request
    public function getBookingDetails($id)
    {
        try {
            // ดึงข้อมูลการจองพร้อมกับห้องประชุมและผู้เข้าร่วม
            $booking = Booking::with('meetingRoom', 'participants.user')->findOrFail($id);

            // ตรวจสอบว่ามีข้อมูลห้องประชุมหรือไม่
            if (!$booking->meetingRoom) {
                return response()->json(['error' => 'ไม่มีข้อมูลห้องประชุม'], 404);
            }

            return response()->json([
                'booking' => $booking,
                'meetingRoom' => $booking->meetingRoom,
                'purpose' => $booking->purpose,  // ส่งข้อมูลวัตถุประสงค์กลับไป
                'equipments' => $booking->bookingEquipments->map(function ($bookingEquipment) {
                    return $bookingEquipment->equipment->name;
                }),
                'participants' => $booking->participants->map(function ($participant) {
                    return [
                        'name' => $participant->user->name,
                        'status' => $participant->status
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล'], 500);
        }
    }
    public function myCalendar()
    {
        $bookings = Booking::whereHas('participants', function ($query) {
            $query->where('user_id', auth()->id()) // เงื่อนไขให้ user_id ตรงกับผู้ใช้ที่ล็อกอิน
                ->where('status', 'approved');   // เงื่อนไขให้สถานะเป็น approved
        })->with('meetingRoom')->get(['id', 'booking_start_date', 'start_time', 'end_time', 'meeting_room_id', 'status']);
        //return $bookings;
        return view('bookings.my_calendar', compact('bookings'));
    }
}
