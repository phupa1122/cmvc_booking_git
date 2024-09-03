<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingRoom;

class MeetingRoomController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::all(); // ดึงข้อมูลห้องประชุมทั้งหมด
        return view('meeting-rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('meeting-rooms.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ]);
        MeetingRoom::create($validatedData);

        return redirect()->route('meeting-rooms.index')->with('success', 'ห้องประชุมถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit(MeetingRoom $meetingRoom)
    {
        return view('meeting-rooms.edit', compact('meetingRoom'));
    }

    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ]);
        $meetingRoom->update($validatedData);

        return redirect()->route('meeting-rooms.index')->with('success', 'ห้องประชุมถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy(MeetingRoom $meetingRoom)
    {
        $meetingRoom->delete();

        return redirect()->route('meeting-rooms.index')->with('success', 'ห้องประชุมถูกลบเรียบร้อยแล้ว');
    }
}
