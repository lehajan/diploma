<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realty extends Model
{
    use HasFactory;

    public mixed $type_rent_id;
    public mixed $type_realty_id;

    public function realties()
    {
        return $this->hasMany(Realty::class); // Пример для отношения один-ко-многим
    }

    protected $fillable = [
        'user_id',
        'type_rent_id',
        'type_realty_id',
        'address',
        'price',
//        'date_start',
//        'date_end',
        'count_rooms',
        'total_square',
        'living_square',
        'kitchen_square',
        'floor',
        'year_construction',
        'image',
        'description'
    ];
}
