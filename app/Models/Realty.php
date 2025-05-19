<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realty extends Model
{
    use HasFactory;

    public function typeRent()
    {
        return $this->belongsTo(TypeRent::class, 'type_rent_id');
        // Если внешний ключ в таблице Realty называется type_rent_id
    }

    public function typeRealty()
    {
        return $this->belongsTo(TypeRealty::class, 'type_realty_id');
    }

    public function typeRepair()
    {
        return $this->belongsTo(TypeRepair::class, 'repair_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'realty_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'realty_id', 'user_id')->withTimestamps();
    }

    public function archive()
    {
        return $this->hasOne(Archive::class, 'realty_id');
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
        'repair_id',
        'year_construction',
        'images',
        'description',
        'is_archived'
    ];

//    protected $guarded = [
//        'user_id'
//    ];

    protected $casts = [
        'images' => 'array', // Добавляем это свойство
        'is_archived' => 'boolean'
    ];

    protected $hidden = [
        'type_rent',
        'type_realty',
    ];

//    protected $appends = [
//        'type_rent_id',
//        'type_realty_id',
//    ];
}
