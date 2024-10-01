<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    // ฟังก์ชันแสดงรายการผู้ใช้ทั้งหมด
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // ฟังก์ชันแสดงฟอร์มแก้ไขข้อมูลผู้ใช้
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // ฟังก์ชันบันทึกการเปลี่ยนแปลงข้อมูลผู้ใช้
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'is_admin' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'department' => $request->department,
            'phone' => $request->phone,
            'is_admin' => $request->is_admin,
        ]);

        return redirect()->route('users.index')->with('success', 'ข้อมูลผู้ใช้ได้รับการอัปเดตเรียบร้อยแล้ว');
    }

    // ฟังก์ชันลบผู้ใช้
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }
}
