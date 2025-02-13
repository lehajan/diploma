<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function create(Request $request)
    {
        return view('advertisement.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_rent' => 'required|in:посуточная,долгосрочная',
            'type_realty' => 'required|in:Квартира,Комната,Дом, дача,апартаменты',
            'address' => 'required|string',
            'price' => 'required|numeric',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'count_rooms' => 'required|in:студия,1,2,3,4,5,6+,свободная планировка',
            'total_square' => 'required|numeric',
            'living_square' => 'required|numeric',
            'kitchen_square' => 'required|numeric',
            'floor' => 'required|integer',
            'year_construction' => 'required|integer' . date('Y'),
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $advertisement = new Advertisement();
        $advertisement->type_rent = $request->type_rent;
        $advertisement->type_realty = $request->type_realty;
        $advertisement->address = $request->address;
        $advertisement->price = $request->price;
        $advertisement->date_start = $request->date_start;
        $advertisement->date_end = $request->date_end;
        $advertisement->count_rooms = $request->count_rooms;
        $advertisement->total_square = $request->total_square;
        $advertisement->living_square = $request->living_square;
        $advertisement->kitchen_square = $request->kitchen_square;
        $advertisement->floor = $request->floor;
        $advertisement->year_construction = $request->year_construction;

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
        }

        $advertisement->save();

        return response()->json(['massage' => 'Объявление о продаже недвижимости успешно создано!']);
    }
}
