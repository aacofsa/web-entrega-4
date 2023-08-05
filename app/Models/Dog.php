<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Dog extends Authenticatable implements JWTSubject
{
    use HasFactory;
    
    protected $table = 'dog';
    protected $fillable = [
        'name',
        'description',
        'username',
        'breed',
        'photo',
        'gender'
    ];

    protected $hidden = [
        'password',
    ];
    
    public function interactions(){
        return $this->hasMany(Interaction::class, "giver_dog","id");
    }
    

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }    
}
