<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ฟังก์ชันแสดงข้อเสนอแนะทั้งหมด (ทั้ง Admin และผู้ใช้ทั่วไป)
    public function index()
    {
        $feedbacks = Feedback::all();
        return view('feedback.index', compact('feedbacks'));
    }

    // ฟังก์ชันสร้างข้อเสนอแนะ (สำหรับผู้ใช้ทั่วไป)
    public function create()
    {
        return view('feedback.create');
    }

    // ฟังก์ชันบันทึกข้อเสนอแนะใหม่
    public function store(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('feedback.index')->with('success', 'เพิ่มข้อเสนอแนะเรียบร้อยแล้ว');
    }

    // ฟังก์ชันแก้ไขข้อเสนอแนะของผู้ใช้เอง
    public function edit($id)
    {
        $feedback = Feedback::findOrFail($id);

        if (Auth::id() != $feedback->user_id && !auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อเสนอแนะนี้');
        }

        return view('feedback.edit', compact('feedback'));
    }

    // ฟังก์ชันอัปเดตข้อเสนอแนะ
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        if (Auth::id() != $feedback->user_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์อัปเดตข้อเสนอแนะนี้');
        }

        $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        $feedback->update([
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('feedback.index')->with('success', 'อัปเดตข้อเสนอแนะเรียบร้อยแล้ว');
    }

    // ฟังก์ชันลบข้อเสนอแนะของผู้ใช้เอง
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        if (Auth::id() != $feedback->user_id) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ลบข้อเสนอแนะนี้');
        }

        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', 'ลบข้อเสนอแนะเรียบร้อยแล้ว');
    }
}
