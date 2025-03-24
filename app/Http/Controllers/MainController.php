<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Realty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $ads = Realty::with(['typeRent', 'typeRealty', 'typeRepair', 'owner'])->get();

        // Преобразование данных
        $ads = $ads->map(function ($ad) {
            return [
                'id' => $ad->id,
                'user_id' => $ad->owner->name,
                'type_rent' => $ad->typeRent->title, // Замена id на значение
                'type_realty' => $ad->typeRealty->title, // Замена id на значение
                'address' => $ad->address,
                'price' => $ad->price,
                'count_rooms' => $ad->count_rooms,
                'total_square' => $ad->total_square,
                'living_square' => $ad->living_square,
                'kitchen_square' => $ad->kitchen_square,
                'floor' => $ad->floor,
                'repair_id' => $ad->typeRepair->title,
                'year_construction' => $ad->year_construction,
                'image' => $ad->image,
                'description' => $ad->description,
                'created_at' => $ad->created_at,
                'updated_at' => $ad->updated_at,
            ];
        });

//        $ads = DB::table('ads')
//            ->join('realties', 'ads.realty_id', '=', 'realties.id')
//            ->join('type_rents', 'realties.type_rent_id', '=', 'type_rents.id')
//            ->join('type_realties', 'realties.type_realty_id', '=', 'type_realties.id')
//            ->select('ads.*', 'type_rents.title as rent_type', 'type_realties.title as realty_type')
//            ->get();
//
        return response()->json($ads);
    }

    public function show(Realty $realty)
    {
        // Загружаем отзывы для недвижимости и информацию о пользователе, оставившем отзыв
        $realty->load('feedbacks.user');

        // Вычисляем средний рейтинг недвижимости
        $averageRating = $realty->feedbacks()->avg('rating');

        // Добавляем средний рейтинг в массив данных недвижимости
        $realtyData = $realty->toArray();
        $realtyData['average_rating'] = $averageRating ?? null;

        // Возвращаем JSON-ответ
        return response()->json($realtyData);
    }
}
