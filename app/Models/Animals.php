<?php

namespace App\Models;
use App\Models\AllocateVaccine;
use App\Models\AllocateDeworming;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AnimalShelter;

class Animals extends Model
{
    protected $table ='animals';

    public function VaccinatedAnimal()
    {
        $shelter=AnimalShelter::where('id','=',session('LoggedUser'))->first();
        return $this->hasMany(AllocateVaccine::class, 'animal_id')->where('shelterid','=',$shelter->id);
    }
    public function DewormedAnimal()
    {
        $shelter=AnimalShelter::where('id','=',session('LoggedUser'))->first();
        return $this->hasMany(AllocateDeworming::class, 'animal_id')->where('shelterid','=',$shelter->id);
    }
    public function vaccine(){
        return $this->hasMany(AllocateVaccine::class,'animal_id');
    }
    public function deworm(){
        return $this->hasMany(AllocateDeworming::class,'animal_id');
    }
    public function post(){
        return $this->hasMany(Post::class,'animal_id');
    }
    public function postphotos(){
        return $this->hasMany(UploadedPhotos::class,'animal_id');
    }
    public function adoption(){
        return $this->hasMany(Adoption::class,'animal_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'id');
    }
}
    