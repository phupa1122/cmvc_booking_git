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

    public function autocomplete(Request $request): JsonResponse
    {
        $data = User::select("name")
                    ->where('name', 'LIKE', '%'. $request->get('query'). '%')
                    ->get();
         
        return response()->json($data);
    }
}