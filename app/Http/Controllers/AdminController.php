<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Participant;

class AdminController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลการจองที่รอการอนุมัติ (status = pending)
        $pendingBookings = Booking::where('status', 'pending')->with('meetingRoom', 'participants.user')->get();

        return view('adminHome', compact('pendingBookings'));
    }

    // ฟังก์ชันสำหรับอนุมัติหรือยกเลิกการจอง
    public function respondToBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // อัปเดตสถานะตามปุ่มที่คลิก
        if ($request->response == 'approve') {
            $booking->status = 'approved';
        } elseif ($request->response == 'cancel') {
            $booking->status = 'cancel';
        }

        $booking->save();

        return redirect()->route('admin.home')->with('success', 'การตอบรับการจองห้องประชุมเรียบร้อยแล้ว');
    }

    // ฟังก์ชันสำหรับแสดงรายละเอียดการจอง
    // public function bookingDetails($bookingId)
    // {
    //     $booking = Booking::with('meetingRoom', 'participants.user')->findOrFail($bookingId);

    //     return view('bookingDetails', compact('booking'));
    // }

    public function bookingDetailsAjax($bookingId)
    {
    // ดึงข้อมูลการจองและผู้เข้าร่วมประชุม
    $booking = Booking::with('meetingRoom', 'participants.user')->findOrFail($bookingId);

    // ส่งข้อมูลในรูปแบบ JSON
    return response()->json([
        'booking' => $booking,
        'meetingRoom' => $booking->meetingRoom,
        'participants' => $booking->participants->map(function ($participant) {
            return $participant->user->name; // ส่งรายชื่อผู้เข้าร่วม
        }),
    ]);
    }

}
