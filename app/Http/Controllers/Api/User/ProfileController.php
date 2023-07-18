<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function userDetails()
    {
        // Get the authenticated user
        $user = Auth::user();
        $user_details = [
            'first_name' => $user->first_name ?? null,
            'last_name'  => $user->last_name ?? null,
            'name'       => $user->name ?? null,
            'email'      => $user->email ?? null,
            'phone'      => $user->phone ?? null,
            'profile_image' => $user->profile_image_url ?? null,
            'is_active'  => $user->is_active,
            'is_block'   => $user->is_block,
        ];
        // Return response
        $responseData = [
            'status' => true,
            'data'   => $user_details,
        ];
        return response()->json($responseData, 200);
    }
}
