<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Zona extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nombre',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
 
    
  public function User()
    {
        return $this->belongsTo('App\User');
    }
   public function Zona()
    {
        return $this->belongsTo('App\Cancha');
    }  
    
    public function DisponibilidadUsuarioZona()
        {
          return $this->belongsTo('App\DisponibilidadUsuario');

        }
     public function DisponibilidadCanchaZona()
        {
            return $this->belongsTo('App\DisponibilidadCancha');

        }    
        
}

