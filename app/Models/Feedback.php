<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'realty_id',
        'rating',
        'comment',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function realty()
    {
        return $this->belongsTo(Realty::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
