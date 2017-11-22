<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Cancha;
use App\User;
use Validator;
use Exception;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CanchaController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
         $cancha=Cancha::with('usuario','zona')->get();
        return response()->json($cancha);
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
        $vl=$this->validatorRegistroCancha($request->all());
        if ($vl->fails())
        {
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => 'Error en los datos ingresados',
                'error' => 'ERROR_CANCHAS_01',
                'obj' =>$vl->errors(),
                'request'=>$request->all()
            ],Response::HTTP_BAD_REQUEST);
        }else
        {
            try
            {
                $cancha=new Cancha;
                $cancha->fill($request->all());
                $cancha->save();
                //$cancha->attachRole($request->roles_id);
                return response([
                    'status' => Response::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'obj' => $cancha,
                    'msg' => 'Cancha creada con éxito'
                ],Response::HTTP_OK);
            }catch(Exception $e){
                return response([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => 'ERROR_CANCHAS_01',
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
        $cancha=Cancha::findOrFail($id);
        return response()->json($cancha);
    }
    /*DSO 13-10-2016 Funcion para consultar una cancha por zona
    * entran el id zona
    * Sale un arreglo con la informaciòn de la cancha
    */
    public function showCanchaByZoneId($id){
        try
        {
            $cancha=Cancha::with('usuario','zona')->where('zona_id',$id)->where('active',1)->get();
            return response([
                'status' => Response::HTTP_OK,
                'response_time' => microtime(true) - LARAVEL_START,
                'obj' => $cancha,
                'msg' => 'Listado de canchas de la Zona'.$id
            ],Response::HTTP_OK);
        }catch(Exception $e){
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'error' => 'ERROR_USERS_07',
                'msg' => 'No hay ninguna cancha en esta zona.',
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
        $vl=$this->validatorRegistroCancha($request->all());
        if ($vl->fails())
        {
            return response([
                'status' => Response::HTTP_BAD_REQUEST,
                'response_time' => microtime(true) - LARAVEL_START,
                'msg' => 'Error en los campos ingresados',
                'error' => 'ERROR_CANCHAS_02',
                'obj' =>$vl->errors(),
                'request'=>$request->all()
            ],Response::HTTP_BAD_REQUEST);
        }else
        {
            try
            {
                $cancha=Cancha::findOrFail($id);
                $cancha->fill($request->all());
                $cancha->save();
                return response([
                    'status' => Response::HTTP_OK,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'obj' => $cancha,
                    'msg' => 'Datos actualizados correctamente.'
                ],Response::HTTP_OK);
            }catch(Exception $e){
                return response([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'response_time' => microtime(true) - LARAVEL_START,
                    'error' => 'ERROR_CANCHAS_02',
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
        $cancha=Cancha::findOrFail($id);
        $cancha->active=0;
        $cancha->save();
        return response([
            'status' => Response::HTTP_OK,
            'response_time' => microtime(true) - LARAVEL_START,
            'obj' =>$cancha,
            'msg' => 'Cancha desactivada correctamente.'
        ],Response::HTTP_OK);
    }
    /*DSO 13-10-2016 Funcion para validar los campos al registrar un usuario
    * entra el arreglo de datos
    * Sale un arreglo con los errores.
    */
    protected function validatorRegistroCancha(array $data)
    {
        return Validator::make($data, [
            'nombre' => 'required|min:3|max:255',
            'direccion' => 'required|min:3|max:255',
            'telefono' => 'numeric|min:7',
            'celular' => 'required|numeric|min:10',
        ]);
    }
}
