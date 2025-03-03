<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realty extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_rent_id',
        'type_realty_id',
        'address',
        'price',
        'date_start',
        'date_end',
        'count_rooms',
        'total_square',
        'living_square',
        'kitchen_square',
        'floor' => [
            'min' => 1,
            'max' => 25,
        ],
        'year_construction',
        'image',
        'description'
    ];
}
