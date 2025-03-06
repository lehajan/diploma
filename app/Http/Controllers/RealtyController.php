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
//        $realty = new Realty();
//        $realty->type_rent_id = $request->type_rent_id;
//        $realty->type_realty_id = $request->type_realty_id;
//        $realty->address = $request->address;
//        $realty->price = $request->price;
//        $realty->count_rooms = $request->count_rooms;
//        $realty->total_square = $request->total_square;
//        $realty->living_square = $request->living_square;
//        $realty->kitchen_square = $request->kitchen_square;
//        $realty->floor = $request->floor;
//        $realty->year_construction = $request->year_construction;
//
//        $realty = Realty::create($data);

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
}
