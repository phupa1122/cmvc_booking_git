<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingRoom;
use App\Models\Equipment;
use App\Models\MeetingRoomEquipment;

class MeetingRoomController extends Controller
{
    public function index()
    {
        $rooms = MeetingRoom::all(); // ดึงข้อมูลห้องประชุมพร้อมกับอุปกรณ์
        return view('meeting-rooms.index', compact('rooms'));
    }

    public function create()
    {
        $equipment = Equipment::all();
        return view('meeting-rooms.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'des' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'equipment' => 'nullable|array',
            'equipment.*.id' => 'exists:equipment,id',
            'equipment.*.quantity' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $meetingRoom = MeetingRoom::create($validatedData);

        if ($request->has('equipment')) {
            foreach ($request->equipment as $equipmentData) {
                if (isset($equipmentData['id'])) { // ตรวจสอบว่ามีค่า id อยู่หรือไม่
                    MeetingRoomEquipment::create([
                        'meeting_room_id' => $meetingRoom->id,
                        'equipment_id' => $equipmentData['id'],
                        'quantity' => $equipmentData['quantity'] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('meeting-rooms.index')->with('success', 'ห้องประชุมถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit(MeetingRoom $meetingRoom)
    {
        $equipment = Equipment::all();
        return view('meeting-rooms.edit', compact('meetingRoom', 'equipment'));
    }

    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'des' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'equipment' => 'nullable|array',
            // 'equipment.*.id' => 'exists:equipment,id',
            // 'equipment.*.quantity' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $meetingRoom->update($validatedData);

        // ลบอุปกรณ์เก่าทั้งหมดก่อนแล้วบันทึกใหม่
        $meetingRoom->equipment()->delete();

        if ($request->has('equipment')) {
            foreach ($request->equipment as $equipmentData) {
                // ตรวจสอบว่าคีย์ 'id' และ 'quantity' มีอยู่ในอาร์เรย์ก่อนใช้งาน
                if (isset($equipmentData['id'], $equipmentData['quantity'])) {
                    $meetingRoom->equipment()->create([
                        'equipment_id' => $equipmentData['id'],
                        'quantity' => $equipmentData['quantity']
                    ]);
                }
            }
        }

        return redirect()->route('meeting-rooms.index')->with('success', 'ห้องประชุมถูกอัปเดตเรียบร้อยแล้ว');
    }

    public function destroy(MeetingRoom $meetingRoom)
    {
        $meetingRoom->delete();
        return redirect()->route('meeting-rooms.index')->with('success', 'ห้องประชุมถูกลบเรียบร้อยแล้ว');
    }
}
