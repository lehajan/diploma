<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class RegistrationController extends Controller
{
    public function reg(Request $request)
    {
        //валидация данных
        $data = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'patronymic' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'verification_code' => 'required|integer'
        ]);

        $code = Cache::get('verification_code_' . $request->email);

        if ($request->verification_code != $code) {
            return response()->json(['error' => 'Invalid verification code'], 400);
        }

        //создание пользователя
//        User::create($data);
        User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        Cache::forget('verification_code_' . $request->email);

        return response()->json('Register successfully.');
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $verificationCode = rand(1000, 9999);

        // Сохраняем код в кэше на 5 минут
        Cache::put('verification_code_' . $request->email, $verificationCode, now()->addMinutes(5));

        Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json('Verification code sent to your email.');
    }
}
