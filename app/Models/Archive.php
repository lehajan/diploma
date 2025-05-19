<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'realty_id',
    ];

    public function realty()
    {
        return $this->belongsTo(Realty::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
