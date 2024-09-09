<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ดึงข้อมูลการประชุมที่ผู้ใช้ล็อกอินเป็นผู้เข้าร่วม (status = pending)
        $pendingMeetings = Participant::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->with('booking.meetingRoom') // ดึงข้อมูลห้องประชุมและการจอง
            ->get();

        return view('home', compact('pendingMeetings'));
    }

    // ฟังก์ชันสำหรับตอบรับการเข้าร่วมประชุม
    public function respondToMeeting(Request $request, $participantId)
    {
        $participant = Participant::findOrFail($participantId);

        // ตรวจสอบว่าเป็นผู้เข้าร่วมที่ล็อคอินเข้ามาหรือไม่
        if ($participant->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ในการตอบรับการประชุมนี้');
        }

        // อัปเดตสถานะตามปุ่มที่คลิก
        if ($request->response == 'approve') {
            $participant->status = 'approved';
        } elseif ($request->response == 'cancel') {
            $participant->status = 'cancel';
        }

        $participant->save();

        return redirect()->route('home')->with('success', 'ตอบรับการประชุมเรียบร้อยแล้ว');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
        return view('adminHome');
    }
}