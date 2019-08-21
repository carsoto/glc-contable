<?php

namespace App\Helpers;

class Funciones{
    
    public static function guardarImagen($archivo, $directorio){
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
        $global_gastos = 0;
        $comisiones = $charter->comisiones;

        foreach ($comisiones as $key => $comision) {
            $global_gastos += $comision->abonado;
        }

        $saldo_entrada = $charter->precio_venta + $charter->apa;
        $saldo = $saldo_entrada - $charter->entradas->sum('monto');

        $entrada['total'] = "$ ".number_format($charter->entradas->sum('monto'), 2, '.', ',');
        $entrada['saldo'] = "$ ".number_format($saldo_entrada - $charter->entradas->sum('monto'), 2, '.', ',');

        $broker['total'] = $charter->comision_broker;
        $broker['gastos'] = $charter->gastos->where('categoria', '=', 'BROKER')->sum('neto');
        $broker['cliente'] = $charter->gastos->where('categoria', '=', 'BROKER')->sum('precio_cliente');
        $broker['ganancia'] = $charter->gastos->where('categoria', '=', 'BROKER')->sum('ganancia');
        $broker['saldo'] = $broker['total'] - $broker['gastos'];

        $global_gastos += $broker['gastos'];

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

        $global_gastos += $operador['gastos'];

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

        $global_gastos += $deluxe['gastos'];

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

        $global_gastos += $apa['gastos'];

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

        $global_gastos += $other['gastos'];

        $other['total'] = "$ ".number_format($other['total'], 2, '.', ',');
        $other['gastos'] = "$ ".number_format($other['gastos'], 2, '.', ',');
        $other['cliente'] = "$ ".number_format($other['cliente'], 2, '.', ',');
        $other['ganancia'] = "$ ".number_format($other['ganancia'], 2, '.', ',');
        $other['saldo'] = "$ ".number_format($other['saldo'], 2, '.', ',');

        $global['total'] = $charter->entradas->sum('monto') + $saldo;
        $global['gastos'] = $global_gastos;
        $global['saldo'] = $global['total'] - $global_gastos;

        $global['total'] = "$ ".number_format($global['total'], 2, '.', ',');
        $global['gastos'] = "$ ".number_format($global['gastos'], 2, '.', ',');
        $global['saldo'] = "$ ".number_format($global['saldo'], 2, '.', ',');

        return array('entradas' => $entrada, 'broker' => $broker, 'operador' => $operador, 'deluxe' => $deluxe, 'apa' => $apa, 'other' => $other,'global' => $global);
    }
}