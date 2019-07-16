@extends('adminlte::page')

@section('title', 'Comisiones Charters')

@section('content_header')
@stop

@section('content')
    <div class="box box-danger">
        <div class="box-header">
            <h3><a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i></a> {{ $charter->descripcion }}</h3>
        </div>
        <div class="box-body">
            <form role="form" enctype="multipart/form-data" id="editar-charter-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">YACHT</label>
                            <div class="form-group">
                                <input class="form-control input-sm" type="text" name="yacht" value="{{ $charter->yacht }}" readonly="readonly">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">YACHT RACK</label>
                            <input class="form-control input-sm" type="text" name="yacht_rack" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="yacht_rack" value="{{ $charter->yacht_rack }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">BROKER</label>
                            <input class="form-control input-sm" type="text" name="broker" value="{{ $charter->broker }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">F. INICIO</label>
                            <input class="form-control input-sm datepicker" type="text" name="fecha_inicio" autocomplete="off" value="{{ Carbon\Carbon::parse($charter->fecha_inicio)->format('d-m-Y') }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">F. FIN</label>
                            <input class="form-control input-sm datepicker" type="text" name="fecha_fin" autocomplete="off" value="{{ Carbon\Carbon::parse($charter->fecha_fin)->format('d-m-Y')}}" readonly="readonly">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">CHARTER RATE</label>
                            <input class="form-control input-sm" type="text" name="precio_venta" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision()" autocomplete="off" id="charter_rate" value="{{ $charter->precio_venta }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">NETO</label>
                            <input class="form-control input-sm" type="text" name="neto" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision()" autocomplete="off" id="charter_neto" value="{{ $charter->neto }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">% BROKER</label>
                            <input class="form-control input-sm" type="text" name="porcentaje_comision_broker" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision()" autocomplete="off" id="porcentaje_comision_broker" value="{{ $charter->porcentaje_comision_broker }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">COMISIÓN BROKER</label>
                            <input class="form-control input-sm" type="text" name="comision_broker" readonly="readonly" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision()" autocomplete="off" id="comision_broker" value="{{ $charter->comision_broker }}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">DELUXE COSTO</label>
                            <input class="form-control input-sm" type="text" name="costo_deluxe" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision()" autocomplete="off" id="costo_deluxe" value="{{ $charter->costo_deluxe }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">COMISIÓN GLC</label>
                            <input class="form-control input-sm" type="text" name="comision_glc" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision()" autocomplete="off" readonly="readonly" id="comision_glc" value="{{ $charter->comision_glc }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">APA</label>
                            <input class="form-control input-sm" type="text" name="apa" onKeyPress="return tipoNumeros(event)" autocomplete="off" value="{{ $charter->apa }}" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">NUEVO CONTRATO</label>
                            <div class="input-group input-file" name="contrato">
                                <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" value="{{ $charter->contrato }}" readonly="readonly"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($charter->contrato != "Sin contrato")
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">REVISAR CONTRATO</label>
                            <br><a target="_blank" href="{{ asset('images/charters/'.$charter->codigo.'/contrato/'.$charter->contrato) }}"><i class="fa fa-paperclip"></i> {{ $charter->contrato }}</a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="box with-nav-tabs box-danger">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a style="color: #E42223;" href="#entradas" data-toggle="tab">ENTRADAS</a></li>
                            <li><a style="color: #E42223;" href="#salidas" data-toggle="tab">SALIDAS</a></li>
                            <li><a style="color: #E42223;" href="#comisiones" data-toggle="tab">COMISIONES</a></li>
                            <li><a style="color: #E42223;" href="#resumen" data-toggle="tab">RESUMEN</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="entradas">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">TOTAL RECIBIDO:</span>  <span id="total_entrada">{{ $entradas["recibido"] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">TOTAL PENDIENTE:</span>  <span id="total_entrada_pendiente">{{ $entradas["pendiente"] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_entrada('{{ $charter->id }}')"><i class="fa fa-plus"></i> Nueva entrada</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-info" onclick="historial_entrada('{{ $charter->id }}')"><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table class="table table-condensed" style="font-size: 12px;" id="table-entradas" data-charter-id="{{ $charter->id }}">
                                            <thead>
                                                <th>REGISTRADO POR</th>
                                                <th>MONTO</th>
                                                <th>COMENTARIO</th>
                                                <th>F. DE ENTRADA</th>
                                                <th>F. DE REGISTRO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="salidas">
                                <!--<strong><span class="label label-danger" style="font-size: 12px;">GLOBAL:</span>  <span id="global_salida">$ 0.00</span></strong>-->
                                
                                <div class="col-lg-4 col-md-4 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">TOTAL RECIBIDO:</span>  <span id="total_recibido">{{ $entradas["recibido"] }}</span></strong>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">TOTAL GASTADO:</span>  <span id="total_gastos">{{ $global["gastos"] }}</span></strong>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table class="table table-bordered table-condensed" style="font-size: 12px;" id="table-salidas">
                                            <thead>
                                                <th>TIPO</th>
                                                <th>TOTAL</th>
                                                <th>GASTOS</th>
                                                <th>SALDO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                            <tbody>
                                                @foreach($charter->gastos AS $key => $gasto)
                                                    <tr>
                                                        <td><strong>{{ strtoupper($gasto->tipo_gasto->descripcion) }}</strong></td>
                                                        <td>$ {{ number_format($gasto->total, 2, '.', ',') }}</td>
                                                        <td><span id="salidas_gasto_{{ $gasto->id }}">{{ $salidas[$gasto->tipo_gasto_id]['gastos'] }}</span></td>
                                                        <td><span id="salidas_saldo_{{ $gasto->id }}">{{ $salidas[$gasto->tipo_gasto_id]['saldo'] }}</span></td>
                                                        @if(strtoupper($gasto->tipo_gasto->descripcion) != "COMISIONES")
                                                            <td><a href="#" data-target="modal" onclick="agregar_gasto('{{ $gasto->id }}')"><i class="fa fa-plus"></i></a> <a href="#" data-target="modal" onclick="historial_gastos('{{ $gasto->id }}')"><i class="fa fa-eye"></i></a> <a href="#" data-target="modal" onclick="historial_acciones_gastos('{{ $gasto->id }}')"><i class="fa fa-history"></i></a></td>
                                                        @else
                                                            <td>-</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="comisiones">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed" style="font-size: 12px;">
                                        <thead>
                                            <th>SOCIO</th>
                                            <th>MONTO</th>
                                            <th>ABONADO</th>
                                            <th>SALDO</th>
                                            <th>FECHA ÚLT. ABONO</th>
                                            <th><i class="fa fa-gears"></i></th>
                                        </thead>
                                        <tbody>
                                            @foreach($charter->comisiones AS $key => $comision)
                                                <tr>
                                                    <td><strong>{{ $comision->socio->nombre }} ({{ $comision->socio->porcentaje }}%)</strong></td>
                                                    <td>$ {{ number_format($comision->monto, 2, '.', ',') }}</td>
                                                    <td><span id="abonado_{{ $comision->id }}">$ {{ number_format($comision->abonado, 2, '.', ',') }}</span></td>
                                                    <td><span id="saldo_{{ $comision->id }}">$ {{ number_format($comision->saldo, 2, '.', ',') }}</span></td>
                                                    @if($comision->fecha_ult_abono != null)
                                                        <td><span id="fecha_ult_abono_{{ $comision->id }}">{{ Carbon\Carbon::parse($comision->fecha_ult_abono)->format('d-m-Y') }}</span></td>
                                                    @else
                                                        <td><span id="fecha_ult_abono_{{ $comision->id }}"></span></td>
                                                    @endif
                                                    <td><a href="#" data-target="modal" onclick="agregar_abono_comision('{{ $comision->id }}')"><i class="fa fa-plus"></i></a> <a href="#" data-target="modal" onclick="historial_abonos_comision('{{ $comision->id }}')"><i class="fa fa-eye"></i></a> <a href="#" data-target="modal" onclick="historial_acciones_abonos('{{ $comision->id }}')"><i class="fa fa-history"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="resumen">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered" style="font-size: 12px;">
                                        <thead>
                                            <th></th>
                                            <th>ENTRADA</th>
                                            <th>SALIDA</th>
                                            <th>SALDO</th>
                                        </thead>
                                        <tbody>
                                            @foreach($charter->gastos AS $key => $gasto)
                                                @if($gasto->tipo_gasto->descripcion == 'COMISIONES')
                                                    <!--<tr>
                                                        <td><strong>{{ strtoupper($gasto->tipo_gasto->descripcion) }}</strong></td>
                                                        <td>$ {{ number_format($gasto->total, 2, '.', ',') }}</td>
                                                        <td><span id="resumen_gastos_{{ $gasto->tipo_gasto_id }}">{{ $salidas[$gasto->tipo_gasto_id]["gastos"] }}</span></td>
                                                        <td><span id="resumen_saldo_{{ $gasto->tipo_gasto_id }}">{{ $salidas[$gasto->tipo_gasto_id]["saldo"] }}</span></td>
                                                    </tr>-->

                                                    @foreach($charter->comisiones AS $key => $comision)
                                                        <tr>
                                                            <td><strong>{{ strtoupper($comision->socio->nombre) }} ({{ $comision->socio->porcentaje }}%)</strong></td>
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
                                        </tbody>
                                        <tfoot>
                                            <tr style="background: grey; color: white;">
                                                <th><strong>{{ strtoupper('GLOBAL') }}</strong></th>
                                                <th>{{ $global['total'] }}</th>
                                                <th><span id="resumen_gastos_total">{{ $global['gastos'] }}</span></th>
                                                <th><span id="resumen_saldo_total" style="color: yellow;">{{ $global['saldo'] }}</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="text-right">
                    <a href="{{ route('admin.comisiones.charters.exportarPDF', ['id' => encrypt($charter->id)]) }}" target="_blank" class="btn btn-sm btn-flat btn-danger"><strong><i class="fa fa-file-pdf-o"></i> EXPORTAR A PDF</strong></a>
                </div>
            </form>
        </div>
    </div>

    <div id="nueva-entrada" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="nueva-entrada-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVA ENTRADA</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="entrada[charter_id]" id="id_charter">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FECHA</label>
                                <input class="form-control input-sm datepicker" type="text" name="entrada[fecha]" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">MONTO</label>
                                <input class="form-control input-sm" type="text" name="entrada[monto]" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="monto_entrada">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIO</label>
                                <textarea class="form-control input-sm" type="text" name="entrada[comentario]" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">BANCO</label>
                                    <input class="form-control input-sm" type="text" name="entrada[banco]" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">REFERENCIA</label>
                                    <input class="form-control input-sm" type="text" name="entrada[referencia]" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">PAPELETA DE PAGO</label>
                                <br>
                                <label>
                                    <div class="icheckbox">
                                        <input type="radio" name="entrada[tipo_recibo]" value="archivo" checked> Archivo
                                    </div> 
                                </label>
                                <label style="margin-left: 20px;">
                                    <div class="icheckbox">
                                        <input type="radio" name="entrada[tipo_recibo]" value="link"> Link
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12" id="recibo_tipo_archivo" style="display: block;">
                            <div class="form-group">
                                <label style="font-size: 11px;"></label>
                                <div class="input-group input-file" name="entrada[archivo]">
                                    <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12" id="recibo_tipo_link" style="display: none;">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;"></label>
                                    <input class="form-control input-sm" type="text" name="entrada[link]" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-flat btn-success submitBtn">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="editar-entrada" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="actualizar-entrada-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">EDITAR ENTRADA</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="entrada[id_entrada]" id="id_entrada">
                        <input class="form-control input-sm" type="hidden" name="entrada[id_charter]" id="charters-id">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FECHA</label>
                                <input class="form-control input-sm datepicker" type="text" name="entrada[fecha]" autocomplete="off" id="entrada-fecha">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">MONTO</label>
                                <input class="form-control input-sm" type="text" name="entrada[monto]" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="entrada-monto">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIO</label>
                                <textarea class="form-control input-sm" type="text" name="entrada[comentario]" style="resize: none;" id="entrada-comentario"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">BANCO</label>
                                    <input class="form-control input-sm" type="text" name="entrada[banco]" autocomplete="off" id="entrada-banco">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">REFERENCIA</label>
                                    <input class="form-control input-sm" type="text" name="entrada[referencia]" autocomplete="off" id="entrada-referencia">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">PAPELETA DE PAGO</label>
                                <br>
                                <label>
                                    <div class="icheckbox">
                                        <input type="radio" name="entrada[tipo_recibo]" value="archivo" id="entrada-tipo-r-archivo"> Archivo
                                    </div> 
                                </label>
                                <label style="margin-left: 20px;">
                                    <div class="icheckbox">
                                        <input type="radio" name="entrada[tipo_recibo]" value="link" id="entrada-tipo-r-link"> Link
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" id="entrada-tipo-archivo" style="display: block;">
                            <div class="form-group">
                                <label style="font-size: 11px;"></label>
                                <div class="input-group input-file" name="entrada[archivo]">
                                    <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" id="input-entrada-tipo-archivo"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" id="entrada-tipo-link" style="display: none;">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;"></label>
                                    <input class="form-control input-sm" type="text" name="entrada[link]" autocomplete="off" id="input-entrada-tipo-link">
                                </div>
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-flat btn-primary submitBtn">Actualizar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="abonos-comision" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="nuevo-abono-comision-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO ABONO</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="id_comision" id="id_comision">
                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FECHA</label>
                                <input class="form-control input-sm datepicker" type="text" name="abono_fecha" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">MONTO</label>
                                <input class="form-control input-sm" type="text" name="abono_monto" onKeyPress="return tipoNumeros(event)" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIO</label>
                                <textarea class="form-control input-sm" type="text" name="abono_comentario" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-flat btn-success submitBtn">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="historial-abonos-comision" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ABONOS</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tabla_hist_abonos_comisiones" class="table table-condensed table-bordered" style="font-size: 11px;">
                            <thead>
                                <th>Registrado por</th>
                                <th>Monto</th>
                                <th>F. de abono</th>
                                <th>Comentario</th>
                                <th>F. de registro</th>
                                <th><i class="fa fa-gears"></i></th>
                            </thead>
                        </table>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="nuevo-gasto" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="nuevo-gasto-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO GASTO</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="id_gasto" id="id_gasto">
                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FECHA</label>
                                <input class="form-control input-sm datepicker" type="text" name="gasto_fecha" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">MONTO</label>
                                <input class="form-control input-sm" type="text" name="gasto_monto" onKeyPress="return tipoNumeros(event)" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-offset-3 col-md-offset-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIO</label>
                                <textarea class="form-control input-sm" type="text" name="gasto_comentario" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-flat btn-success submitBtn">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="historial-gastos" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">GASTOS</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tabla_hist_gastos" class="table table-condensed table-bordered" style="font-size: 11px;">
                            <thead>
                                <th>Registrado por</th>
                                <th>Monto</th>
                                <th>F. de abono</th>
                                <th>Comentario</th>
                                <th>F. de registro</th>
                                <th><i class="fa fa-gears"></i></th>
                            </thead>
                        </table>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

@stop