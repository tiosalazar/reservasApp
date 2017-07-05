<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cancha extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'direccion','telefono','celular',
    ];
    
   public function Cancha()
        {
              return $this->belongsTo('App\User');
        }  
    
  public function Zona()
    {
        return $this->hasOne('App\Zona');
    }
    
    public function Disponibilidad()
        {
              return $this->hasMany('App\DisponibilidadCancha');
        }    
        
}

