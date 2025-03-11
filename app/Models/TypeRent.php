<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRent extends Model
{
    use HasFactory;

//    public function realty()
//    {
//        return $this->belongsTo(Realty::class, 'realty_id');
//    }
    public function realty()
    {
        return $this->hasMany(Realty::class, 'type_rent_id');
    }

}
