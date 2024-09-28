<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        // แสดงรายการอุปกรณ์ทั้งหมด
        $equipment = Equipment::all();
        return view('equipment.index', compact('equipment'));
    }

    public function create()
    {
        // ฟอร์มสำหรับเพิ่มอุปกรณ์ใหม่
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        // Validate และบันทึกข้อมูลอุปกรณ์ใหม่
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Equipment::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('equipment.index')->with('success', 'เพิ่มอุปกรณ์เรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        // แก้ไขอุปกรณ์
        $equipment = Equipment::findOrFail($id);
        return view('equipment.edit', compact('equipment'));
    }

    public function update(Request $request, $id)
    {
        // Validate และอัปเดตข้อมูลอุปกรณ์
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $equipment = Equipment::findOrFail($id);
        $equipment->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('equipment.index')->with('success', 'แก้ไขอุปกรณ์เรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        // ลบอุปกรณ์
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'ลบอุปกรณ์เรียบร้อยแล้ว');
    }
}
