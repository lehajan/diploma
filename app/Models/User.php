<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'phone',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function realty()
    {
        return $this->hasMany(Realty::class, 'user_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Realty::class, 'favorites', 'user_id', 'realty_id')->withTimestamps();
    }
}
