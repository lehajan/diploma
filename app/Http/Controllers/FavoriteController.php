<?php

namespace App\Http\Controllers;

use App\Models\Realty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function addToFavorite(Realty $realty)
    {
        $user = Auth::user();

        // Проверяем, не превышает ли пользователь лимит избранного
        if ($user->favorites()->count() > 15) {
            return response()->json(['error' => 'Вы достигли максимального количества избранных квартир (15).'], 400);
        }

        // Добавляем квартиру в избранное
        $user->favorites()->attach($realty->id);

        return response()->json(['message' => 'Квартира добавлена в избранное'], 200);
    }

    public function destroy(Realty $realty)
    {
        Auth::user()->favorites()->detach($realty->id);

        return response()->json(['message' => 'Квартира удалена из избранного'], 200);
    }

    public function show(Realty $realty)
    {
        $user = Auth::user();
        $favorites = $user->favorites;

        return response()->json(['favorites' => $favorites], 200);
    }
}
