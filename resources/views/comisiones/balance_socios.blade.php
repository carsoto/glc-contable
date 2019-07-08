@extends('adminlte::page')

@section('title', 'Balance Socios')

@section('content_header')
    <h1>BALANCE SOCIOS</h1>
@stop
@section('content')
    <div class="box box-danger">
    	<div class="box-body">
    		<div class="table-responsive">
    			<table class="table table-condensed table-bordered" style="font-size: 11px;">
    				<thead>
    					<tr>
                            <th style="vertical-align: middle;text-align:center;" rowspan="2">CHARTER</th>
	    					@foreach($socios AS $key => $socio)
	    						<th class="text-center" colspan="3">{{ $socio->nombre }} ({{ $socio->porcentaje }}%)</th>
	    					@endforeach
						</tr>
						<tr>
							@foreach($socios AS $key => $socio)
		    					<th class="text-center success" width="80px">Total</th>
		    					<th class="text-center warning" width="80px">Abono</th>
                                <th class="text-center danger" width="80px">Saldo</th>
                                <!-- MONTO - ABONO = SALDO -->	
	    					@endforeach
						</tr>
    				</thead>
                    <tbody>
                        @foreach($charters AS $key => $charter)

                            <tr>
                                <td><strong>{{ $charter->descripcion }}</strong></td>
                                @foreach($charter->comisiones AS $key1 => $comision)
                                    <td>$ {{ number_format($comision->monto, 2, '.', ',') }}</td>
                                    <td>$ {{ number_format($comision->abonado, 2, '.', ',') }}</td>
                                    <td>$ {{ number_format($comision->saldo, 2, '.', ',') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right">BALANCE FINAL</th>
                            @foreach($socios AS $clave => $socio)
                                <td>$ {{ number_format($socio->comisiones->sum('monto'), 2, '.', ',') }}</td>
                                <td>$ {{ number_format($socio->comisiones->sum('abonado'), 2, '.', ',') }}</td>
                                <td>$ {{ number_format($socio->comisiones->sum('saldo'), 2, '.', ',') }}</td>
                            @endforeach
                        </tr>
                    </tfoot>
    			</table>
    		</div>
    	</div>
    </div>
@stop