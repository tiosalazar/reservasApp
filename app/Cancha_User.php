<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cancha_User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha', 'hora','solicitud_aprobada',
    ];
    
    public function Cancha_x_Usuario()
    {
        return $this->hasManyThrough('App\Cancha', 'App\User');
    }
         
 
}

