<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\Role;
use Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Requests\StoreUsers;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $user= User::all();
        return response()->json($user);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //Validaciòn de las entradas por el metodo POST
        $vl=$this->validatorRegistroUsuario($request->all());
        if ($vl->fails())
        {
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => 'Error al crear Usuario',
                'error' => 'ERROR_USERS_02',
                'obj' =>$vl->errors(),
                'request'=>$request->all()
            ],Response::HTTP_BAD_REQUEST);
        }else
        {
            $user=new User;
            $user->fill($request->all());
            //Se encripta la contraseña del usuario.
            $user->password=bcrypt($request->password);
            try
            {
                $user->api_token=str_random(60);
                $user->save();
                //$user->attachRole($request->roles_id);
                return response([
                    'status' => Response::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'obj' => $user,
                    'msg' => 'Usuario creado con éxito'
                ],Response::HTTP_OK);
            }catch(Exception $e){
                return response([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => 'ERROR_USERS_02',
                    'consola' =>$e->getMessage(),
                    'request' => $request->all()
                ],Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $user= User::findOrFail($id);
        return response()->json($user);
    }
    /*DSO 13-10-2016 Funcion para consultar un usuario por su Email Unico
    * entran el Email del usuario
    * Sale un arreglo con la informaciòn del usuario.
    */
    public function showUserByEmail($email){
        try
        {
            $usr=User::where('email',$email)->first();
            return response([
                'status' => Response::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'obj' => $user,
                'msg' => 'Usuario encontrado con exito.'
            ],Response::HTTP_OK);
        }catch(Exception $e){
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => 'ERROR_USERS_07',
                'msg' => 'Usuario no registra en la base de datos.',
                'consola' =>$e->getMessage()
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //Validaciòn de las entradas por el metodo POST
        $vl=$this->validatorUpdateUsuario($request->all());
        if ($vl->fails())
        {
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => 'Error en los campos ingresados',
                'error' => 'ERROR_USERS_06',
                'obj' =>$vl->errors(),
                'request'=>$request->all()
            ],Response::HTTP_BAD_REQUEST);
        }else
        {
            try
            {
                $user=User::findOrFail($id);
                $user->fill($request->all());
                $user->save();
                return response([
                    'status' => Response::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'obj' => $user,
                    'msg' => 'Datos actualizados correctamente.'
                ],Response::HTTP_OK);
            }catch(Exception $e){
                return response([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => 'ERROR_USERS_06',
                    'consola' =>$e->getMessage(),
                    'request' => $request->all()
                ],Response::HTTP_BAD_REQUEST);
            }
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $user=User::findOrFail($id);
        $user->active=0;
        $user->save();
        return response([
            'status' => Response::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'obj' => $user,
            'msg' => 'Usuario Desactivado correctamente.'
        ],Response::HTTP_OK);
    }

    /*DSO 14-10-2016 Funcion para validar el ingreso de un Usuario
    * entran los datos de acceso, emaail/usuario y contraseña
    * Sale un arreglo con los datos del cliente, para guardar en session
    */
    public function login(Request $request){
        if (Auth::attempt(['email' =>$request->email, 'password' => $request->password, 'active' => 1])){
            $user = Auth::user();
            return response([
                'status' => Response::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg'=>'Usuario Autenticado con Éxito.',
                'obj' => $user
            ],Response::HTTP_OK);
        }
        return response([
            'status' => Response::HTTP_BAD_REQUEST,
            'response_time' => microtime(true) - LARAVEL_START,
            'error' => 'ERROR_USERS_01',
            'msg'=>'Usuario y/o contraseña Incorrectos',
            'request' => $request->all()
        ],Response::HTTP_BAD_REQUEST);

    }
    /*DSO 13-10-2016 Funcion para actualizar las notificaciones de correo y email
    * entran los datos del formulario de actualizaciòn. dos checkbos osea valores 1 y 0
    * Sale un arreglo informando si se actualizo o presento un error.
    */
    public function updateNotificationsPush(Request $request, $id)
    {
        try
        {
            $user=  User::findOrFail($id);
            $user->notifications_push=$request->push_notification;
            /// $user->notifications_email=$request->email_notification;
            return response([
                'status' => Response::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg'=>'Notificacion Actualizada con éxito.',
                'obj' => $user
            ],Response::HTTP_OK);
        }catch(Exception $e){
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => 'ERROR_USERS_05',
                'msg'=>'Usuario no encontrado',
                'consola' =>$e->getMessage(),
                'request' => $request->all()
            ],Response::HTTP_BAD_REQUEST);
        }
    }
    /*DSO 13-10-2016 Funcion para actualizar las notificaciones de correo y email
    * entran los datos del formulario de actualizaciòn. dos checkbos osea valores 1 y 0
    * Sale un arreglo informando si se actualizo o presento un error.
    */
    public function updateNotificationsEmail(Request $request, $id)
    {
        try
        {
            $user=  User::findOrFail($id);
            $user->notifications_email=$request->email_notification;
            return response([
                'status' => Response::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg'=>'Notificacion Actualizada con éxito.',
                'obj' => $user
            ],Response::HTTP_OK);
        }catch(Exception $e){
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => 'ERROR_USERS_05',
                'msg'=>'Usuario no encontrado',
                'consola' =>$e->getMessage(),
                'request' => $request->all()
            ],Response::HTTP_BAD_REQUEST);
        }
    }
    /*DSO 13-10-2016 Funcion para enviar email de olvido conraseña
    * entran el Email del usuario
    * Sale un email con el link
    */
    public function RecoveryPassword(Request $request){
        $this->validate($request, ['email' => 'required|email']);
        try
        {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $user=User::where('email',$request->email)->first();
            $response = $user->sendPasswordResetNotification($user->api_token);
            return response([
                'status' => Response::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg'=>'Se ha enviado un email con el link para restaurar la cuenta.',
            ],Response::HTTP_OK);
        }catch(Exception $e){
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => 'ERROR_USERS_03',
                'msg'=>'El usuario no se encuentra en el sistema.',
                'consola' =>$e->getMessage(),
                'request' => $request->all()
            ],Response::HTTP_BAD_REQUEST);
        }

    }


    /*DSO 14-10-2016
    *Funcion la cual se encarga de cerrar la sección Activa
    */
    public function logout(){
        Auth::logout();
        return response([
            'status' => Response::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'msg'=>'Ha salido con éxito de la aplicación.',
            'obj' => Auth::logout()
        ],Response::HTTP_OK);

    }
    /*DSO 13-10-2016 Funcion para validar los campos al registrar un usuario
    * entra el arreglo de datos
    * Sale un arreglo con los errores.
    */
    protected function validatorRegistroUsuario(array $data)
    {
        return Validator::make($data, [
            'nombres' => 'required|min:3|max:255',
            'apellidos' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
        ]);
    }

    /*DSO 13-10-2016 Funcion para validar los campos al actualizar un usuario
    * entra el arreglo de datos
    * Sale un arreglo con los errores.
    */
    protected function validatorUpdateUsuario(array $data)
    {
        return Validator::make($data, [
            'nombres' => 'required|min:3|max:105',
            'apellidos' => 'required|min:3|max:105',
            'fijo'=>'numeric|min:7',
            'celular'=>'numeric|min:10'
        ]);
    }
}
