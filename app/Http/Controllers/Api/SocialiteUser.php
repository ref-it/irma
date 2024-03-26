<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialiteUser extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->uid, // not ldap uid, but uuid
            'nickname' => $user->username, // socialite expected claim
            'username' => $user->username, // filled with ldap uid
            'name' => $user->full_name, // cn
            'email' => $user->email,
            'avatar' => null,
            'iban' => null,
            'address' => null,
        ]);
    }
}
