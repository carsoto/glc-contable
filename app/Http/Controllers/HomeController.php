<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Charter;
use App\Pedido;
use Funciones;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard(){
        $ventas_por_mes = $ganancias_monetarias = $pedidos_por_mes = $pedidos_status = array();
        $controller_comisiones = new ContabilidadController();
        $charters = Charter::all();
        $pedidos = Pedido::all();

        $ventas_por_mes[date('Y')-1] = $ventas_por_mes[date('Y')] = $ventas_por_mes[date('Y')+1] = $ventas_por_mes[date('Y')+2] = array();

        $ganancias_monetarias[date('Y')-1] = $ganancias_monetarias[date('Y')] = $ganancias_monetarias[date('Y')+1] = $ganancias_monetarias[date('Y')+2] = array();

        $pedidos_por_mes[date('Y')-1] = $pedidos_por_mes[date('Y')] = $pedidos_por_mes[date('Y')+1] = $pedidos_por_mes[date('Y')+2] = array();

        $e_pedidos_por_mes[date('Y')-1] = $e_pedidos_por_mes[date('Y')] = $e_pedidos_por_mes[date('Y')+1] = $e_pedidos_por_mes[date('Y')+2] = array();

        foreach ($ventas_por_mes as $key => $arr) {
            # code...
            for ($i=0; $i < 12; $i++) {
                $ventas_por_mes[$key][$i] = 0;
                $ganancias_monetarias[$key][$i] = 0;
                $pedidos_por_mes[$key][$i] = 0;
                $e_pedidos_por_mes[$key][$i] = 0;
            }
        }

        foreach ($charters as $key => $charter) {
            if(array_key_exists((int)$charter->anyo, $ventas_por_mes)){
               $ventas_por_mes[$charter->anyo][$charter->mes-1] = $ventas_por_mes[$charter->anyo][$charter->mes-1]+1;
                $totales = Funciones::calcular_totales($charter);
                
                $saldo = str_replace("$ ", "", $totales["global"]["saldo"]);
                $saldo = str_replace(",", "", $saldo);
                $ganancias_monetarias[$charter->anyo][$charter->mes-1] = $ganancias_monetarias[$charter->anyo][$charter->mes-1]+$saldo; 
            }
            
        }

        foreach ($pedidos as $key => $pedido) {
            $mes = Carbon::parse($pedido->f_inicio)->format('m');
            $anyo = Carbon::parse($pedido->f_inicio)->format('Y');
            $mes = $mes - 1;
            $pedidos_por_mes[$anyo][$mes] = $pedidos_por_mes[$anyo][$mes]+1;

            $e_mes = Carbon::parse($pedido->created_at)->format('m');
            $e_anyo = Carbon::parse($pedido->created_at)->format('Y');
            $e_mes = $e_mes - 1;
            $e_pedidos_por_mes[$e_anyo][$e_mes] = $e_pedidos_por_mes[$e_anyo][$e_mes]+1;

            if(array_key_exists($pedido->pedidos_status->descripcion, $pedidos_status)){
                $pedidos_status[$pedido->pedidos_status->descripcion] = $pedidos_status[$pedido->pedidos_status->descripcion]+1;    
            }else{
                $pedidos_status[$pedido->pedidos_status->descripcion] = 1;
            }
        }

        return response()->json(['ganancias' => $ganancias_monetarias, 'ventas' => $ventas_por_mes, 'pedidos' => $pedidos_por_mes, 'pedidos_entrantes' => $e_pedidos_por_mes, 'pedidos_status' => $pedidos_status]);
    }
}
