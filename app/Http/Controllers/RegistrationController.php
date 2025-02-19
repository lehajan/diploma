<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class RegistrationController extends Controller
{

    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $verificationCode = rand(1000, 9999);

        // Сохраняем код в кэше на 5 минут
        Cache::put('verification_code_' . $request->email, $verificationCode, now()->addMinutes(5));

//        Log::info('Verification code: ' . $verificationCode . ' for email: ' . $request->email);

        Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));

        return response()->json(['message', 'Код отправлен на вашу почту!']);
//        try {
//            Mail::to($request->email)->send(new VerificationCodeMail($verificationCode));
//            Log::info('Email sent successfully to: ' . $request->email);
//            return response()->json('Verification code sent to your email.');
//        } catch (\Exception $e) {
//            Log::error('Error sending email: ' . $e->getMessage());
//            return response()->json('Failed to send verification code.', 500); // Важно вернуть код ошибки 500
//        }
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
            'verification_code' => 'required | integer',
        ]);

        $code = Cache::get('verification_code_' . $request->email);

        if (!$code) {
            return response()->json(['error', 'код верификации истек']);
        }
        if ($request->verification_code != $code) {
            return response()->json(['error', 'Неправильный код']);
        }

        //добавление email в кэш, чтобы знать, что пользователь прошел верификацию
        Cache::put('verified_email_' . $request->email, true, now()->addMinutes(30));

        Cache::forget('verification_code_' . $request->email); //удаление кода из кэша

        return response()->json(['message', 'Код подтвержден!']);
    }
    public function reg(Request $request)
    {
        //валидация данных
        $data = $request->validate([
            'name' => 'required | string | regex:/^[А-Яа-яЁё\s-]+$/u',
            'surname' => 'required | string | regex:/^[А-Яа-яЁё\s-]+$/u',
            'patronymic' => 'required | string | regex:/^[А-Яа-яЁё\s-]+$/u',
            'phone' => 'required | string | regex:/^\+7\d{10}$/',
            'email' => 'required | email | unique:users',
            'password' => 'required | string | min:8'
        ]);

//        $phoneUtil = PhoneNumberUtil::getInstance();
//
//        try {
//            $phoneNumber = $phoneUtil->parse($data['phone'], null); // null означает автоопределение страны
//
//            if (!$phoneUtil -> isValidNumber($phoneNumber)) {
//                return response()->json(['error' => 'Невалидный номер!'], 400);
//            }
//
//            $phone = $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);
//        } catch (\libphonenumber\NumberParseException $e) {
//            return response()->json(['error' => 'Номер должен начинаться с +7'], 400);
//        }
//
//        if (!Cache::has('verified_email_' . $data['email'])) {
//            return response()->json(['error', 'Email не верифицирован']);
//        }

        //создание пользователя
        User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        Cache::forget('verified_email_' . $data['email']);

        return response()->json('Регистрация прошла успешно!');
    }


}
