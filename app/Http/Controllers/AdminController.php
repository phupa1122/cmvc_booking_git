<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Models\User;
use App\Models\Participant;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // ฟังก์ชันการดึงข้อมูลการจองที่รอการอนุมัติ (status = pending)
        $pendingBookings = Booking::where('status', 'pending')->with('meetingRoom', 'participants.user')->get();

        // ดึงข้อมูลช่วงเวลาที่ต้องการสำหรับสถิติ
        $year = $request->input('year', Carbon::now()->year);  // ค่าเริ่มต้นคือปีปัจจุบัน
        $startOfYear = Carbon::createFromDate($year, 1, 1);
        $endOfYear = Carbon::createFromDate($year, 12, 31);

        // จำนวนการจองห้องประชุมทั้งหมดในช่วงเวลาที่เลือก
        $monthlyBookings = Booking::whereBetween('booking_start_date', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(booking_start_date) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month');

        // จำนวนการจองตามห้องประชุม
        $roomBookings = MeetingRoom::withCount(['bookings' => function ($query) use ($startOfYear, $endOfYear) {
            $query->whereBetween('booking_start_date', [$startOfYear, $endOfYear]);
        }])->get();

        $maxRoomBooking = $roomBookings->max('bookings_count');
        $minRoomBooking = $roomBookings->min('bookings_count');

        // จำนวนผู้ใช้งานระบบทั้งหมด
        $userCount = User::count();

        // ผู้ใช้ที่จองห้องประชุมมากที่สุด
        $topUsers = Booking::selectRaw('user_id, COUNT(*) as total_bookings')
            ->whereBetween('booking_start_date', [$startOfYear, $endOfYear])
            ->groupBy('user_id')
            ->orderBy('total_bookings', 'desc')
            ->take(5)
            ->with('user')
            ->get();

        // การตอบรับของผู้เข้าร่วมประชุมทั้งหมด (ตอบรับ, ปฏิเสธ, ไม่ตอบรับ)
        $participantStats = Participant::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('adminHome', compact(
            'pendingBookings',
            'monthlyBookings',
            'roomBookings',
            'maxRoomBooking',
            'minRoomBooking',
            'userCount',
            'topUsers',
            'participantStats',
            'year'
        ));
    }

    // ฟังก์ชันสำหรับการตอบสนองต่อการจอง (อนุมัติหรือยกเลิก)
    public function respondToBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($request->response == 'approve') {
            $booking->status = 'approved';
        } elseif ($request->response == 'cancel') {
            $booking->status = 'cancel';
        }

        $booking->save();

        return redirect()->route('admin.home')->with('success', 'การตอบรับการจองห้องประชุมเรียบร้อยแล้ว');
    }

    public function bookingDetailsAjax($bookingId)
    {
        $booking = Booking::with('meetingRoom', 'participants.user')->findOrFail($bookingId);

        return response()->json([
            'booking' => $booking,
            'meetingRoom' => $booking->meetingRoom,
            'participants' => $booking->participants->map(function ($participant) {
                return $participant->user->name;
            }),
        ]);
    }
}
