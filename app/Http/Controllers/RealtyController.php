<?php
namespace App\Http\Controllers;

use App\Models\Realty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'price' => 'required|numeric|min:1',
            'count_rooms' => 'required|in:студия,1,2,3,4,5,6+,свободная планировка',
            'total_square' => 'required|numeric|min:1',
            'living_square' => 'required|numeric|min:1',
            'kitchen_square' => 'nullable|required|numeric|min:1',
            'floor' => 'required|integer|min:1',
            'repair_id' => 'required|integer',
            'year_construction' => 'required|integer|min:1|max:' . date('Y'),
            'images' => 'required|array|max:10',
            'images.*' => 'image|max:2048',
            'description' => 'nullable|string',
        ]);

        $user_id = Auth::id();
        $data['user_id'] = $user_id;
        $realty = Realty::create($data);

        if($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $filename = uniqid().'.'.$image->extension(); // Генерация уникального имени
                $path = $image->storeAs('public/images', $filename);
                $paths[] = str_replace('public/', 'storage/', $path); // Без экранирования
            }
            $realty->images = json_encode($paths, JSON_UNESCAPED_SLASHES); // Отключаем экранирование слешей
            $realty->save();
        }

        return response()->json(['message' => 'Объявление о продаже недвижимости успешно создано!']);
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

    public function update(Request $request, Realty $realty)
    {
        $user = Auth::user();
        if ($realty->user_id !== $user->id) {
            return response()->json(['Вы не можете редактировать это объявления, т.к. вы не являетесь его владельцем'], 403);
        }

        $data = $request->validate([
            'type_rent_id' => 'nullable|integer',
            'type_realty_id' => 'nullable|integer',
            'address' => 'string',
            'price' => 'nullable|numeric|min:1',
            'count_rooms' => 'nullable|in:студия,1,2,3,4,5,6+,свободная планировка',
            'total_square' => 'nullable|numeric|min:1',
            'living_square' => 'nullable|numeric|min:1',
            'kitchen_square' => 'nullable|numeric|min:1',
            'floor' => 'nullable|integer|min:1',
            'repair_id' => 'nullable|integer',
            'year_construction' => 'nullable|integer|min:1|max:' . date('Y'),
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        // Обновляем существующие изображения
        if ($request->hasFile('images')) {
            // Удаляем старые изображения (если нужно)
             $oldImages = json_decode($realty->images, true);
             foreach ($oldImages as $oldImage) {
                 Storage::delete($oldImage);
             }

            $paths = [];
            foreach ($request->file('images') as $image) {
                $filename = uniqid().'.'.$image->extension(); // Генерация уникального имени
                $path = $image->storeAs('public/images', $filename);
                $paths[] = str_replace('public/', 'storage/', $path); // Без экранирования
            }

            $data['images'] = json_encode($paths, JSON_UNESCAPED_SLASHES);
            } else {
                // Если изображения не обновляются, оставляем старые
                unset($data['images']);
        }

        $realty->update($data);

        return response()->json(['message' => 'Объявление успешно обновлено']);
    }

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
        $propertyTypes = DB::table('type_realties')->get();
        $renovationTypes = DB::table('type_repairs')->get();
        $rentTypes = DB::table('type_rents')->get();

        $query = Realty::with(['typeRent', 'typeRealty', 'typeRepair']);

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
            $query->whereIn('count_rooms', $request->input('count_rooms'));
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
        if ($request->has('repair_id')) {
            $query->where('repair_id', $request->input('repair_id'));
        }

        $realties = $query->get();

        $realties->transform(function ($realty) {
            if ($realty->images) {
                $images = json_decode($realty->images, true);
                if (is_array($images)) {
                    $realty->images = array_map(function ($image) {
                        return asset($image);
                    }, $images);
                }
            }
            return $realty;
        });

        return response()->json([
            'propertyTypes' => $propertyTypes, // Типы недвижимости
            'renovationTypes' => $renovationTypes, // Типы ремонта
            'listings' => $realties, // Отфильтрованные объявления
            'rentTypes' => $rentTypes, //возвращение типа аренды
        ]);
    }
}
