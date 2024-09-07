<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
class UserController extends Controller
{
    // public function searchByPhone(Request $request)
    // {
    //     $phone = $request->get('phone');
    //     $user = User::where('phone', $phone)->first();

    //     if ($user) {
    //         return response()->json(['success' => true, 'user' => $user]);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'User not found']);
    //     }
    // }

    // 
    public function autocomplete(Request $request)
{
    $term = $request->get('term');

    // ดึงชื่อจาก table users ที่ตรงกับการค้นหา
    $users = User::where('name', 'like', '%' . $term . '%')->get();

    // แปลงข้อมูลเพื่อใช้ใน AutoComplete
    $data = [];
    foreach ($users as $user) {
        $data[] = [
            'id' => $user->id,
            'label' => $user->name,  // นี่จะเป็นค่าที่แสดงใน AutoComplete
            'value' => $user->name,  // นี่คือค่าที่จะถูกเติมใน input
        ];
    }

    return response()->json($data);
}
}