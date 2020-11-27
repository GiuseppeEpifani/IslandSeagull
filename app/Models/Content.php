<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\User;

class Content extends Model
{
    use HasFactory;

    const CONTENT_ACTIVE='activado';
    const CONTENT_INACTIVE='desactivado';

     //sirve para evitar poner el eager loading en las consultas eloquent, de esta forma es mas limpio
     protected $with = [
        'images',
        'user'
    ];

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 
        'description', 
        'user_id',
        'state', 
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
