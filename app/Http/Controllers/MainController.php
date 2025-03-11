<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $ads = Ads::all();
        $ads->load(['realty']);
        return $ads;

//        $ads = DB::table('ads')
//            ->join('realties', 'ads.realty_id', '=', 'realties.id')
//            ->join('type_rents', 'realties.type_rent_id', '=', 'type_rents.id')
//            ->join('type_realties', 'realties.type_realty_id', '=', 'type_realties.id')
//            ->select('ads.*', 'type_rents.title as rent_type', 'type_realties.title as realty_type')
//            ->get();
//
//        return response()->json($ads);



    }
}
