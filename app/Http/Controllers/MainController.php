<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $ads = Ads::all();
        $ads->load('realty');
        return $ads;
    }
}
