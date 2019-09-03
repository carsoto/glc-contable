<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Socio;
use App\Charter;
use App\Comisione;
use App\AbonosComisione;
use App\GastosDetalle;
use App\Entrada;
use App\Embarcacion;
use App\TipoGasto;
use App\Gasto;
use App\Historial;
use App\Broker;
use App\Programa;
use App\TiposPatente;
use App\ChartersEmbarcacion;
use Carbon\Carbon;
use DB;
use Redirect;
use File;
use Response;
use Auth;
use Datatables;
use PDF;
use Storage;
use Funciones;

class CharterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('charters.index');
    }

    public function opciones($id){
        $id = decrypt($id);
        $charter = Charter::find($id);
        return view('charters.opciones', ['charter' => $charter]);
    }

    public function dashboard(){
        $charters = Charter::all();
        return Datatables::of($charters)
            ->addColumn('codigo', function ($charters) {
                return $charters->codigo;
            })
            ->addColumn('broker', function ($charters) {
                if($charters->broker != null){
                   return strtoupper($charters->broker->nombre);
                }
            })
            ->addColumn('cliente', function ($charters) {
                return $charters->cliente;
            })
            ->addColumn('yacht', function ($charters) {
                return $charters->yacht;
            })
            ->addColumn('fecha_inicio', function ($charters) {
                return Carbon::parse($charters->fecha_inicio)->format('d-m-Y');
            })
            ->addColumn('fecha_fin', function ($charters) {
                return Carbon::parse($charters->fecha_fin)->format('d-m-Y');
            })
            ->addColumn('programa', function ($charters) {
                if($charters->programa != null){
                    return $charters->programa->desc_programa;
                }
                return "";
            })
            ->addColumn('estatus', function ($charters) {
                return $charters->status;
            })
            ->addColumn('action', function ($charters) {
                return '<a href="opciones-charter/'.encrypt($charters['id']).'"><i class="fa fa-eye fa-fw" title="Detalles"></i></a> <a href="opciones-charter/'.encrypt($charters['id']).'"><i class="fa fa-pencil fa-fw" title="Editar"></i></a><a href="#" onclick="eliminar_charter(\''.encrypt($charters['id']).'\')"><i class="fa fa-trash fa-fw" title="Eliminar"></i></a>';

            })
            ->order(function ($query) {
                if (request()->has('fecha_inicio')) {
                    $query->orderBy('fecha_inicio', 'ASC');
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brokers = Broker::all();
        $programas = Programa::all();
        $tipos_patente = TiposPatente::all();
        return view('charters.crear', ['brokers' => $brokers, 'programas' => $programas, 'tipos_patente' => $tipos_patente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        DB::beginTransaction();
        
        try{
            $descripcion = "";
            $n_fecha_inicio = str_replace("-", "", $request->fecha_inicio);
            $codigo = "CHT-".$n_fecha_inicio;
            $charter_reg = Charter::where('codigo', '=', $codigo)->get();
            $socios = Socio::all();
            $yacht = "";

            if(count($charter_reg) > 0){
                $cod_reg = count($charter_reg);
                $codigo_charter = $codigo."-".($cod_reg+1);  
            }else{
                $codigo_charter = $codigo;
            }

            $f_inicio = Carbon::parse($request->fecha_inicio);
            $f_fin = Carbon::parse($request->fecha_fin);

            $f_init = explode(",", $f_inicio->toFormattedDateString());
            $f_end = explode(",", $f_fin->toFormattedDateString());
            
            $init_f = explode(" ", $f_init[0]);
            $end_f = explode(" ", $f_end[0]);

            if($request->tipo_charter == 3){
                foreach ($request->embarcacion as $key => $value) {
                    $embarcacion_id = $value;
                    $embarcacion = Embarcacion::find($embarcacion_id)->nombre;
                    if($yacht != ""){
                        $yacht = $yacht.'-'.strtoupper($embarcacion);
                    }else{
                        $yacht = strtoupper($embarcacion);
                    }
                }

            }else{
                if(isset($request->embarcacion)){
                    $embarcacion_id = $request->embarcacion[0];
                    $embarcacion = Embarcacion::find($embarcacion_id)->nombre;
                    $yacht = strtoupper($embarcacion);    
                }
            }

            if($init_f[0] == $end_f[0]){
                $descripcion = $f_init[0]." - ".$end_f[1].",".$f_end[1].". (".$yacht.")";
            }else{
                $descripcion = $f_init[0]." - ".$f_end[0].",".$f_end[1].". (".$yacht.")";   
            }
            $directorio_images = 'images/charters/'.$codigo_charter;
            
            if(!File::exists(public_path($directorio_images))) {
                File::makeDirectory(public_path($directorio_images));

                if(!File::exists(public_path($directorio_images.'/contrato'))){
                    File::makeDirectory(public_path($directorio_images.'/contrato'));
                }
            }

            if(count($request->file()) > 0){
                if($request->file()['contrato'] != null){
                    $contrato = Funciones::guardarImagen($request->file()['contrato'], public_path($directorio_images.'/contrato'));    
                }else{
                    $contrato = "Sin contrato";
                }
            }else{
                $contrato = "Sin contrato";
            } 
    
            if(isset($request->nuevo_intermediario["check"])){
                $broker = new Broker();
                $broker->nombre = strtoupper($request->nuevo_intermediario["nombre"]);
                $broker->email = $request->nuevo_intermediario["email"];
                $broker->empresa = $request->nuevo_intermediario["empresa"];
                $broker->telefono = $request->nuevo_intermediario["telefono"];
                $broker->save();
                $broker_id = $broker->id;
            }else{
                $broker_id = $request->broker;
            }

            $ffi = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
            $ff_init = explode("-", $ffi);
            $charter = new Charter();
            $charter->creado_por = Auth::id();
            $charter->codigo = $codigo_charter;
            $charter->descripcion = strtoupper($descripcion);
            $charter->yacht = $yacht;
            $charter->brokers_id = $broker_id;
            $charter->programa_id = $request->programa;
            $charter->cliente = strtoupper($request->cliente);
            $charter->fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
            $charter->fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
            $charter->anyo = $ff_init[0];
            $charter->mes = $ff_init[1];
            $charter->precio_venta = $request->precio_venta;
            $charter->yacht_rack = $request->yacht_rack;
            $charter->neto = $request->neto;
            $charter->porcentaje_comision_broker = $request->porcentaje_comision_broker;
            $charter->comision_broker = $request->comision_broker;
            $charter->costo_deluxe = $request->costo_deluxe;
            $charter->comision_glc = $request->comision_glc;
            $charter->apa = $request->apa;
            $charter->contrato = $contrato;
            $charter->status = $request->status;

            if($charter->save()){
                $count_comision = 0;
                
                if($request->tipo_charter == 3){
                    foreach ($request->embarcacion as $key => $value) {
                        $charter_embarcacion = new ChartersEmbarcacion();
                        $charter_embarcacion->charters_id = $charter->id;
                        
                        if($value != 0){
                            $charter_embarcacion->embarcacion_id = $value;
                        }

                        if($request->itinerario[$key] != 0){
                            $charter_embarcacion->itinerarios_id = $request->itinerario[$key];    
                        }
                        
                        $charter_embarcacion->save();
                    }
                }else{
                    $embarcacion_id = $request->embarcacion[0];
                    $itinerario_id = $request->itinerario[0];

                    $charter_embarcacion = new ChartersEmbarcacion();
                    $charter_embarcacion->charters_id = $charter->id;
                    
                    if($embarcacion_id != 0){
                        $charter_embarcacion->embarcacion_id = $embarcacion_id;    
                    }

                    if($itinerario_id != 0){
                        $charter_embarcacion->itinerarios_id = $itinerario_id;    
                    }
                    
                    $charter_embarcacion->save();
                }

                foreach ($socios as $key => $socio) {
                    $new_comision = new Comisione();
                    $new_comision->users_id = Auth::id();
                    $new_comision->charters_id = $charter->id;
                    $new_comision->socios_id = $socio->id;
                    $new_comision->porcentaje_comision_socio = $socio->porcentaje;
                    
                    if($socio->porcentaje != 0){
                        $comision = ($request->comision_glc * $socio->porcentaje)/100;

                        if($socio->nombre == 'GLC'){
                            $comision = $comision - $count_comision;
                        }

                        $new_comision->monto = $comision;
                        $new_comision->saldo = $comision;
                    }else{
                        foreach ($socio->socios_regla_negocios as $key => $regla) {
                            if($request->precio_venta >= $regla->reglas_negocio->r_inicio){
                                if($regla->reglas_negocio->r_fin != null){
                                    if($request->precio_venta <= $regla->reglas_negocio->r_fin){
                                        $comision = $regla->reglas_negocio->monto;
                                        $new_comision->monto = $comision;
                                        $new_comision->saldo = $comision;
                                        $count_comision = $count_comision + $comision;
                                    }    
                                }else{
                                    $comision = $regla->reglas_negocio->monto;
                                    $new_comision->monto = $comision;
                                    $new_comision->saldo = $comision;
                                    $count_comision = $count_comision + $comision;
                                }
                            }

                        }
                    }

                    $new_comision->save();
                }

                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'CHARTER';
                $new_action->accion = 'ADD';
                $new_action->comentario = 'Charter '.$descripcion.' registrado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save(); 

                $msg = 'Charter registrado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante el registro del charter";
                $status = 'error';
            }  

            DB::commit();

            return Response::json(array('msg' => $msg, 'status' => $status));

        }
        catch(Exception $e){
            return Response::json(array('msg' => 'Error en la transacción', 'status' => 'error'));
            DB::rollBack();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id_charter = decrypt($id);
        $charter = Charter::find($id_charter);

        $info["codigo"] = $charter->codigo;
        $info["descripcion"] = $charter->descripcion;
        $info["yacht"] = $charter->yacht;
        $info["broker"] = $charter->broker;
        $info["cliente"] = $charter->cliente;
        $info["fecha_inicio"] = Carbon::parse($charter->fecha_inicio)->format('d-m-Y');
        $info["fecha_fin"] = Carbon::parse($charter->fecha_fin)->format('d-m-Y');
        $info["precio_venta"] = $charter->precio_venta;
        $info["yacht_rack"] = $charter->yacht_rack;
        $info["neto"] = $charter->neto;
        $info["porcentaje_comision_broker"] = $charter->porcentaje_comision_broker;
        $info["comision_broker"] = $charter->comision_broker;
        $info["costo_deluxe"] = $charter->costo_deluxe;
        $info["comision_glc"] = $charter->comision_glc;
        $info["apa"] = $charter->apa;
        $info["contrato"] = $charter->contrato;

        return Response::json(['charter' => $info, 'id' => $id]);
    }

    public function editar($id)
    {
        $id_charter = decrypt($id);
        $charter = Charter::find($id_charter);
        $brokers = Broker::all();
        $programas = Programa::all();
        $tipos_patente = TiposPatente::all();
        $tipo_charter = 0;
        $charter_embarcacion = array();

        if(count($charter->embarcacions) == 0){
            $tipo_charter = 0;
        }
        if(count($charter->embarcacions) > 1){
            $tipo_charter = 3;

            foreach ($charter->embarcacions as $key => $value) {
                $itinerarios = array();
                $embarcaciones = Embarcacion::where('tipos_patente_id', '=', $value->tipos_patente->id)->get();

                foreach ($value->itinerarios as $key => $itinerario) {
                    $itinerarios[$itinerario->id] = $itinerario->nombre;
                }

                $charter_embarcacion[] = array('id' => $value->pivot->id, 'patente' => strtoupper($value->tipos_patente->descripcion), 'embarcacion_selected' => $value->id, 'itinerario_selected' => $value->pivot->itinerarios_id, 'itinerarios' => $itinerarios, 'embarcaciones' => $embarcaciones);
            }
        }else{
            foreach ($charter->embarcacions as $key => $value) {
                $tipo_charter = $value->tipos_patente_id;
                $itinerarios = array();
                $embarcaciones = Embarcacion::where('tipos_patente_id', '=', $value->tipos_patente_id)->get();

                foreach ($value->itinerarios as $key => $itinerario) {
                    $itinerarios[$itinerario->id] = $itinerario->nombre;
                }

                $charter_embarcacion[] = array('id' => $value->pivot->id, 'patente' => strtoupper($value->tipos_patente->descripcion), 'embarcacion_selected' => $value->id, 'itinerario_selected' => $value->pivot->itinerarios_id, 'itinerarios' => $itinerarios, 'embarcaciones' => $embarcaciones);
            }
        }

        return view('charters.editar', ['charter' => $charter, 'brokers' => $brokers, 'programas' => $programas, 'tipos_patente' => $tipos_patente, 'tipo_charter' => $tipo_charter, 'charter_embarcacion' => $charter_embarcacion]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

        dd($request);
        /*$charter = Charter::find(decrypt($request->id_charter));
        $socios = Socio::all();
        $contrato = "Sin contrato";
        
        if(($charter->contrato != $request->contrato) && ($request->contrato != "") && ($request->contrato != "Sin contrato")){
            $directorio_images = 'images/charters/'.$charter->codigo;
            if(count($request->file()) > 0){
                if($request->file()['contrato'] != null){
                    $contrato = Funciones::guardarImagen($request->file()['contrato'], public_path($directorio_images.'/contrato'));    
                }else{
                    $contrato = "Sin contrato";
                }
            }else{
                $contrato = "Sin contrato";
            } 
        }

        $ff_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $ff_init = explode("-", $ff_inicio);

        $descripcion = "";

        $f_inicio = Carbon::parse($request->fecha_inicio);
        $f_fin = Carbon::parse($request->fecha_fin);

        $f_init = explode(",", $f_inicio->toFormattedDateString());
        $f_end = explode(",", $f_fin->toFormattedDateString());
        
        $init_f = explode(" ", $f_init[0]);
        $end_f = explode(" ", $f_end[0]);

        if($init_f[0] == $end_f[0]){
            $descripcion = $f_init[0]." - ".$end_f[1].",".$f_end[1].". (".$request->yacht.")";
        }else{
            $descripcion = $f_init[0]." - ".$f_end[0].",".$f_end[1].". (".$request->yacht.")";   
        }

        $charter->descripcion = strtoupper($descripcion);
        $charter->yacht = strtoupper($request->yacht);
        $charter->brokers_id = $request->broker;
        $charter->cliente = strtoupper($request->cliente);
        $charter->fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $charter->fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
        $charter->anyo = $ff_init[0];
        $charter->mes = $ff_init[1];
        $charter->precio_venta = $request->precio_venta;
        $charter->yacht_rack = $request->yacht_rack;
        $charter->neto = $request->neto;
        $charter->porcentaje_comision_broker = $request->porcentaje_comision_broker;
        $charter->comision_broker = $request->comision_broker;
        $charter->costo_deluxe = $request->costo_deluxe;
        $charter->comision_glc = $request->comision_glc;
        $charter->apa = $request->apa;
        $charter->contrato = $contrato;
        
        if($charter->save()){

            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = 'CHARTER';
            $new_action->accion = 'UPDATE';
            $new_action->comentario = 'Charter '.$descripcion.' actualizado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save(); 

            $msg = 'Charter actualizado exitosamente';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante la edición del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status));*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id_charter = decrypt($id);
        $descripcion = '';
        $charter = Charter::find($id_charter);

        if($charter->descripcion != null){
            $descripcion = $charter->descripcion;    
        }
        
        $codigo_charter = $charter->codigo;

        if($charter->delete()){
            /*Storage::delete('public/images/charters/'.$codigo_charter);**/
            //rmdir(public_path('images/charters/'.$codigo_charter));
            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = 'CHARTER';
            $new_action->accion = 'DELETE';
            $new_action->comentario = 'Charter '.$descripcion.' eliminado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save(); 

            $msg = 'Charter eliminado satisfactoriamente';
            $status = 'success';
        }else{
            $msg = 'No se pudo eliminar el charter intente más tarde';
            $status = 'error';

        }

        return Response::json(['msg' => $msg, 'status' => $status]);
    }

    public function historial_charters(){
        $historial = Historial::where('item', '=', 'CHARTER')->get();

        return Datatables::of($historial)
            ->addColumn('usuario', function ($historial) { 
                if($historial->user != null){
                    return $historial->user->name;
                }
            })
            ->addColumn('comentario', function ($historial) { 
                return $historial->comentario;
            })
            ->addColumn('fecha', function ($historial) { 
                return Carbon::parse($historial->created_at)->format('d-m-Y');
            })
            ->order(function ($query) {
                if (request()->has('fecha')) {
                    $query->orderBy('created_at', 'DESC');
                }
            })
            ->make(true);
    }

    public function variantes_patente($id_tipo){
        $patente = TiposPatente::find($id_tipo);
        if(count($patente->variacion_patentes) > 0){
            foreach ($patente->variacion_patentes as $key => $patente) {
                $primer_orden = TiposPatente::where('id', $patente->primer_orden)->first()->descripcion;
                $segundo_orden = TiposPatente::where('id', $patente->segundo_orden)->first()->descripcion;
                $variantes[$id_tipo][] = array('primer_orden' => $patente->primer_orden, 'segundo_orden' => $patente->segundo_orden, 'descripcion' => $primer_orden."-".$segundo_orden);
            }    
        }else{
            $variantes[$id_tipo][] = TiposPatente::find($id_tipo)->descripcion;
        }

        return Response::json(['variantes' => $variantes]);
    }
}
