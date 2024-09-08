<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
class UserController extends Controller
{
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