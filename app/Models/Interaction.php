<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    protected $table = 'interaction';
    protected $fillable = [
        'action',
        'selected_dog',
        'giver_dog'
    ];

    public function giver(){
        return $this->belongsTo(Dog::class,"giver_id", "id");
    }

    public function selected(){
        return $this->belongsTo(Dog::class, "selected_dog", "id");
    }
}
