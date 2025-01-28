<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function reg(Request $request)
    {
        $data = $request->validate([
            'name' => 'string | required',
            'surname' => 'string | required',
            'patronymic' => 'string | required',
            'phone' => 'string | required',
            'email' => 'string | required',
            'password' => 'string | required',
        ]);
        User::create($data);
        return response()->json('Register successfully');
    }
}
