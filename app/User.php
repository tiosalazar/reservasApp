<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Role;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- This is required
//use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
  use Notifiable,CanResetPassword;
  use SoftDeletes, EntrustUserTrait {

      SoftDeletes::restore as sfRestore;
      EntrustUserTrait::restore as euRestore;

  }

  public function restore() {
      $this->sfRestore();
      Cache::tags(Config::get('entrust.role_user_table'))->flush();
  }

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
   'nombres','apellidos','email','celular','genero','ciudad_id','pais_id','notifications_push','notifications_email','active',
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * Send the password reset notification.
   *
   * @param  string  $token
   * @return void
   */
  /*public function sendPasswordResetNotification($token)
  {
      $this->notify(new ResetPasswordNotification($token));
  }*/

  /**
  * Obtiene el Rol que esta asociado a un Usuario
  */
  public function Rol()
  {
    return $this->belongsTo('App\Role');
  }


    public function Cancha()
        {
            return $this->hasMany('App\Cancha');
        }

    public function Disponibilidad()
        {
            return $this->hasMany('App\DisponibilidadUsuario');
        }

    public function Pais()
        {
            return $this->hasOne('App\Pais');
        }

    public function Ciudad()
        {
            return $this->hasOne('App\Ciudade');
        }
}
