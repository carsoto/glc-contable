<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Socio;
use App\Charter;
use App\Comisione;
use App\AbonosComisione;
use App\GastosDetalle;
use App\Entrada;
use App\TipoGasto;
use App\Gasto;
use Carbon\Carbon;
use DB;
use Redirect;
use File;
use Response;
use Auth;
use Datatables;
use PDF;

class ComisionesController extends Controller
{
    public function index(){
        $socios = Socio::all();
        return view('comisiones.index', ['socios' => $socios]);
    }

    public function edit($id){
        $id_charter = decrypt($id);
        $charter = Charter::find($id_charter);
        $tipos_gastos = TipoGasto::all();
        $totales = $this->calcular_totales($charter);
        //dd($totales);
        //"total_comision_abonado" => $totales["comisiones"]["abonado"], "total_comision_saldo" => $totales["comisiones"]["saldo"]
        return view('comisiones.editar', ['charter' => $charter, 'tipos_gastos' => $tipos_gastos, 'entradas' => $totales['entradas'], 'salidas' => $totales['salidas'], 'comisiones' => $totales['comisiones'], 'global' => $totales['global']]);
    }

    public function actualizar($id){
        $id_charter = decrypt($id);
        $charter = Charter::find($id_charter);

        //$charter->fecha_inicio = 
        $info["codigo"] = $charter->codigo;
        $info["descripcion"] = $charter->descripcion;
        $info["yacht"] = $charter->yacht;
        $info["broker"] = $charter->broker;
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

    public function update(Request $request){
        $charter = Charter::find(decrypt($request->id_charter));

        if(($charter->contrato != $request->contrato) && ($request->contrato != "") && ($request->contrato != "Sin contrato")){
            $directorio_images = 'images/charters/'.$charter->codigo;
            if(count($request->file()) > 0){
                if($request->file()['contrato'] != null){
                    $contrato = $this->guardarImagen($request->file()['contrato'], public_path($directorio_images.'/contrato'));    
                }else{
                    $contrato = "Sin contrato";
                }
            }else{
                $contrato = "Sin contrato";
            } 
        }

        $charter->yacht = strtoupper($request->yacht);
        $charter->broker = strtoupper($request->broker);
        $charter->fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d') ;
        $charter->fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
        $charter->anyo = date('Y');
        $charter->mes = date('m');
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
            $msg = 'Charter actualizado exitosamente';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante la edición del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status));
    }

    public function store(Request $request){
        $descripcion = "";
        $n_fecha_inicio = str_replace("-", "", $request->fecha_inicio);
        $codigo = "CHT-".$n_fecha_inicio;
        $charter_reg = Charter::where('codigo', '=', $codigo)->get();
        $socios = Socio::all();

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

        if($init_f[0] == $end_f[0]){
            $descripcion = $f_init[0]." - ".$end_f[1].", ".$f_init[1].". (".$request->yacht.")";
        }else{
            $descripcion = $f_init[0]." - ".$f_end[0].", ".$f_init[1].". (".$request->yacht.")";   
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
                $contrato = $this->guardarImagen($request->file()['contrato'], public_path($directorio_images.'/contrato'));    
            }else{
                $contrato = "Sin contrato";
            }
        }else{
            $contrato = "Sin contrato";
        } 

        $charter = new Charter();
        $charter->creado_por = Auth::id();
        $charter->codigo = $codigo_charter;
        $charter->descripcion = strtoupper($descripcion);
        $charter->yacht = strtoupper($request->yacht);
        $charter->broker = strtoupper($request->broker);
        $charter->fecha_inicio = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
        $charter->fecha_fin = Carbon::parse($request->fecha_fin)->format('Y-m-d');
        $charter->anyo = date('Y');
        $charter->mes = date('m');
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
            foreach ($socios as $key => $socio) {
                $comision = ($request->comision_glc * $socio->porcentaje)/100;
                $new_comision = new Comisione();
                $new_comision->users_id = Auth::id();
                $new_comision->charters_id = $charter->id;
                $new_comision->socios_id = $socio->id;
                $new_comision->porcentaje_comision_socio = $socio->porcentaje;
                $new_comision->monto = $comision;
                $new_comision->save();
            }

            $tipos_gastos = TipoGasto::all();

            $contabilidad_adicional = array('OPERADOR NETO' => $request->neto, 'DELUXE' => $request->costo_deluxe, 'COMISIONES' => $request->comision_glc, 'APA' => $request->apa, 'BROKER' => $request->comision_broker);
            
            foreach ($tipos_gastos as $key => $t) {
                $new_gasto = new Gasto();
                $new_gasto->users_id = Auth::id();
                $new_gasto->charters_id = $charter->id;
                $new_gasto->tipo_gasto_id = $t->id;
                $new_gasto->total = $contabilidad_adicional[$t->descripcion];
                $new_gasto->gastos = 0;
                $new_gasto->saldo = 0;
                $new_gasto->save(); 

                if($t->descripcion == 'BROKER'){
                    $new_detalle = new GastosDetalle();
                    $new_detalle->users_id = Auth::id();
                    $new_detalle->gastos_id = $new_gasto->id;
                    $new_detalle->monto = $contabilidad_adicional[$t->descripcion];
                    $new_detalle->fecha = date('Y-m-d');
                    $new_detalle->comentario = 'Registro de pago automático';
                    $new_detalle->save();
                }
            }

            $msg = 'Charter registrado exitosamente';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante el registro del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status));
    }

    public function comisiones_charters(){
        $charters = Charter::all();
        return Datatables::of($charters)
            ->addColumn('precio_venta', function ($charters) { 
                return "$ ".number_format($charters->precio_venta, 2, '.', ',');
            })
            ->addColumn('deluxe_total', function ($charters) { 
                return "$ ".number_format($charters->costo_deluxe, 2, '.', ',');
            })
            ->addColumn('deluxe_gastos', function ($charters) { 
                return "$ 0.00";
            })
            ->addColumn('deluxe_saldo', function ($charters) { 
                return "$ 0.00";
            })
            ->addColumn('aryel_total', function ($charters) {
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 1)->first();
                if($comision != null){
                    return "$ ".number_format($comision->monto, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('aryel_abono', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 1)->first();
                if($comision != null){
                    return "$ ".number_format($comision->abonado, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('aryel_saldo', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 1)->first();
                if($comision != null){
                    return "$ ".number_format($comision->saldo, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('stephi_total', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 2)->first();
                if($comision != null){
                    return "$ ".number_format($comision->monto, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('stephi_abono', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 2)->first();
                if($comision != null){
                    return "$ ".number_format($comision->abonado, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('stephi_saldo', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 2)->first();
                if($comision != null){
                    return "$ ".number_format($comision->saldo, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('glc_total', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 3)->first();
                if($comision != null){
                    return "$ ".number_format($comision->monto, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('glc_abono', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 3)->first();
                if($comision != null){
                    return "$ ".number_format($comision->abonado, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('glc_saldo', function ($charters) { 
                $comision = Comisione::where('charters_id', '=', $charters->id)->where('socios_id', '=', 3)->first();
                if($comision != null){
                    return "$ ".number_format($comision->saldo, 2, '.', ',');
                }
                return "$ 0.00";
            })
            ->addColumn('action', function ($charters) {
                return '<a href="editar-charter/'.encrypt($charters['id']).'"><i class="fa fa-eye fa-fw" title="Detalles"></i></a> <a href="#" onclick="editar_charter(\''.encrypt($charters['id']).'\')"><i class="fa fa-pencil fa-fw" title="Editar"></i></a>';
            })
            
            ->make(true);
    }

    public function abonos_comisiones($id_comision){

        $abonos = AbonosComisione::where('comisiones_id', '=', $id_comision)->get();
        return Datatables::of($abonos)
            ->addColumn('user', function ($abonos) { 
                return $abonos->user->name;
            })
            ->addColumn('monto', function ($abonos) { 
                return "$ ".number_format($abonos->monto,2,".",",");
            })
            ->addColumn('comentario', function ($abonos) { 
                if($abonos->comentario == null){
                    return "-";
                }
                return $abonos->comentario;
            })
            ->addColumn('fecha', function ($abonos) { 
                return Carbon::parse($abonos->fecha)->format('d-m-Y');
            })
            ->addColumn('created_at', function ($abonos) { 
                return Carbon::parse($abonos->created_at)->format('d-m-Y');
            })
            ->make(true);
    }

    public function guardarImagen($archivo, $directorio){
        if($archivo != null){
            $image = $archivo;
            $imageName = $image->getClientOriginalName();
            $image->move($directorio, $imageName);
            $name = $imageName;
        }else{
            $name = null;
        }

        return $name;
    }

    private function calcular_totales($charter){ 
        $comision_abonado = $comision_monto = $comision_saldo = $total_recibido = $total_pendiente = $total_gastos = $total_saldo = 0;

        foreach ($charter->entradas as $key => $entrada) {
            $total_recibido += $entrada->monto;
        }

        $total_pendiente = $charter->precio_venta + $charter->apa - $total_recibido - $charter->comision_broker2;

        foreach($charter->comisiones AS $key => $comision){
            $comision_abonado += $comision->abonado;
            $comision_monto += $comision->monto; 
        }

        $gastos = Gasto::where('charters_id', '=', $charter->id)->get();
        $tipo_comisiones = TipoGasto::where('descripcion', '=', 'COMISIONES')->first();

        foreach ($gastos as $key => $gasto) {
            if($gasto->tipo_gasto_id == $tipo_comisiones->id){
                $gasto->gastos = $comision_abonado;
                $gasto->saldo = $gasto->total - $comision_abonado;
                $gasto->save();
            }else{
                $acumulado = 0;
                
                foreach ($gasto->gastos_detalles as $d => $detalle) {
                    $acumulado += $detalle->monto;
                }

                $gasto->gastos = $acumulado;
                $gasto->saldo = $gasto->total - $acumulado;
                $gasto->save();
            }
        }
        foreach ($charter->gastos as $key => $salida) {
            $salidas[$salida->tipo_gasto_id] = array('gastos' => "$ ".number_format($salida->gastos, 2, '.', ','), 'saldo' => "$ ".number_format($salida->saldo, 2, '.', ','));
            $total_gastos += $salida->gastos;
            $total_saldo += $salida->saldo;
        }
        //dd($total_recibido, $total_gastos, $total_saldo);
        $global_pendiente = $total_saldo;
        $global_total = $charter->precio_venta + $charter->apa;

        $total_recibido = "$ ".number_format($total_recibido, 2, '.', ',');
        $total_pendiente = "$ ".number_format($total_pendiente, 2, '.', ',');

        $entradas = array('recibido' => $total_recibido, 'pendiente' => $total_pendiente);

        $comision_saldo = $comision_monto - $comision_abonado;
        $comision_monto = "$ ".number_format($charter->comision_glc, 2, '.', ',');
        $comision_abonado = "$ ".number_format($comision_abonado, 2, '.', ',');
        $comision_saldo = "$ ".number_format($comision_saldo, 2, '.', ',');

        $comisiones = array('monto' => $comision_monto, 'abonado' => $comision_abonado, 'saldo' => $comision_saldo);

        $total_gastos = "$ ".number_format($total_gastos, 2, '.', ',');
        $global_pendiente = "$ ".number_format($global_pendiente, 2, '.', ',');
        $global_total = "$ ".number_format($global_total, 2, '.', ',');

        $global = array('total' => $global_total, 'gastos' => $total_gastos, 'saldo' => $global_pendiente);

        return array('entradas' => $entradas, 'salidas' => $salidas, 'comisiones' => $comisiones , 'global' => $global);
    }

    public function crear_abono_comision(Request $request){
        $comision = Comisione::find($request->id_comision);
        $charter = Charter::find($comision->charters_id);

        $abonos_acumulados = AbonosComisione::where('comisiones_id', '=', $request->id_comision)->get();
        $abonado = 0;
        foreach ($abonos_acumulados as $key => $abonos) {
            $abonado += $abonos->monto;
        }

        $abono = new AbonosComisione();
        $abono->users_id = Auth::id();
        $abono->comisiones_id = $request->id_comision;
        $abono->monto = $request->abono_monto;
        $abono->fecha = Carbon::parse($request->abono_fecha)->format('Y-m-d');
        $abono->comentario = $request->abono_comentario;

        if($abono->save()){
            $comision->abonado = $abonado + $request->abono_monto;
            $comision->saldo = $comision->monto - $request->abono_monto - $abonado;
            $comision->fecha_ult_abono = Carbon::parse($request->abono_fecha)->format('Y-m-d');
            $comision->save();

            $msg = 'Abono registrado exitosamente';
            $status = 'success';

            $r_id_comision = $request->id_comision;
            $r_abonado = "$ ".number_format(($abonado + $request->abono_monto), 2, ".", ",");
            $r_saldo = "$ ".number_format(($comision->monto - $request->abono_monto - $abonado), 2, ".", ",");
            $r_fecha_ult_abono = Carbon::parse($request->abono_fecha)->format('d-m-Y');

            $totales = $this->calcular_totales($charter);
        }else{
            $msg = "Ocurrió un error durante el registro del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status, "id_comision" => $r_id_comision, "abonado" => $r_abonado, "saldo" => $r_saldo, "fecha_ult_abono" => $r_fecha_ult_abono, 'totales' => $totales));
    }

    public function crear_gasto(Request $request){
        $gasto = Gasto::find($request->id_gasto);
        $charter = Charter::find($gasto->charters_id);

        $new_detalle = new GastosDetalle();
        $new_detalle->users_id = Auth::id();
        $new_detalle->gastos_id = $request->id_gasto;
        $new_detalle->monto = $request->gasto_monto;
        $new_detalle->fecha = Carbon::parse($request->gasto_fecha)->format('Y-m-d');
        $new_detalle->comentario = $request->gasto_comentario;

        if($new_detalle->save()){
            $r_id_gasto = $request->id_gasto;

            $gastos_acumulados = GastosDetalle::where('gastos_id', '=', $request->id_gasto)->get();
            $gastado = 0;

            foreach ($gastos_acumulados as $key => $gasto_d) {
                $gastado += $gasto_d->monto;
            }

            $gasto->gastos = $gastado;
            $gasto->saldo = $gasto->total - $gastado;
            $gasto->fecha_ult_abono = Carbon::parse($request->gasto_fecha)->format('Y-m-d');
            $gasto->save();

            $r_gastos = "$ ".number_format($gastado, 2, ".", ",");
            $r_saldo = "$ ".number_format(($gasto->total - $gastado), 2, ".", ",");
            $r_fecha_ult_gasto = Carbon::parse($request->gasto_fecha)->format('d-m-Y');

            $totales = $this->calcular_totales($charter);

            $msg = 'Gasto registrado exitosamente';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante el registro del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status, "id_gasto" => $r_id_gasto, 'totales' => $totales, 'r_gastos' => $r_gastos, 'r_saldo' => $r_saldo, 'r_fecha_ult_gasto' => $r_fecha_ult_gasto));
    }

    public function crear_entrada_charter(Request $request){
        $charter = Charter::find($request->entrada["charter_id"]);
        $total_recibido = 0;
        $total_pendiente = 0;

        if($request->entrada["tipo_recibo"] == "archivo"){
            $directorio_images = 'images/charters/'.$charter->codigo;

            if(!File::exists(public_path($directorio_images))) {
                File::makeDirectory(public_path($directorio_images));

                if(!File::exists(public_path($directorio_images.'/recibos'))){
                    File::makeDirectory(public_path($directorio_images.'/recibos'));
                }
            }

            if(count($request->file()) > 0){
                if($request->file()['entrada']['archivo'] != null){
                    $recibo = $this->guardarImagen($request->file()['entrada']['archivo'], public_path($directorio_images.'/recibos'));    
                }else{
                    $recibo = "";
                }
            }else{
                $recibo = "";
            } 
        }

        else{
            $recibo = $request->entrada["link"];
        }

        $nueva_entrada = new Entrada();
        $nueva_entrada->charters_id = $charter->id;
        $nueva_entrada->registrado_por = Auth::id();
        $nueva_entrada->fecha = Carbon::parse($request->entrada["fecha"])->format('Y-m-d');
        $nueva_entrada->monto = $request->entrada["monto"];
        $nueva_entrada->comentario = $request->entrada["comentario"];
        $nueva_entrada->banco = strtoupper($request->entrada["banco"]);
        $nueva_entrada->referencia = strtoupper($request->entrada["referencia"]);
        $nueva_entrada->tipo_recibo = $request->entrada["tipo_recibo"];
        $nueva_entrada->link_papeleta_pago = $recibo;
        
        if($nueva_entrada->save()){
            $msg = 'Entrada registrada exitosamente.';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante el registro de la entrada.";
            $status = 'error';
        }

        $totales = $this->calcular_totales($charter);

        return Response::json(array('msg' => $msg, 'status' => $status,'total_recibido' => $totales['entradas']['recibido'], 'total_pendiente' => $totales['entradas']['pendiente']));
    }

    public function edit_entrada($id){
        $entrada = Entrada::find($id);
        $registro = array();
        $registro["id"] = $entrada->id;
        $registro["charters_id"] = $entrada->charters_id;
        $registro["fecha"] = Carbon::parse($entrada->fecha)->format('d-m-Y');
        $registro["monto"] = $entrada->monto;
        $registro["comentario"] = $entrada->comentario;
        $registro["banco"] = $entrada->banco;
        $registro["referencia"] = $entrada->referencia;
        $registro["tipo_recibo"] = $entrada->tipo_recibo;
        $registro["link_papeleta_pago"] = $entrada->link_papeleta_pago;

        return Response::json(['entrada' => $registro]);
    }

    public function actualizar_entrada_charter(Request $request){
        $e = Entrada::find($request->entrada["id_entrada"]);
        $charter = Charter::find($request->entrada["id_charter"]);

        if($request->entrada["tipo_recibo"] == "archivo"){
            $directorio_images = 'images/charters/'.$charter->codigo;

            if(!File::exists(public_path($directorio_images))) {
                File::makeDirectory(public_path($directorio_images));

                if(!File::exists(public_path($directorio_images.'/recibos'))){
                    File::makeDirectory(public_path($directorio_images.'/recibos'));
                }
            }

            if(count($request->file()) > 0){
                if($request->file()['entrada']['archivo'] != null){
                       $recibo = $this->guardarImagen($request->file()['entrada']['archivo'], public_path($directorio_images.'/recibos'));
                }else{
                    $recibo = "";
                }
            }else{
                $recibo = $e->link_papeleta_pago;
            } 
        }

        else{
            $recibo = $request->entrada["link"];
        }

        $e->registrado_por = Auth::id();
        $e->fecha = Carbon::parse($request->entrada["fecha"])->format('Y-m-d');
        $e->monto = $request->entrada["monto"];
        $e->comentario = $request->entrada["comentario"];
        $e->banco = $request->entrada["banco"];
        $e->referencia = $request->entrada["referencia"];
        $e->tipo_recibo = $request->entrada["tipo_recibo"];
        $e->link_papeleta_pago = $recibo;

        if($e->save()){
            $msg = 'Entrada actualizada exitosamente.';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante la actualización de la entrada.";
            $status = 'error';
        }

        $totales = $this->calcular_totales($charter);

        return Response::json(array('msg' => $msg, 'status' => $status,'total_recibido' => $totales['entradas']['recibido'], 'total_pendiente' => $totales['entradas']['pendiente']));
    }

    public function historial_entradas($id_charter){
        $charter = Charter::find($id_charter);
        $entradas = $charter->entradas;
        return Datatables::of($entradas)
            ->addColumn('user', function ($entradas) { 
                return $entradas->user->name;
            })
            ->addColumn('monto', function ($entradas) { 
                return "$ ".number_format($entradas->monto,2,".",",");
            })
            ->addColumn('comentario', function ($entradas) { 
                if($entradas->comentario == null){
                    return "-";
                }
                return $entradas->comentario;
            })
            ->addColumn('fecha', function ($entradas) { 
                return Carbon::parse($entradas->fecha)->format('d-m-Y');
            })
            ->addColumn('created_at', function ($entradas) { 
                return Carbon::parse($entradas->created_at)->format('d-m-Y');
            })
            ->addColumn('action', function ($entradas) use (&$charter) {
                $recibo = "";
                if($entradas->link_papeleta_pago != ""){
                    if($entradas->tipo_recibo == 'link'){
                        $recibo = '<a target="_blank" href='.$entradas->link_papeleta_pago.'><i class="fa fa-paperclip"></i> Recibo</a>';
                    }else{
                        $recibo = '<a target="_blank" href='.asset('images/charters/'.$charter->codigo.'/recibos/'.$entradas->link_papeleta_pago).'><i class="fa fa-paperclip"></i> Recibo</a>';
                    }
                    
                }
                return '<a class="btn btn-flat btn-sm btn-primary" href="#" onclick="editar_entrada('.$entradas->id.')"><i class="fa fa-pencil fa-fw" title="Detalles"></i></a>'.$recibo;
            })
            ->make(true);
    }

    public function historial_gastos($id_gasto){
        $gastos = GastosDetalle::where('gastos_id', '=', $id_gasto)->get();

        return Datatables::of($gastos)
            ->addColumn('user', function ($gastos) { 
                return $gastos->user->name;
            })
            ->addColumn('monto', function ($gastos) { 
                return "$ ".number_format($gastos->monto,2,".",",");
            })
            ->addColumn('comentario', function ($gastos) { 
                if($gastos->comentario == null){
                    return "-";
                }
                return $gastos->comentario;
            })
            ->addColumn('fecha', function ($gastos) { 
                return Carbon::parse($gastos->fecha)->format('d-m-Y');
            })
            ->addColumn('created_at', function ($gastos) { 
                return Carbon::parse($gastos->created_at)->format('d-m-Y');
            })
            ->make(true);
    }

    public function exportarPDF($id){
        $id_charter = decrypt($id);
        $charter = Charter::find($id_charter);
        $tipos_gastos = TipoGasto::all();
        $totales = $this->calcular_totales($charter);
        $pdf = PDF::loadView('comisiones.pdf.comisiones', ['charter' => $charter, 'tipos_gastos' => $tipos_gastos, 'entradas' => $totales['entradas'], 'salidas' => $totales['salidas'], 'comisiones' => $totales['comisiones'], 'global' => $totales['global']]);
        return $pdf->stream('comisiones-'.$charter->codigo.".pdf");
    }

    public function balance_socios(){
        $charters = Charter::all();
        $socios = Socio::all();
        return view('comisiones.balance_socios', ['charters' => $charters, 'socios' => $socios]);
    }
}
