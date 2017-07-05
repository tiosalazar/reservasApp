<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
     use Notifiable;
     
     
     
    protected $fillable = [
        'id', 'nombre',
    ];
    
    
    public function User()
    {
        return $this->belongsTo('App\User');
    }
    
     public function Departamento()
    {
        return $this->belongsTo('App\Departamento');
    }
   
    
}
