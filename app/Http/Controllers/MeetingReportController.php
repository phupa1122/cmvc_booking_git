<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Models\Booking;
use App\Models\MeetingReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MeetingReportController extends Controller
{
    // แสดงรายการประชุมสำหรับ User และ Admin
    public function index()
    {
        $user = Auth::user();

        // ส่วนของ User
        if (!$user->is_admin) {
            $bookings = Booking::with('meetingRoom')
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('status', 'approved');
                })
                ->get();
        } else {
            // ส่วนของ Admin
            $bookings = Booking::with('meetingRoom')->where('status', 'approved')->get();
        }

        return view('meeting-report.index', compact('bookings', 'user'));
    }
    public function create(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $booking = Booking::with('meetingRoom', 'participants.user')->findOrFail($bookingId);
        return view('meeting-report.create', compact('booking'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'meeting_title' => 'required|string',
            'meeting_number' => 'required|string',
            'first_paragraph' => 'required|string',
            'agenda_count' => 'required|integer|min:1|max:10',
            'agenda_titles' => 'required|array|max:10',
            'agenda_contents' => 'required|array|max:10',
            'sign_count' => 'required|integer|min:1|max:10',
            'sign_names' => 'required|array|max:10',
            'sign_positions' => 'required|array|max:10',
        ]);

        // ดึงข้อมูลการจองจากฐานข้อมูล
        $booking = Booking::with('meetingRoom', 'participants.user')
            ->findOrFail($validated['booking_id']);

        // สร้างไฟล์ PDF
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [storage_path('fonts/')]),
            'fontdata' => $fontData + [
                'sarabun_new' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew-Italic.ttf',
                    'B' => 'THSarabunNew-Bold.ttf',
                ],
            ],
            'default_font' => 'sarabun_new',
        ]);

        // เรนเดอร์ HTML เพื่อเขียนลงไฟล์ PDF โดยส่งทั้ง $validated และ $booking ไปยัง view
        $html = view('meeting-report.pdf', compact('validated', 'booking'))->render();
        $mpdf->WriteHTML($html);

        // สร้างชื่อไฟล์และบันทึกลงใน public/pdf_files
        $fileName = 'meeting_report_' . time() . '.pdf';
        $filePath = public_path('pdf_files/' . $fileName);

        // ตรวจสอบว่ามีโฟลเดอร์ 'pdf_files' อยู่หรือไม่ ถ้าไม่มีก็สร้างขึ้น
        if (!file_exists(public_path('pdf_files'))) {
            mkdir(public_path('pdf_files'), 0755, true);
        }

        // บันทึกไฟล์ PDF
        $mpdf->Output($filePath, 'F'); // 'F' หมายถึงบันทึกไฟล์ในโฟลเดอร์ที่กำหนด

        // บันทึกลงฐานข้อมูล
        MeetingReport::create([
            'user_id' => Auth::id(),
            'booking_id' => $validated['booking_id'],
            'report_content' => $fileName,  // บันทึกชื่อไฟล์ในฐานข้อมูล
        ]);

        return redirect()->route('meeting-report.index')->with('success', 'บันทึกรายงานสำเร็จ');
    }

    //public function view($id)
    //{
        //$report = MeetingReport::where('booking_id', $id)->firstOrFail();
        //return view('meeting-report.view', compact('report'));
    //}

    public function destroy($id)
    {
        $report = MeetingReport::findOrFail($id);

    // ลบไฟล์ pdf จากโฟลเดอร์ public/pdf_files
        $filePath = public_path('pdf_files/' . $report->report_content);
        if (file_exists($filePath)) {
        unlink($filePath);
        }

    // ลบรายงานจากฐานข้อมูล
        $report->delete();

        return redirect()->back()->with('success', 'รายงานถูกลบเรียบร้อยแล้ว');
    }
}
