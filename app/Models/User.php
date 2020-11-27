<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Image;
use App\Models\Content;
use App\Models\Observation;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,SoftDeletes;



    const USER_ACTIVE='true';
    const USER_INACTIVE='false';

    const USER_ADMIN='Administrador';
    const USER_REGULAR='Regular';
    const USER_SCIENTIFIC='Cientifico';

    protected $with = [
        'image',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'role',
        'state'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function Contents()
    {
        return $this->hasMany(Content::class);
    }

    public function Obsertavions()
    {
        return $this->hasMany(Observation::class);
    }

    public function getRole()
    {
        return $this->role;
    }
    
    //funcion que integra adminlte para recuperar una imagen 
    public function adminlte_image()
    {
        //usamos asset() despues
        return 'https://picsum.photos/300/300';
    }

    //funcion para mostrar el role del usuario en el logout
    public function adminlte_desc()
    {
        return $this->role;
    }

    //funcion para llevar a la vista del perfil de usuario
    public function adminlte_profile_url()
    {
        return 'profile';
    }
}
