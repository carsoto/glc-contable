<link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
<!--<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.css') }}">-->
<div class="text-center">
    <img src="{{ asset('images/logo-glc.PNG') }}" width="250px">
</div>
<div class="text-right">
    <span style="font-size: 14px;"><strong>CHARTER: {{ $charter->descripcion }}</strong></span>
</div>
<hr>

<span style="font-size: 12px;"><strong>YACHT:</strong> {{ $charter->yacht }}</span>
<br><span style="font-size: 12px;"><strong>YACHT RACK:</strong> $ {{ number_format($charter->yacht_rack, 2, '.', ',') }}</span>
<br><span style="font-size: 12px;"><strong>BROKER:</strong> {{ $charter->broker }}</span>
<br><span style="font-size: 12px;"><strong>CHARTER RATE:</strong> $ {{ number_format($charter->precio_venta, 2, '.', ',') }}</span>
<br><span style="font-size: 12px;"><strong>NETO:</strong> $ {{ number_format($charter->neto, 2, '.', '.') }}</span>
<br><span style="font-size: 12px;"><strong>TOTAL RECIBIDO:</strong> {{ $totales['entradas']['total'] }}</span>
@if($charter->contrato != "Sin contrato")
    <br><span style="font-size: 12px;"><strong>CONTRATO:</strong> {{ asset('images/charters/'.$charter->codigo.'/contrato/'.$charter->contrato) }}</span>                         
@endif

<br><br>

<table class="table table-condensed table-bordered" style="font-size: 12px;" width="100%">
    <thead style="background: gainsboro;">
        <tr>
            <th>RESUMEN CONTABLE</th>
            <th>TOTAL</th>
            <th>ABONADO</th>
            <th>SALDO</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>BROKER</strong></td>
            <td><span id="resumen_broker_entrada">{{ $totales['broker']['total'] }}</span></td>
            <td><span id="resumen_broker_salida">{{ $totales['broker']['gastos'] }}</span></td>
            <td><span id="resumen_broker_saldo">{{ $totales['broker']['saldo'] }}</span></td>
        </tr>
        <tr>
            <td><strong>OPERADOR</strong></td>
            <td><span id="resumen_operador_entrada">{{ $totales['operador']['total'] }}</span></td>
            <td><span id="resumen_operador_salida">{{ $totales['operador']['gastos'] }}</span></td>
            <td><span id="resumen_operador_saldo">{{ $totales['operador']['saldo'] }}</span></td>
        </tr>
        <tr>
            <td><strong>DELUXE</strong></td>
            <td><span id="resumen_deluxe_entrada">{{ $totales['deluxe']['total'] }}</span></td>
            <td><span id="resumen_deluxe_salida">{{ $totales['deluxe']['gastos'] }}</span></td>
            <td><span id="resumen_deluxe_saldo">{{ $totales['deluxe']['saldo'] }}</span></td>
        </tr>
        @foreach($charter->comisiones AS $key => $comision)
            <tr>
                <td><strong>{{ strtoupper($comision->socio->nombre) }} ({{ $comision->socio->porcentaje }}%)</strong></td>
                <td>$ {{ number_format($comision->monto, 2, '.', ',') }}</td>
                <td><span id="resumen_gastos_comision_{{ $comision->id }}">$ {{ number_format($comision->abonado, 2, '.', ',') }}</span></td>
                <td><span id="resumen_saldo_comision_{{ $comision->id }}">$ {{ number_format($comision->saldo, 2, '.', ',') }}</span></td>
            </tr>
        @endforeach
        <tr>
            <td><strong>APA</strong></td>
            <td><span id="resumen_apa_entrada">{{ $totales['apa']['total'] }}</span></td>
            <td><span id="resumen_apa_salida">{{ $totales['apa']['gastos'] }}</span></td>
            <td><span id="resumen_apa_saldo">{{ $totales['apa']['saldo'] }}</span></td>
        </tr>
        <tr>
            <td><strong>OTHER</strong></td>
            <td><span id="resumen_other_entrada">{{ $totales['other']['total'] }}</span></td>
            <td><span id="resumen_other_salida">{{ $totales['other']['gastos'] }}</span></td>
            <td><span id="resumen_other_saldo">{{ $totales['other']['saldo'] }}</span></td>
        </tr>
    </tbody>
    <tfoot>
        <tr style="background: grey; color: white;">
            <th><strong>{{ strtoupper('GLOBAL') }}</strong></th>
            <th><span id="resumen_global_entrada">{{ $totales['global']['total'] }}</span></th>
            <th><span id="resumen_global_salida">{{ $totales['global']['gastos'] }}</span></th>
            <th><span id="resumen_global_saldo" style="color: yellow;">{{ $totales['global']['saldo'] }}</span></th>
        </tr>
    </tfoot>
</table>
 