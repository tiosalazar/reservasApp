<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class usuario_sistema extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usu_id', 'usu_login', 'usu_clave', 'usu_nombre', 'usu_tid_id', 'usu_identificacion', 'usu_correo', 'usu_telefono', 'usu_celular'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'usu_login', 'remember_token',
    ];
    
    
   
}
