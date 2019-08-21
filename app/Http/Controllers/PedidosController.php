<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\PedidosStatus;
use App\TipoContacto;
use App\Historial;
use App\SeguimientoPedido;
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

class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos_contacto = TipoContacto::all();
        $statuses = PedidosStatus::all();
        return view('pedidos.index', ['tipos_contacto' => $tipos_contacto, 'statuses' => $statuses]);
    }

    public function dashboard(){
        /*$pedido_estatus = PedidosStatus::where('descripcion', '=', $estatus)->first();
        $pedidos = array();

        if($pedido_estatus != null){
            $pedidos = Pedido::where('pedidos_status_id', '=', $pedido_estatus->id)->get();    
        }*/
        $pedidos = Pedido::all();    
        return Datatables::of($pedidos)
            ->addColumn('fecha', function ($pedidos) {
                return Carbon::parse($pedidos->fecha)->format('Y-m-d');
            })
            ->addColumn('compania', function ($pedidos) {
                return $pedidos->compania;
            })
            ->addColumn('solicitante', function ($pedidos) {
                return $pedidos->solicitante;
            })
            ->addColumn('f_inicio', function ($pedidos) {
                return Carbon::parse($pedidos->f_inicio)->format('d-m-Y');
            })
            ->addColumn('f_fin', function ($pedidos) {
                return Carbon::parse($pedidos->f_fin)->format('d-m-Y');
            })
            ->addColumn('prox_seguimiento', function ($pedidos) {
                return Carbon::parse($pedidos->prox_seguimiento)->format('d-m-Y');
            })
            ->addColumn('status', function ($pedidos) {
                return $pedidos->pedidos_status->descripcion;
            })
            ->addColumn('action', function ($pedidos) {
                return '<a href="#" onclick="seguimientos_pedido(\''.encrypt($pedidos['id']).'\')"><i class="fa fa-history fa-fw" title="Detalles"></i></a> <a href="#" onclick="editar_pedido(\''.encrypt($pedidos['id']).'\')"><i class="fa fa-pencil fa-fw" title="Editar"></i></a> <a href="#" onclick="eliminar_pedido(\''.encrypt($pedidos['id']).'\')"><i class="fa fa-trash fa-fw" title="Eliminar"></i></a>';
            })
            ->order(function ($query) {
                if (request()->has('fecha')) {
                    $query->orderBy('fecha', 'DESC');
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
            if($request->next_follow == null){
                $fecha = Carbon::parse($request->fecha_pedido)->format('Y-m-d');
                $next_follow = Carbon::createFromFormat('d-m-Y',$request->fecha_pedido)->addDays(2)->toDateString();
            }else{
                $next_follow = Carbon::parse($request->next_follow)->format('Y-m-d');
            }

            $new_pedido = new Pedido();
            $new_pedido->fecha = Carbon::parse($request->fecha_pedido)->format('Y-m-d');
            $new_pedido->tipo_contacto_id = $request->tipo_contacto;
            $new_pedido->compania = strtoupper($request->company);
            $new_pedido->solicitante = strtoupper($request->name);
            $new_pedido->telefono = $request->phone;
            $new_pedido->email = $request->email;
            $new_pedido->f_inicio = Carbon::parse($request->date_start)->format('Y-m-d');
            $new_pedido->f_fin = Carbon::parse($request->date_finish)->format('Y-m-d');
            $new_pedido->detalles = $request->details;
            $new_pedido->prox_seguimiento = $next_follow;
            $new_pedido->pedidos_status_id = 1;

            if($new_pedido->save()){
                $new_seguimiento = new SeguimientoPedido();
                $new_seguimiento->pedidos_id = $new_pedido->id;
                $new_seguimiento->fecha = $next_follow;
                $new_seguimiento->save();

                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'PEDIDOS';
                $new_action->accion = 'ADD';
                $new_action->comentario = 'Nuevo pedido de '.strtoupper($request->name).' registrado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save();

                $msg = 'Pedido registrado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante el registro del pedido";
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
        $pedido = Pedido::find(decrypt($id));

        $reg_pedido["id"] = $pedido->id;
        $reg_pedido["tipo_contacto_id"] = $pedido->tipo_contacto_id;
        $reg_pedido["fecha"] = Carbon::parse($pedido->fecha)->format('d-m-Y');
        $reg_pedido["compania"] = $pedido->compania;
        $reg_pedido["solicitante"] = $pedido->solicitante;
        $reg_pedido["telefono"] = $pedido->telefono;
        $reg_pedido["email"] = $pedido->email;
        $reg_pedido["f_inicio"] = Carbon::parse($pedido->f_inicio)->format('d-m-Y');
        $reg_pedido["f_fin"] = Carbon::parse($pedido->f_fin)->format('d-m-Y');
        $reg_pedido["prox_seguimiento"] = Carbon::parse($pedido->prox_seguimiento)->format('d-m-Y');
        $reg_pedido["detalles"] = $pedido->detalles;
        $reg_pedido["pedidos_status_id"] = $pedido->pedidos_status_id;

        return Response::json(['pedido' => $reg_pedido, 'id' => $id]);
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

            $pedido = Pedido::find(decrypt($request->pedido_id));
            $pedido->fecha = Carbon::parse($request->fecha_pedido)->format('Y-m-d');
            $pedido->tipo_contacto_id = $request->tipo_contacto;
            $pedido->compania = strtoupper($request->company);
            $pedido->solicitante = strtoupper($request->name);
            $pedido->telefono = $request->phone;
            $pedido->email = $request->email;
            $pedido->f_inicio = Carbon::parse($request->date_start)->format('Y-m-d');
            $pedido->f_fin = Carbon::parse($request->date_finish)->format('Y-m-d');
            $pedido->detalles = $request->details;
            $pedido->pedidos_status_id = $request->pedido_status;

            $status_pedido = PedidosStatus::find($request->pedido_status);

            if($pedido->save()){

                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'PEDIDOS';
                $new_action->accion = 'UPDATE';
                $new_action->comentario = 'Pedido de '.strtoupper($request->name).' actualizado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save();

                $msg = 'Pedido actualizado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante el registro del pedido";
                $status = 'error';
            }  

            DB::commit();
            return Response::json(array('msg' => $msg, 'status' => $status, 'pedido_status' => $status_pedido->descripcion));
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
        $pedido = Pedido::find(decrypt($id));
        $descripcion = 'Pedido solicitado por '.$pedido->solicitante;

        if($pedido->delete()){
            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = 'PEDIDOS';
            $new_action->accion = 'DELETE';
            $new_action->comentario = $descripcion.' fue eliminado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save(); 

            $msg = 'Pedido eliminado satisfactoriamente';
            $status = 'success';
        }else{
            $msg = 'No se pudo eliminar el pedido intente más tarde';
            $status = 'error';
        }

        return Response::json(['msg' => $msg, 'status' => $status]);
    }

    public function historial_pedidos(){
        $historial = Historial::where('item', '=', 'PEDIDOS')->get();

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

    public function seguimientos($pedido_id){
        $pedido = Pedido::find(decrypt($pedido_id));
        $seguimientos = $pedido->seguimiento_pedidos;

        return Datatables::of($seguimientos)
            ->addColumn('fecha', function ($seguimientos) { 
                return Carbon::parse($seguimientos->fecha)->format('d-m-Y');
            })
            ->addColumn('usuario', function ($seguimientos) { 
                if($seguimientos->users_id != null){
                    return $seguimientos->user->name;
                }
            })
            ->addColumn('comentario', function ($seguimientos) { 
                return $seguimientos->comentario;
            })
            ->addColumn('action', function ($seguimientos) {
                return '<a href="#" onclick="editar_seguimiento(\''.encrypt($seguimientos->id).'\')"><i class="fa fa-pencil fa-fw" title="Editar"></i></a>';
            })
            ->order(function ($query) {
                if (request()->has('fecha')) {
                    $query->orderBy('fecha', 'DESC');
                }
            })
            ->make(true);
    }

    public function registrar_seguimiento(Request $request){

        DB::beginTransaction();

        try{
            $pedido = Pedido::find(decrypt($request->pedido_id));
            $new_seguimiento = new SeguimientoPedido();
            $new_seguimiento->users_id = Auth::id();
            $new_seguimiento->pedidos_id = decrypt($request->pedido_id);
            $new_seguimiento->fecha = Carbon::parse($request->fecha_seguimiento)->format('Y-m-d');
            $new_seguimiento->comentario = $request->details_seguimiento;

            if($new_seguimiento->save()){
                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'PEDIDOS';
                $new_action->accion = 'SEGUIMIENTO PEDIDO '.decrypt($request->pedido_id);
                $new_action->comentario = 'Nuevo seguimiento al pedido de '.$pedido->solicitante.' registrado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save();

                $msg = 'Seguimiento registrado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante el registro del seguimiento";
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

    public function actualizar_seguimiento(Request $request){
        DB::beginTransaction();

        try{
            $pedido = Pedido::find(decrypt($request->pedido_id));
            $seguimiento = SeguimientoPedido::find(decrypt($request->seguimiento_id));
            $seguimiento->users_id = Auth::id();
            $seguimiento->pedidos_id = decrypt($request->pedido_id);
            $seguimiento->fecha = Carbon::parse($request->fecha_seguimiento)->format('Y-m-d');
            $seguimiento->comentario = $request->details_seguimiento;

            if($seguimiento->save()){
                $new_action = new Historial();
                $new_action->users_id = Auth::id();
                $new_action->item = 'PEDIDOS';
                $new_action->accion = 'SEGUIMIENTO PEDIDO '.decrypt($request->pedido_id);
                $new_action->comentario = 'Actualizado seguimiento al pedido de '.$pedido->solicitante.' registrado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
                $new_action->save();

                $msg = 'Seguimiento actualizado exitosamente';
                $status = 'success';
            }else{
                $msg = "Ocurrió un error durante la actualización del seguimiento";
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

    public function editar_seguimiento($id)
    {
        $seguimiento = SeguimientoPedido::find(decrypt($id));
        $reg_seguimiento["fecha"] = Carbon::parse($seguimiento->fecha)->format('d-m-Y');
        $reg_seguimiento["comentario"] = $seguimiento->comentario;

        return Response::json(['seguimiento' => $reg_seguimiento]);
    }

}
