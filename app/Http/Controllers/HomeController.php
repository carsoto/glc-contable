<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Charter;

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
        $ventas_por_mes = $ganancias_monetarias = array();
        $controller_comisiones = new ComisionesController();
        $charters = Charter::all();

        $ventas_por_mes[date('Y')-1] = array();
        $ventas_por_mes[date('Y')] = array();
        $ventas_por_mes[date('Y')+1] = array();
        $ventas_por_mes[date('Y')+2] = array();

        $ganancias_monetarias[date('Y')-1] = array();
        $ganancias_monetarias[date('Y')] = array();
        $ganancias_monetarias[date('Y')+1] = array();
        $ganancias_monetarias[date('Y')+2] = array();

        foreach ($ventas_por_mes as $key => $arr) {
            # code...
            for ($i=0; $i < 12; $i++) {
                $ventas_por_mes[$key][$i] = 0;
                $ganancias_monetarias[$key][$i] = 0;
            }
        }

        foreach ($charters as $key => $charter) {
            if(array_key_exists((int)$charter->anyo, $ventas_por_mes)){
               $ventas_por_mes[$charter->anyo][$charter->mes-1] = $ventas_por_mes[$charter->anyo][$charter->mes-1]+1;
                $totales = $controller_comisiones->calcular_totales($charter);
                
                $saldo = str_replace("$ ", "", $totales["global"]["saldo"]);
                $saldo = str_replace(",", "", $saldo);
                $ganancias_monetarias[$charter->anyo][$charter->mes-1] = $ganancias_monetarias[$charter->anyo][$charter->mes-1]+$saldo; 
            }
            
        }

        return response()->json(['ganancias' => $ganancias_monetarias, 'ventas' => $ventas_por_mes]);
    }
}
