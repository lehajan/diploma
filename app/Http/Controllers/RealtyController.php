<?php

namespace App\Http\Controllers;

use App\Models\Realty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtyController extends Controller
{
    public function create(Request $request)
    {
        return view('advertisement.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type_rent_id' => 'required|integer',
            'type_realty_id' => 'required|integer',
            'address' => 'required|string',
            'price' => 'required|numeric',
            'count_rooms' => 'required|in:студия,1,2,3,4,5,6+,свободная планировка',
            'total_square' => 'required|numeric',
            'living_square' => 'required|numeric',
            'kitchen_square' => 'required|numeric',
            'floor' => 'required|integer',
            'year_construction' => 'required|integer|max:' . date('Y'),
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $user_id = Auth::id();
        $data['user_id'] = $user_id;
        $realty = Realty::create($data);

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $realty->image = $path;
            $realty->save();
        }



        return response()->json(['massage' => 'Объявление о продаже недвижимости успешно создано!']);
    }

    public function delete(Realty $realty)
    {
        $user = Auth::user();
        if($realty->user_id == $user->id){
            $realty->delete();
            return response()->json(['Квартира удалена']);
        }else{
            return response()->json(['Вы не можете удалить эту квартиру']);
        }

    }

//    public function preview()
//    {
//        $apartments = Realty::all();
//        $apartments->load(['typeRent', 'typeRealty']);
//        foreach ($apartments as $apartment) {
//            echo $apartment->typeRent->title; // Название типа аренды
//            echo $apartment->typeRealty->title; // Название типа недвижимост
//        }
//        return $apartments;
//    }
    public function preview()
    {
        $apartments = Realty::all();

        $result = [];
        foreach ($apartments as $apartment) {
            $apartmentData = $apartment->toArray(); // Преобразуем объект в массив
            $apartmentData['rent_type'] = $apartment->typeRent ? $apartment->typeRent->title : 'Не указано';
            $apartmentData['realty_type'] = $apartment->typeRealty ? $apartment->typeRealty->title : 'Не указано';
            unset($apartmentData['type_rent_id'], $apartmentData['type_realty_id']); // Удаляем ненужные id

            $result[] = $apartmentData;
        }

        return response()->json($result);
    }

    public function filter(Request $request)
    {
        $query = Realty::query();

        // Тип аренды
        if ($request->has('type_rent_id')) {
            $query->where('type_rent_id', $request->input('type_rent_id'));
        }

        // Тип недвижимости
        if ($request->has('type_realty_id')) {
            $query->where('type_realty_id', $request->input('type_realty_id'));
        }

        // Цена
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Количество комнат
        if ($request->has('count_rooms')) {
            $query->where('count_rooms', $request->input('count_rooms'));
        }

        // Общая площадь
        if ($request->has('total_square_min')) {
            $query->where('total_square', '>=', $request->input('total_square_min'));
        }
        if ($request->has('total_square_max')) {
            $query->where('total_square', '<=', $request->input('total_square_max'));
        }

        // Жилая площадь
        if ($request->has('living_square_min')) {
            $query->where('living_square', '>=', $request->input('living_square_min'));
        }
        if ($request->has('living_square_max')) {
            $query->where('living_square', '<=', $request->input('living_square_max'));
        }

        // Этаж
        if ($request->has('floor_min')) {
            $query->where('floor', '>=', $request->input('floor_min'));
        }
        if ($request->has('floor_max')) {
            $query->where('floor', '<=', $request->input('floor_max'));
        }

        // Ремонт
        if ($request->has('repair_type')) {
            $query->where('repair_type', $request->input('repair_type'));
        }

        $realties = $query->get();

        return response()->json($realties);
    }
}
