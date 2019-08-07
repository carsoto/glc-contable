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
        return view('comisiones.editar', ['charter' => $charter, 'tipos_gastos' => $tipos_gastos, 'entradas' => $totales['entradas'], 'broker' => $totales['broker'], 'operador' => $totales['operador'], 'deluxe' => $totales['deluxe'], 'apa' => $totales['apa'], 'other' => $totales['other'], 'global' => $totales['global']]);
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
        $socios = Socio::all();
        $contrato = "Sin contrato";
        
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
        $charter->broker = strtoupper($request->broker);
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

        return Response::json(array('msg' => $msg, 'status' => $status));
    }

    public function store(Request $request){

        DB::beginTransaction();
        try{
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
                $descripcion = $f_init[0]." - ".$end_f[1].",".$f_end[1].". (".$request->yacht.")";
            }else{
                $descripcion = $f_init[0]." - ".$f_end[0].",".$f_end[1].". (".$request->yacht.")";   
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

            $ffi = Carbon::parse($request->fecha_inicio)->format('Y-m-d');
            $ff_init = explode("-", $ffi);
            $charter = new Charter();
            $charter->creado_por = Auth::id();
            $charter->codigo = $codigo_charter;
            $charter->descripcion = strtoupper($descripcion);
            $charter->yacht = strtoupper($request->yacht);
            $charter->broker = strtoupper($request->broker);
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
                $count_comision = 0;
            
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

    public function comisiones_charters(){
        $charters = Charter::all();
        return Datatables::of($charters)
            ->addColumn('fecha_inicio', function ($charters) {
                return Carbon::parse($charters->fecha_inicio)->format('d-m-Y');
            })
            ->addColumn('fecha_fin', function ($charters) {
                return Carbon::parse($charters->fecha_fin)->format('d-m-Y');
            })
            ->addColumn('precio_venta', function ($charters) { 
                return "$ ".number_format($charters->precio_venta, 2, '.', ',');
            })
            ->addColumn('deluxe_total', function ($charters) { 
                $totales = $this->calcular_totales($charters);
                return $totales['deluxe']['total'];
            })
            ->addColumn('deluxe_gastos', function ($charters) { 
                $totales = $this->calcular_totales($charters);
                return $totales['deluxe']['gastos'];
            })
            ->addColumn('deluxe_saldo', function ($charters) { 
                $totales = $this->calcular_totales($charters);
                return $totales['deluxe']['saldo'];
            })
            ->addColumn('global_total', function ($charters) {
                $totales = $this->calcular_totales($charters);
                if($totales['global']['total'] != null){
                    return $totales['global']['total'];
                }
                return "$ 0.00";
            })
            ->addColumn('global_gastos', function ($charters) {
                $totales = $this->calcular_totales($charters);
                if($totales['global']['gastos'] != null){
                    return $totales['global']['gastos'];
                }
                return "$ 0.00";
            })
            ->addColumn('global_saldo', function ($charters) { 
                $totales = $this->calcular_totales($charters);
                if($totales['global']['saldo'] != null){
                    return $totales['global']['saldo'];
                }
                return "$ 0.00";
            })
            ->addColumn('action', function ($charters) {
                return '<a href="editar-charter/'.encrypt($charters['id']).'"><i class="fa fa-eye fa-fw" title="Detalles"></i></a> <a href="#" onclick="editar_charter(\''.encrypt($charters['id']).'\')"><i class="fa fa-pencil fa-fw" title="Editar"></i></a><a href="#" onclick="eliminar_charter(\''.encrypt($charters['id']).'\')"><i class="fa fa-trash fa-fw" title="Eliminar"></i></a>';
            })
            ->order(function ($query) {
                if (request()->has('fecha_inicio')) {
                    $query->orderBy('fecha_inicio', 'desc');
                }
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
            ->addColumn('action', function ($abonos) use (&$id_comision) { 
                return '<a href="#" onclick="eliminar_abono_comision('.$id_comision.','.$abonos->id.')"><i class="fa fa-trash fa-fw" ></i></a>';
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

    public static function calcular_totales($charter){ 
        $entrada = $broker = $operador = $deluxe = $apa = $other = $global = array();
        
        $saldo_entrada = $charter->precio_venta + $charter->apa;
        $entrada['total'] = "$ ".number_format($charter->entradas->sum('monto'), 2, '.', ',');
        $entrada['saldo'] = "$ ".number_format($saldo_entrada - $charter->entradas->sum('monto'), 2, '.', ',');

        $broker['total'] = $charter->comision_broker;
        $broker['gastos'] = $charter->gastos->where('categoria', '=', 'BROKER')->sum('neto');
        $broker['cliente'] = $charter->gastos->where('categoria', '=', 'BROKER')->sum('precio_cliente');
        $broker['ganancia'] = $charter->gastos->where('categoria', '=', 'BROKER')->sum('ganancia');
        $broker['saldo'] = $broker['total'] - $broker['gastos'];

        $broker['total'] = "$ ".number_format($broker['total'], 2, '.', ',');
        $broker['gastos'] = "$ ".number_format($broker['gastos'], 2, '.', ',');
        $broker['cliente'] = "$ ".number_format($broker['cliente'], 2, '.', ',');
        $broker['ganancia'] = "$ ".number_format($broker['ganancia'], 2, '.', ',');
        $broker['saldo'] = "$ ".number_format($broker['saldo'], 2, '.', ',');

        $operador['total'] = $charter->neto;
        $operador['gastos'] = $charter->gastos->where('categoria', '=', 'OPERADOR')->sum('neto');
        $operador['cliente'] = $charter->gastos->where('categoria', '=', 'OPERADOR')->sum('precio_cliente');
        $operador['ganancia'] = $charter->gastos->where('categoria', '=', 'OPERADOR')->sum('ganancia');
        $operador['saldo'] = $operador['total'] - $operador['gastos'];

        $operador['total'] = "$ ".number_format($operador['total'], 2, '.', ',');
        $operador['gastos'] = "$ ".number_format($operador['gastos'], 2, '.', ',');
        $operador['cliente'] = "$ ".number_format($operador['cliente'], 2, '.', ',');
        $operador['ganancia'] = "$ ".number_format($operador['ganancia'], 2, '.', ',');
        $operador['saldo'] = "$ ".number_format($operador['saldo'], 2, '.', ',');

        $deluxe['total'] = $charter->costo_deluxe;
        $deluxe['gastos'] = $charter->gastos->where('categoria', '=', 'DELUXE')->sum('neto');
        $deluxe['cliente'] = $charter->gastos->where('categoria', '=', 'DELUXE')->sum('precio_cliente');
        $deluxe['ganancia'] = $charter->gastos->where('categoria', '=', 'DELUXE')->sum('ganancia');
        $deluxe['saldo'] = $deluxe['total'] - $deluxe['gastos'];

        $deluxe['total'] = "$ ".number_format($deluxe['total'], 2, '.', ',');
        $deluxe['gastos'] = "$ ".number_format($deluxe['gastos'], 2, '.', ',');
        $deluxe['cliente'] = "$ ".number_format($deluxe['cliente'], 2, '.', ',');
        $deluxe['ganancia'] = "$ ".number_format($deluxe['ganancia'], 2, '.', ',');
        $deluxe['saldo'] = "$ ".number_format($deluxe['saldo'], 2, '.', ',');

        $apa['total'] = $charter->apa + $charter->entradas->where('tipo_gasto_id', '=', 1)->sum('monto');
        $apa['gastos'] = $charter->gastos->where('categoria', '=', 'APA')->sum('neto');
        $apa['cliente'] = $charter->gastos->where('categoria', '=', 'APA')->sum('precio_cliente');
        $apa['ganancia'] = $charter->gastos->where('categoria', '=', 'APA')->sum('ganancia');
        $apa['saldo'] = $apa['total'] - $apa['gastos'];

        $apa['total'] = "$ ".number_format($apa['total'], 2, '.', ',');
        $apa['gastos'] = "$ ".number_format($apa['gastos'], 2, '.', ',');
        $apa['cliente'] = "$ ".number_format($apa['cliente'], 2, '.', ',');
        $apa['ganancia'] = "$ ".number_format($apa['ganancia'], 2, '.', ',');
        $apa['saldo'] = "$ ".number_format($apa['saldo'], 2, '.', ',');

        $other['total'] = $charter->entradas->where('tipo_gasto_id', '=', 2)->sum('monto');
        $other['gastos'] = $charter->gastos->where('categoria', '=', 'OTHER')->sum('neto');
        $other['cliente'] = $charter->gastos->where('categoria', '=', 'OTHER')->sum('precio_cliente');
        $other['ganancia'] = $charter->gastos->where('categoria', '=', 'OTHER')->sum('ganancia');
        $other['saldo'] = $other['total'] - $other['gastos'];

        $other['total'] = "$ ".number_format($other['total'], 2, '.', ',');
        $other['gastos'] = "$ ".number_format($other['gastos'], 2, '.', ',');
        $other['cliente'] = "$ ".number_format($other['cliente'], 2, '.', ',');
        $other['ganancia'] = "$ ".number_format($other['ganancia'], 2, '.', ',');
        $other['saldo'] = "$ ".number_format($other['saldo'], 2, '.', ',');

        $global['total'] = $charter->entradas->sum('monto');
        $global['gastos'] = $charter->gastos->sum('neto');
        $global['saldo'] = $global['total'] - $global['gastos'];

        $global['total'] = "$ ".number_format($global['total'], 2, '.', ',');
        $global['gastos'] = "$ ".number_format($global['gastos'], 2, '.', ',');
        $global['saldo'] = "$ ".number_format($global['saldo'], 2, '.', ',');

        return array('entradas' => $entrada, 'broker' => $broker, 'operador' => $operador, 'deluxe' => $deluxe, 'apa' => $apa, 'other' => $other,'global' => $global);
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
        $charter = Charter::find($request->gasto["charter_id"]);
        $tipo_gasto = TipoGasto::where('descripcion', '=', strtoupper($request->gasto["categoria"]))->first();
        $id_tipo_gasto = null;
        $ganancia = 0;
        $recibo = null;

        if($tipo_gasto != null){
            $id_tipo_gasto = $tipo_gasto->id;
        }

        if($request->gasto["precio_cliente"] != null){
            $ganancia = $request->gasto["precio_cliente"] - $request->gasto["neto"];
        }

        if($request->gasto["tipo_recibo"] == "archivo"){
            $directorio_images = 'images/charters/'.$charter->codigo;

            if(!File::exists(public_path($directorio_images))) {
                File::makeDirectory(public_path($directorio_images));

                if(!File::exists(public_path($directorio_images.'/recibos'))){
                    File::makeDirectory(public_path($directorio_images.'/recibos'));
                }
            }

            if(count($request->file()) > 0){
                if($request->file()['gasto']['archivo'] != null){
                    $recibo = $this->guardarImagen($request->file()['gasto']['archivo'], public_path($directorio_images.'/recibos'));    
                }else{
                    $recibo = "";
                }
            }else{
                $recibo = "";
            } 
        }

        else{
            $recibo = $request->gasto["link"];
        }
        
        $new_gasto = new Gasto();
        $new_gasto->charters_id = $request->gasto["charter_id"];
        $new_gasto->registrado_por = Auth::id();
        $new_gasto->razon_social = $request->gasto["razon_social"];
        $new_gasto->categoria = strtoupper($request->gasto["categoria"]);
        $new_gasto->precio_cliente = $request->gasto["precio_cliente"];
        $new_gasto->neto = $request->gasto["neto"];
        $new_gasto->ganancia = $ganancia;
        $new_gasto->tipo_gasto_id = $id_tipo_gasto;
        $new_gasto->fecha = Carbon::parse($request->gasto["fecha"])->format('Y-m-d');;
        $new_gasto->comentario = $request->gasto["comentario"];
        $new_gasto->banco = $request->gasto["banco"];
        $new_gasto->referencia = $request->gasto["referencia"];
        $new_gasto->tipo_recibo = $request->gasto["tipo_recibo"];
        $new_gasto->link_papeleta_pago = $recibo;

        if($new_gasto->save()){

            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = strtoupper($request->gasto["categoria"]);
            $new_action->charters_id = $charter->id;
            $new_action->accion = 'ADD';
            $new_action->comentario = 'Gasto de $ '.number_format($request->gasto["neto"], 2, '.', ',').' registrado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save();

            $totales = $this->calcular_totales($charter);

            $msg = 'Gasto registrado exitosamente';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante el registro del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status, 'totales' => $totales));
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
        $nueva_entrada->tipo_gasto_id = $request->entrada["tipo_gasto"];
        $nueva_entrada->comentario = $request->entrada["comentario"];
        $nueva_entrada->banco = strtoupper($request->entrada["banco"]);
        $nueva_entrada->referencia = strtoupper($request->entrada["referencia"]);
        $nueva_entrada->tipo_recibo = $request->entrada["tipo_recibo"];
        $nueva_entrada->link_papeleta_pago = $recibo;
        
        if($nueva_entrada->save()){
            
            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = 'ENTRADA';
            $new_action->charters_id = $charter->id;
            $new_action->accion = 'ADD';
            $new_action->comentario = 'Entrada de $ '.number_format($request->entrada["monto"], 2, '.', ',').' registrada por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save();

            $msg = 'Entrada registrada exitosamente.';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante el registro de la entrada.";
            $status = 'error';
        }

        $totales = $this->calcular_totales($charter);

        return Response::json(array('msg' => $msg, 'status' => $status, 'totales' => $totales));
    }

    public function edit_entrada($id){
        $entrada = Entrada::find($id);
        $registro = array();
        $registro["id"] = $entrada->id;
        $registro["charters_id"] = $entrada->charters_id;
        $registro["fecha"] = Carbon::parse($entrada->fecha)->format('d-m-Y');
        $registro["monto"] = $entrada->monto;
        $registro["tipo_gasto_id"] = $entrada->tipo_gasto_id;
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
        $monto_ant = $e->monto;

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
        $e->tipo_gasto_id = $request->entrada["tipo_gasto"];
        $e->comentario = $request->entrada["comentario"];
        $e->banco = $request->entrada["banco"];
        $e->referencia = $request->entrada["referencia"];
        $e->tipo_recibo = $request->entrada["tipo_recibo"];
        $e->link_papeleta_pago = $recibo;

        if($e->save()){
            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = 'ENTRADA';
            $new_action->charters_id = $charter->id;
            $new_action->accion = 'UPDATE';
            $new_action->comentario = 'Entrada actualizada de $ '.number_format($monto_ant, 2, '.', ',').' a $ '.number_format($request->entrada["monto"], 2, '.', ',').' registrada por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save(); 

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
                return '<a href="#" onclick="editar_entrada('.$entradas->id.')"><i class="fa fa-pencil fa-fw" title="Detalles"></i></a> <a href="#" onclick="eliminar_entrada('.$entradas->id.')"><i class="fa fa-trash fa-fw"></i></a>'.$recibo;
            })
            ->make(true);
    }

    public function historial_gastos($tipo, $id_charter){
        $gastos = Historial::where('charters_id', '=', $id_charter)->where('item', '=', strtoupper($tipo))->get();

        return Datatables::of($gastos)
            ->addColumn('usuario', function ($gastos) { 
                return $gastos->user->name;
            })
            ->addColumn('accion', function ($gastos) { 
                return $gastos->accion;
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

    public function gastos($tipo, $id_charter){
        $categoria = strtoupper($tipo);
        $gastos = Gasto::where('charters_id', '=', $id_charter)->where('categoria', '=', $categoria)->get();
        
        if(($categoria != 'APA') || ($categoria == 'OTHER')){

            return Datatables::of($gastos)
            ->addColumn('registrado_por', function ($gastos) { 
                return $gastos->user->name;
            })
            ->addColumn('monto', function ($gastos) { 
                return "$ ".number_format($gastos->neto,2,".",",");
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
            ->addColumn('action', function ($gastos) use (&$charter, &$tipo) {
                $recibo = "";
                /*if($gastos->link_papeleta_pago != ""){
                    if($gastos->tipo_recibo == 'link'){
                        $recibo = '<a target="_blank" href='.$gastos->link_papeleta_pago.'><i class="fa fa-paperclip"></i> Recibo</a>';
                    }else{
                        $recibo = '<a target="_blank" href='.asset('images/charters/'.$charter->codigo.'/recibos/'.$gastos->link_papeleta_pago).'><i class="fa fa-paperclip"></i> Recibo</a>';
                    }
                    
                }*/
                return '<a href="#" onclick="editar_gasto('.$gastos->id.', \''.$tipo.'\')"><i class="fa fa-pencil fa-fw" title="Detalles"></i></a> <a href="#" onclick="eliminar_gasto('.$gastos->id.', \''.$tipo.'\')"><i class="fa fa-trash fa-fw"></i></a>'.$recibo;
            })
            ->make(true);
        }else{
            return Datatables::of($gastos)
            ->addColumn('registrado_por', function ($gastos) { 
                return $gastos->user->name;
            })
            ->addColumn('comentario', function ($gastos) { 
                if($gastos->comentario == null){
                    return "-";
                }
                return $gastos->comentario;
            })
            ->addColumn('precio_cliente', function ($gastos) { 
                return "$ ".number_format($gastos->precio_cliente,2,".",",");
            })
            ->addColumn('neto', function ($gastos) { 
                return "$ ".number_format($gastos->neto,2,".",",");
            })
            ->addColumn('ganancia', function ($gastos) { 
                return "$ ".number_format($gastos->ganancia,2,".",",");
            })
            ->addColumn('fecha', function ($gastos) { 
                return Carbon::parse($gastos->fecha)->format('d-m-Y');
            })
            ->addColumn('created_at', function ($gastos) { 
                return Carbon::parse($gastos->created_at)->format('d-m-Y');
            })
            ->addColumn('action', function ($gastos) use (&$charter, &$tipo) {
                $recibo = "";
                /*if($gastos->link_papeleta_pago != ""){
                    if($gastos->tipo_recibo == 'link'){
                        $recibo = '<a target="_blank" href='.$gastos->link_papeleta_pago.'><i class="fa fa-paperclip"></i> Recibo</a>';
                    }else{
                        $recibo = '<a target="_blank" href='.asset('images/charters/'.$charter->codigo.'/recibos/'.$gastos->link_papeleta_pago).'><i class="fa fa-paperclip"></i> Recibo</a>';
                    }
                    
                }*/
                return '<a href="#" onclick="editar_gasto('.$gastos->id.', \''.$tipo.'\')"><i class="fa fa-pencil fa-fw" title="Detalles"></i></a> <a href="#" onclick="eliminar_gasto('.$gastos->id.', \''.$tipo.'\')"><i class="fa fa-trash fa-fw"></i></a>'.$recibo;
            })
            ->make(true);
        }    
    }

    public function exportarPDF($id){
        $id_charter = decrypt($id);
        $charter = Charter::find($id_charter);
        $totales = $this->calcular_totales($charter);
        $pdf = PDF::loadView('comisiones.pdf.comisiones', ['charter' => $charter, 'totales' => $totales]);
        return $pdf->stream("resumen-".$charter->codigo.".pdf");
    }

    public function balance_socios(){
        $charters = Charter::all();
        $socios = Socio::all();
        return view('comisiones.balance_socios', ['charters' => $charters, 'socios' => $socios]);
    }

    public function delete($id){
        $id_charter = decrypt($id);

        $charter = Charter::find($id_charter);
        $descripcion = $charter->descripcion;
        $codigo_charter = $charter->codigo;
        if($charter->delete()){
            /*Storage::delete('public/images/charters/'.$codigo_charter);**/
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

    public function historial_acciones(Request $request){

        $historial = Historial::where('charters_id', '=', decrypt($request->charter_id))->where('item', '=', decrypt($request->item))->get();

        return Datatables::of($historial)
            ->addColumn('usuario', function ($historial) { 
                return $historial->user->name;
            })
            ->addColumn('comentario', function ($historial) { 
                return $historial->comentario;
            })
            ->addColumn('fecha', function ($historial) { 
                return Carbon::parse($historial->created_at)->format('d-m-Y h:i:s');
            })
            ->order(function ($query) {
                if (request()->has('fecha')) {
                    $query->orderBy('created_at', 'DESC');
                }
            })
            ->make(true);
    }

    public function eliminar_entrada($id_entrada){
        $entrada = Entrada::find($id_entrada);
        $monto_eliminado = $entrada->monto;
        $charter = $entrada->charters_id;
        $ob_charter = Charter::find($charter);

        if($entrada->delete()){
             
            $action_new = new Historial();
            $action_new->users_id = Auth::id();
            $action_new->item = 'ENTRADA';
            $action_new->charters_id = $charter;
            $action_new->accion = 'DELETE';
            $action_new->comentario = 'Entrada de $ '.number_format($monto_eliminado, 2, '.', ',').' eliminada. Fecha: '.date('d-m-Y');;
            $action_new->save();

            $msg = 'Entrada eliminada satisfactoriamente';
            $status = 'success';

            $totales = $this->calcular_totales($ob_charter);
        }else{
            $msg = 'No se pudo eliminar la entrada intente más tarde';
            $status = 'error';

        }
        return Response::json(['msg' => $msg, 'status' => $status, 'totales' => $totales]);
    }

    public function eliminar_gasto($id_gasto){
        $gasto = Gasto::find($id_gasto);
        $monto_eliminado = $gasto->neto;
        $charter = $gasto->charters_id;
        $categoria = $gasto->categoria;
        $ob_charter = Charter::find($charter);

        if($gasto->delete()){
             
            $action_new = new Historial();
            $action_new->users_id = Auth::id();
            $action_new->item = $categoria;
            $action_new->charters_id = $charter;
            $action_new->accion = 'DELETE';
            $action_new->comentario = 'Gasto de $ '.number_format($monto_eliminado, 2, '.', ',').' eliminado. Fecha: '.date('d-m-Y');;
            $action_new->save();

            $msg = 'Gasto eliminado satisfactoriamente';
            $status = 'success';

            $totales = $this->calcular_totales($ob_charter);

        }else{
            $msg = 'No se pudo eliminar el gasto intente más tarde';
            $status = 'error';

        }

        return Response::json(['msg' => $msg, 'status' => $status, 'totales' => $totales]);
    }

    public function edit_gasto($id){
        $gasto = Gasto::find($id);

        $registro = array();
        $registro["id"] = $gasto->id;
        $registro["charters_id"] = $gasto->charters_id;
        $registro["registrado_por"] = $gasto->registrado_por;
        $registro["razon_social"] = $gasto->razon_social;
        $registro["categoria"] = $gasto->categoria;
        $registro["precio_cliente"] = $gasto->precio_cliente;
        $registro["neto"] = $gasto->neto;
        $registro["ganancia"] = $gasto->ganancia;
        $registro["tipo_gasto_id"] = $gasto->tipo_gasto_id;
        $registro["fecha"] = Carbon::parse($gasto->fecha)->format('d-m-Y');
        $registro["comentario"] = $gasto->comentario;
        $registro["banco"] = $gasto->banco;
        $registro["referencia"] = $gasto->referencia;
        $registro["tipo_recibo"] = $gasto->tipo_recibo;
        $registro["link_papeleta_pago"] = $gasto->link_papeleta_pago;

        return Response::json(['gasto' => $registro]);
    }

    public function actualizar_gasto(Request $request){

        $gasto = Gasto::find($request->gasto["gasto_id"]);
        $charter = Charter::find($gasto->charters_id);
        $ganancia = 0;
        $recibo = null;

        if($request->gasto["precio_cliente"] != null){
            $ganancia = $request->gasto["precio_cliente"] - $request->gasto["neto"];
        }

        if($request->gasto["tipo_recibo"] == "archivo"){
            $directorio_images = 'images/charters/'.$charter->codigo;

            if(!File::exists(public_path($directorio_images))) {
                File::makeDirectory(public_path($directorio_images));

                if(!File::exists(public_path($directorio_images.'/recibos'))){
                    File::makeDirectory(public_path($directorio_images.'/recibos'));
                }
            }

            if(count($request->file()) > 0){
                if($request->file()['gasto']['archivo'] != null){
                    $recibo = $this->guardarImagen($request->file()['gasto']['archivo'], public_path($directorio_images.'/recibos'));    
                }else{
                    $recibo = "";
                }
            }else{
                $recibo = "";
            } 
        }

        else{
            $recibo = $request->gasto["link"];
        }

        $gasto->charters_id = $request->gasto["charter_id"];
        $gasto->registrado_por = Auth::id();
        $gasto->razon_social = $request->gasto["razon_social"];
        $gasto->precio_cliente = $request->gasto["precio_cliente"];
        $gasto->neto = $request->gasto["neto"];
        $gasto->ganancia = $ganancia;
        $gasto->fecha = Carbon::parse($request->gasto["fecha"])->format('Y-m-d');;
        $gasto->comentario = $request->gasto["comentario"];
        $gasto->banco = $request->gasto["banco"];
        $gasto->referencia = $request->gasto["referencia"];
        $gasto->tipo_recibo = $request->gasto["tipo_recibo"];
        $gasto->link_papeleta_pago = $recibo;

        if($gasto->save()){

            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->item = strtoupper($gasto->categoria);
            $new_action->charters_id = $charter->id;
            $new_action->accion = 'UPDATE';
            $new_action->comentario = 'Gasto de $ '.number_format($request->gasto["neto"], 2, '.', ',').' actualizado por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save();

            $totales = $this->calcular_totales($charter);

            $msg = 'Gasto actualizado exitosamente';
            $status = 'success';
        }else{
            $msg = "Ocurrió un error durante el registro del charter";
            $status = 'error';
        }

        return Response::json(array('msg' => $msg, 'status' => $status, 'totales' => $totales));
    }

    public function eliminar_abono_comision($id_comision, $id_abono){
        $abono = AbonosComisione::find($id_abono);
        $comision = $abono->comisione;
        $nuevo_abonado = $comision->abonado - $abono->monto;
        $nuevo_saldo = $comision->monto - $nuevo_abonado;
        $item = 'COMISION '.$abono->comisione->socio->nombre;
        $charter = Charter::find($abono->comisione->charters_id);
        $monto = $abono->monto;
        $comision->abonado = $nuevo_abonado;
        $comision->saldo = $nuevo_saldo;

        if($comision->save()){
            $abono->delete();
            $new_action = new Historial();
            $new_action->users_id = Auth::id();
            $new_action->charters_id = $charter->id;
            $new_action->item = $item;
            $new_action->accion = 'DELETE';
            $new_action->comentario = 'Eliminado abono de '.$monto.' por '.Auth::user()->name.'. Fecha: '.date('d-m-Y');
            $new_action->save(); 

            $nuevo_abonado = "$ ".number_format($nuevo_abonado, 2, ',', '.');
            $nuevo_saldo = "$ ".number_format($nuevo_saldo, 2, ',', '.');

            $msg = 'Abono eliminado satisfactoriamente';
            $status = 'success';
        }else{
            $msg = 'No se pudo eliminar el charter intente más tarde';
            $status = 'error';

        }

        $totales = $this->calcular_totales($charter);

        return Response::json(['msg' => $msg, 'status' => $status, 'totales' => $totales, 'abonado' => $nuevo_abonado, 'saldo' =>  $nuevo_saldo]);
    }

}