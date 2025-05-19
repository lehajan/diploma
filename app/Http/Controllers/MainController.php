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
        $ads = Realty::with(['typeRent', 'typeRealty', 'typeRepair', 'owner'])
            ->where('is_archived', 0)
            ->latest()
            ->get();

        // Преобразование данных
        $ads = $ads->where('is_archived', 0)
            ->map(function ($ad) {
                $images = json_decode($ad->images, true) ?? [];
                $processedImages = array_map(function($path) {
                    return asset($path);
                }, $images);

                return [
                    'id' => $ad->id,
                    'user_id' => $ad->owner->name,
                    'type_rent' => $ad->typeRent->title,
                    'type_realty' => $ad->typeRealty->title,
                    'address' => $ad->address,
                    'price' => $ad->price,
                    'count_rooms' => $ad->count_rooms,
                    'total_square' => $ad->total_square,
                    'living_square' => $ad->living_square,
                    'kitchen_square' => $ad->kitchen_square,
                    'floor' => $ad->floor,
                    'repair_id' => $ad->typeRepair->title,
                    'year_construction' => $ad->year_construction,
                    'images' => $processedImages,
                    'description' => $ad->description,
                    'created_at' => $ad->created_at,
                    'updated_at' => $ad->updated_at,
                ];
            });

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
