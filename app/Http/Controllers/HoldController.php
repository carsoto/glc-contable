<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Embarcacion;
use App\Hold;
use App\Historial;
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

class HoldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $embarcaciones = Embarcacion::all();
        return view('pedidos.holds.index', ['embarcaciones' => $embarcaciones]);
    }

    public function dashboard(){
        $data = Hold::all();   
        return Datatables::of($data)
            ->addColumn('f_inicio', function ($data) {
                return Carbon::parse($data->f_inicio)->format('Y-m-d');
            })
            ->addColumn('f_fin', function ($data) {
                return Carbon::parse($data->f_fin)->format('Y-m-d');
            })
            ->addColumn('yacht', function ($data) {
                if($data->embarcacion != null){
                    return strtoupper($data->embarcacion->nombre);
                }else{
                    return "";
                }
            })
            ->addColumn('itinerario', function ($data) {
                if($data->itinerario != null){
                    return $data->itinerario->nombre;
                }else{
                    return "";
                }
            })
            ->addColumn('tarifa', function ($data) {
                return number_format($data->tarifa, 2, ',', '.');
            })
            ->addColumn('f_limite', function ($data) {
                return Carbon::parse($data->f_limite)->format('d-m-Y');
            })
            ->addColumn('status', function ($data) {
                return strtoupper($data->status);
            })
            ->addColumn('action', function ($data) {
                return '<a href="#" onclick="editar_hold(\''.encrypt($data['id']).'\')"><i class="fa fa-pencil fa-fw" title="Editar"></i></a> <a href="#" onclick="eliminar_hold(\''.encrypt($data['id']).'\')"><i class="fa fa-trash fa-fw" title="Eliminar"></i></a>';
            })
            ->order(function ($query) {
                if (request()->has('f_limite')) {
                    $query->orderBy('f_limite', 'DESC');
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
        DB::beginTransaction();

        try{
            $f_inicio = $request->f_inicio_charter;
            $f_fin = $request->f_fin_charter;
            
            if($request->itinerario_yacht != 0){
               $yacht = Embarcacion::find($request->yacht)->nombre; 
            }else{
                $yacht = "SIN YATE";
            }
            

            $new_hold = new Hold();
            $new_hold->f_inicio = Carbon::parse($f_inicio)->format('Y-m-d');
            $new_hold->f_fin = Carbon::parse($f_fin)->format('Y-m-d');
            $new_hold->status = $request->status;
            
            if($request->yacht != 0){
                $new_hold->yacht = $request->yacht;     
            }
        
            if($request->itinerario_yacht != 0){
                $new_hold->itinerario_id = $request->itinerario_yacht;
            }
            
            $new_hold->tarifa = $request->tarifa_yacht;
            $new_hold->comentario = $request->comentario;
            $new_hold->f_limite = Carbon::parse($request->f_limite)->format('Y-m-d');

            if($new_hold->save()){
                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'HOLDS';
                $new_action->accion = 'ADD';
                $new_action->comentario = 'Nuevo hold de '.strtoupper($yacht).' ['.$f_inicio.']['.$f_fin.'] registrado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save();

                $msg = 'Hold registrado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante el registro del hold";
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
        $hold = Hold::find(decrypt($id));

        $data["f_inicio"] = Carbon::parse($hold->f_inicio)->format('d-m-Y');
        $data["f_fin"] = Carbon::parse($hold->f_fin)->format('d-m-Y');
        $data["status"] = $hold->status;
        $data["yacht"] = $hold->yacht;
        $data["itinerario_id"] = $hold->itinerario_id;
        $data["tarifa"] = $hold->tarifa;
        $data["comentario"] = $hold->comentario;
        $data["f_limite"] = Carbon::parse($hold->f_limite)->format('d-m-Y');
        
        return Response::json(['hold' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try{
            $f_inicio = $request->f_inicio_charter;
            $f_fin = $request->f_fin_charter;
            
            if($request->itinerario_yacht != 0){
               $yacht = Embarcacion::find($request->yacht)->nombre; 
            }else{
                $yacht = "SIN YATE";
            }
            

            $hold = Hold::find(decrypt($request->hold_id));
            $hold->f_inicio = Carbon::parse($f_inicio)->format('Y-m-d');
            $hold->f_fin = Carbon::parse($f_fin)->format('Y-m-d');
            $hold->status = $request->status;
            
            if($request->yacht != 0){
                $hold->yacht = $request->yacht;     
            }
        
            if($request->itinerario_yacht != 0){
                $hold->itinerario_id = $request->itinerario_yacht;
            }
            
            $hold->tarifa = $request->tarifa_yacht;
            $hold->comentario = $request->comentario;
            $hold->f_limite = Carbon::parse($request->f_limite)->format('Y-m-d');

            if($hold->save()){
                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'HOLDS';
                $new_action->accion = 'UPDATE';
                $new_action->comentario = 'Hold de '.strtoupper($yacht).' ['.$f_inicio.']['.$f_fin.'] actualizado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save();

                $msg = 'Hold actualizado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante el registro del hold";
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hold = Hold::find(decrypt($id));
        $yate = $hold->embarcacion->nombre;

        if($hold->delete()){
            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = 'HOLDS';
            $new_action->accion = 'DELETE';
            $new_action->comentario = 'Hold del yate: '.strtoupper($yate).' fue eliminado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save(); 

            $msg = 'Hold eliminado satisfactoriamente';
            $status = 'success';
        }else{
            $msg = 'No se pudo eliminar el hold intente más tarde';
            $status = 'error';
        }

        return Response::json(['msg' => $msg, 'status' => $status]);
    }

    public function historial(){

        $historial = Historial::where('item', '=', 'HOLDS')->get();

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
            ->addColumn('hora', function ($historial) { 
                return Carbon::parse($historial->created_at)->format('H:i:s');
            })
            ->order(function ($query) {
                if (request()->has('fecha')) {
                    $query->orderBy('created_at', 'DESC');
                }
            })
            ->make(true);
    }
}
