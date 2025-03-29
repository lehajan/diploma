<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
        response()->json('Профиль удален!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required | string | regex:/^[А-Яа-яЁё\s-]+$/u',
            'surname' => 'required | string | regex:/^[А-Яа-яЁё\s-]+$/u',
            'patronymic' => 'required | string | regex:/^[А-Яа-яЁё\s-]+$/u',
            'phone' => 'required | string | regex:/^\+7\d{10}$/',
        ]);

        $user->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'patronymic' => $data['patronymic'],
            'phone' => $data['phone'],
        ]);
        return response()->json(['message' => 'Данные обновлены']);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => 'required',
            'password1' => 'required | string | min:8 | confirmed',
            'password1_confirmation' => 'required | string',
        ]);

        if ($data['password1'] != $data['password1_confirmation']) {
            return response()->json(['error' => 'Пароли не совпадают'], 400);
        }
        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['error' => 'Неверный текущий пароль'], 400);
        }
        $user->update([
            'password' => $data['password1'],
        ]);
        return response()->json(['message' => 'Пароль обновлен']);
    }

}
