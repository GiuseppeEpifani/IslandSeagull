<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\kind;

class Specie extends Model
{
    use HasFactory;

    protected $with = [
        'kinds','images'
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'latitude', 
        'longitude', 
    ];


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function kinds()
    {
        return $this->belongsToMany(Kind::class);
    }

   
}
