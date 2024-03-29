<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestadoption extends Model
{
    use HasFactory;
    protected $table='request';

    public function shelter(){
        return $this->belongsTo(AnimalShelter::class,'shelter_id');
    }
    public function petowner(){
        return $this->belongsTo(PetOwner::class,'petowner_id');
    }
    public function animal(){
        return $this->belongsTo(Animals::class,'animal_id');
    }
    public function slip(){
        return $this->hasMany(Animals::class,'reqadoption_id');
    }
}
