<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DisponibilidadUsuario extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha', 'hora',
    ];
    
   public function Disponibilidad()
        {
              return $this->belongsTo('App\User');
        }  
        
    public function DisponibilidadUsuarioZona()
        {
            return $this->hasOne('App\Zona');

        }
         
 
}

