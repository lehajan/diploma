<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $fillable = [
        'realty_id'
    ];

    public function realty()
    {
        return $this->belongsTo(Realty::class);
    }
}
