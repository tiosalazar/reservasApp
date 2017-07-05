<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Ciudade extends Model
{
    use Notifiable;
    
     protected $fillable = [
        'id', 'nombre','departamento_id',
    ];
    
    
    public function User()
    {
        return $this->belongsTo('App\User');
    }
    
     public function Departamento()
    {
        return $this->hasOne('App\Departamento');
    }
    
}
