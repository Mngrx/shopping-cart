<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    
    public function loginAdmin(): JsonResponse {
        $admin = User::where('email', 'admin@example.com')->first();


        return response()->json(['token' => $admin->createToken('admin_token')->plainTextToken], 200);

    }

    public function loginRegular(): JsonResponse {
        $admin = User::where('email', 'regular@example.com')->first();


        return response()->json(['token' => $admin->createToken('regular_token')->plainTextToken], 200);

    }

}
