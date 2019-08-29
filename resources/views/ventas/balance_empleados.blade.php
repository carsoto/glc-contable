@extends('adminlte::page')

@section('title', 'Comisiones Empleados')

@section('content_header')
    <h1>COMISIONES EMPLEADOS</h1>
@stop
@section('content')
    <div class="box box-danger">
    	<div class="box-body">
    		<div class="table-responsive">
    			<table class="table table-striped table-condensed table-bordered" style="font-size: 11px;">
    				<thead>
    					<tr>
                            <th style="vertical-align: middle;text-align:center;" rowspan="2">CHARTER</th>
	    					@foreach($socios AS $key => $socio)
	    						<th class="text-center" colspan="3">{{ $socio->nombre }} ({{ $socio->porcentaje }}%)</th>
	    					@endforeach
						</tr>
						<tr>
							@foreach($socios AS $key => $socio)
		    					<th class="text-center success" width="80px">Comisión</th>
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
                                @foreach($socios AS $key => $socio)
                                    @foreach($charter->comisiones AS $key1 => $comision)
                                        @if($comision->socios_id == $socio->id)
                                            <td>$ {{ number_format($comision->monto, 2, '.', ',') }}</td>
                                            <td>$ {{ number_format($comision->abonado, 2, '.', ',') }}</td>
                                            <td>$ {{ number_format($comision->saldo, 2, '.', ',') }}</td>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: grey; color: white;">
                            <th class="text-right">BALANCE FINAL</th>
                            @foreach($socios AS $clave => $socio)
                                @if($socio->comisiones != null)
                                    <th>$ {{ number_format($socio->comisiones->sum('monto'), 2, '.', ',') }}</th>
                                    <th>$ {{ number_format($socio->comisiones->sum('abonado'), 2, '.', ',') }}</th>
                                    <th>$ {{ number_format($socio->comisiones->sum('saldo'), 2, '.', ',') }}</th>
                                @else
                                    <th>$ {{ number_format(0, 2, '.', ',') }}</th>
                                    <th>$ {{ number_format(0, 2, '.', ',') }}</th>
                                    <th>$ {{ number_format(0, 2, '.', ',') }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </tfoot>
    			</table>
    		</div>
    	</div>
    </div>
@stop