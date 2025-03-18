<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeRepair extends Model
{
    use HasFactory;

    public function realty()
    {
        return $this->hasMany(Realty::class, 'repair_id');
    }
}
