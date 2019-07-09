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

        foreach ($charters as $key => $charter) {
            if (!array_key_exists($charter->anyo, $ventas_por_mes)) {
                $ventas_por_mes[$charter->anyo] = array();
                $ganancias_monetarias[$charter->anyo] = array();
                for ($i=0; $i < 12; $i++) {
                    $ventas_por_mes[$charter->anyo][$i] = 0;
                    $ganancias_monetarias[$charter->anyo][$i] = 0;
                }
            }

            $ventas_por_mes[$charter->anyo][$charter->mes-1] = $ventas_por_mes[$charter->anyo][$charter->mes-1]+1;
            $totales = $controller_comisiones->calcular_totales($charter);
            
            $saldo = str_replace("$ ", "", $totales["global"]["saldo"]);
            $saldo = str_replace(",", "", $saldo);



            $ganancias_monetarias[$charter->anyo][$charter->mes-1] = $ganancias_monetarias[$charter->anyo][$charter->mes-1]+$saldo;
        }

/*"global" => array:3 [
"total" => "$ 157,400.00"
"gastos" => "$ 18,000.00"
"saldo" => "$ 139,400.00"
]*/
        return response()->json(['ganancias' => $ganancias_monetarias, 'ventas' => $ventas_por_mes]);
    }
}
