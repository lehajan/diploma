<?php

namespace App\Http\Controllers;

use App\Mail\TemporaryPassword;
use App\Models\Realty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use function Laravel\Prompts\password;

class UserController extends Controller
{
    // ф-ция показа профиля, который сейчас авторизован
    public function profile()
    {
        $user = Auth::user();
        return response()->json(['user', $user]);
    }

    public function viewApartments()
    {
        $user = Auth::user();

        $apartments = Realty::where('user_id', $user->id)->get();

        // Возвращаем результат в формате JSON
        return response()->json([
            'success' => true,
            'apartments' => $apartments
        ]);
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

    public function sendTemporaryPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $data['email'])->first();

        $tempPassword = Str::random(8);

        $user->password = Hash::make($tempPassword);
        $user->save();

        Mail::to($user->email)->send(new TemporaryPassword($user, $tempPassword));

        return response()->json(['message' => 'Временный пароль отправлен']);
    }
}
