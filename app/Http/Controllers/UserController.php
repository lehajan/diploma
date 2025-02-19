<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use function Laravel\Prompts\password;

class UserController extends Controller
{
    // ф-ция показа профиля, который сейчас авторизован
    public function profile()
    {
        $user = Auth::user();
        return response()->json(['user', $user]);
    }


    // ф-ция удаления своего аккаунта
    public function delete()
    {
        $user = Auth::user();
        $user->delete();
        response()->json('user was deleted');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required | string',
            'surname' => 'required | string',
            'patronymic' => 'required | string',
            'phone' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'required_with:password1',
            'password1' => 'nullable|string|min:8|confirmed',
            'password2' => 'nullable|string',
        ]);

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $phoneUtil->parse($data['phone'], null); // null means auto-detect country

            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                return response()->json(['error' => 'Невалидный номер!'], 400);
            }

            // Format to E.164 (e.g., +14155552671)
            $phone = $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);

        } catch (\libphonenumber\NumberParseException $e) {
            return response()->json(['error' => 'Номер должен начинаться с +7'], 400);
        }

        if ($data['password1']) {
            if ($data['password1'] != $data['password2']) {
                return response()->json(['error' => 'Пароли не совпадают'], 400);
            }
            if (!Hash::check($data['current_password'], $user->password)){
                return response()->json(['error' => 'Неверный текущий пароль'], 400);
            }

            $user->update([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'patronymic' => $data['patronymic'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password1']),
            ]);
        } else {
            $user->update([
               'name' => $data['name'],
               'surname' => $data['surname'],
               'patronymic' => $data['patronymic'],
               'phone' => $data['phone'],
            ]);
        }
        return response()->json(['message' => 'Данные обновлены']);
    }

    public function passwordRecovery()
    {

    }
}
