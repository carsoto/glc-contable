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
@if($charter->contrato != "Sin contrato")
    <br><span style="font-size: 12px;"><strong>CONTRATO:</strong> {{ asset('images/charters/'.$charter->codigo.'/contrato/'.$charter->contrato) }}</span>                         
@endif

<br><br>
<table class="table table-condensed table-bordered" style="font-size: 12px;">
<thead>
    <tr>
        <th>RESUMEN CONTABLE</th>
        <th>TOTAL</th>
        <th>GASTOS</th>
        <th>SALDO PENDIENTE</th>
    </tr>
</thead>
<tbody>
    @foreach($charter->gastos AS $key => $gasto)
        @if($gasto->tipo_gasto->descripcion == 'COMISIONES')
            <tr>
                <td><strong>{{ strtoupper($gasto->tipo_gasto->descripcion) }}</strong></td>
                <td>$ {{ number_format($gasto->total, 2, '.', ',') }}</td>
                <td><span id="resumen_gastos_{{ $gasto->tipo_gasto_id }}">{{ $salidas[$gasto->tipo_gasto_id]["gastos"] }}</span></td>
                <td><span id="resumen_saldo_{{ $gasto->tipo_gasto_id }}">{{ $salidas[$gasto->tipo_gasto_id]["saldo"] }}</span></td>
            </tr>

            @foreach($charter->comisiones AS $key => $comision)
                <tr style="background: gainsboro;">
                    <td>{{ $comision->socio->nombre }} ({{ $comision->socio->porcentaje }}%)</td>
                    <td>$ {{ number_format($comision->monto, 2, '.', ',') }}</td>
                    <td><span id="resumen_gastos_comision_{{ $comision->id }}">$ {{ number_format($comision->abonado, 2, '.', ',') }}</span></td>
                    <td><span id="resumen_saldo_comision_{{ $comision->id }}">$ {{ number_format($comision->saldo, 2, '.', ',') }}</span></td>
                </tr>
            @endforeach
        @else
            <tr>
                <td><strong>{{ strtoupper($gasto->tipo_gasto->descripcion) }}</strong></td>
                <td>$ {{ number_format($gasto->total, 2, '.', ',') }}</td>
                <td><span id="resumen_gastos_{{ $gasto->tipo_gasto_id }}">{{ $salidas[$gasto->tipo_gasto_id]["gastos"] }}</span></td>
                <td><span id="resumen_saldo_{{ $gasto->tipo_gasto_id }}">{{ $salidas[$gasto->tipo_gasto_id]["saldo"] }}</span></td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td><strong>{{ strtoupper('GLOBAL') }}</strong></td>
        <td>{{ $global['total'] }}</td>
        <td><span id="resumen_gastos_total">{{ $global['gastos'] }}</span></td>
        <td><span id="resumen_saldo_total">{{ $global['saldo'] }}</span></td>
    </tr>
</tbody>
</table>
 