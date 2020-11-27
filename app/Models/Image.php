<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'size'
    ];

    //automaticamente laravel ve ha que modelo pertenece siempre y cuando mantengamos la consistencia de los nombres
    public function imageable()
    {
        return $this->morphTo();
    }
}
