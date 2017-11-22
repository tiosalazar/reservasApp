<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
     use Notifiable;
     
     
     
    protected $fillable = [
        'id', 'nombre','pais_id',
    ];
    
    
    public function Pais()
    {
        return $this->hasOne('App\Pais');
    }
    
     public function Ciudad()
    {
        return $this->belongsTo('App\Ciudade');
    }
   
}
