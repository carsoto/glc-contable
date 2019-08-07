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
                            <li class="active"><a style="color: #E42223; font-size: 11px;" href="#entradas" data-toggle="tab"><strong>{{ strtoupper('entradas') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#broker" data-toggle="tab"><strong>{{ strtoupper('broker') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#operador" data-toggle="tab"><strong>{{ strtoupper('operador') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#deluxe" data-toggle="tab"><strong>{{ strtoupper('deluxe') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#comisiones" data-toggle="tab"><strong>{{ strtoupper('comisiones') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#apa" data-toggle="tab"><strong>{{ strtoupper('apa') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#other" data-toggle="tab"><strong>{{ strtoupper('other') }}</strong></a></li>
                            <li><a style="color: #E42223; font-size: 11px;" href="#resumen" data-toggle="tab"><strong>{{ strtoupper('resumen') }}</strong></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="entradas">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">TOTAL RECIBIDO:</span>  <span id="total_entradas">{{ $entradas["total"] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">TOTAL PENDIENTE:</span>  <span id="total_entradas_pendiente">{{ $entradas["saldo"] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_entrada('{{ $charter->id }}')"><i class="fa fa-plus"></i> Nueva entrada</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button data-toggle="modal" data-target="#historial-entradas" type="button" class="btn btn-sm btn-flat btn-block btn-info"><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table class="table table-condensed" style="font-size: 11px;" id="table-entradas" data-charter-id="{{ $charter->id }}" width="100%">
                                            <thead style="background: gainsboro;">
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

                            <div class="tab-pane fade" id="broker">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">TOTAL A PAGAR:</span>  <span id="total_broker">{{ $broker['total'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">TOTAL PENDIENTE:</span>  <span id="total_broker_pendiente">{{ $broker['saldo'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_gasto('{{ $charter->id }}', 'broker')"><i class="fa fa-plus"></i> Nuevo gasto</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-info" onclick="historial_gasto('{{ $charter->id }}', 'broker')" ><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table class="table table-condensed" style="font-size: 11px;" id="table-broker" data-charter-id="{{ $charter->id }}" width="100%">
                                            <thead style="background: gainsboro;">
                                                <th>REGISTRADO POR</th>
                                                <th>MONTO</th>
                                                <th>COMENTARIO</th>
                                                <th>F. DE REGISTRO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="operador">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">TOTAL A PAGAR:</span>  <span id="total_operador">{{ $operador['total'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">TOTAL PENDIENTE:</span>  <span id="total_operador_pendiente">{{ $operador['saldo'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_gasto('{{ $charter->id }}', 'operador')"><i class="fa fa-plus"></i> Nuevo gasto</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-info" onclick="historial_gasto('{{ $charter->id }}', 'operador')" ><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table id="table-operador" class="table table-condensed" style="font-size: 11px;" data-charter-id="{{ $charter->id }}" width="100%">
                                            <thead style="background: gainsboro;">
                                                <th>REGISTRADO POR</th>
                                                <th>MONTO</th>
                                                <th>COMENTARIO</th>
                                                <th>F. DE REGISTRO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="deluxe">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">DISPONIBLE:</span>  <span id="total_deluxe">{{ $deluxe['total'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">SALDO:</span>  <span id="total_deluxe_pendiente">{{ $deluxe['saldo'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_gasto('{{ $charter->id }}', 'deluxe')"><i class="fa fa-plus"></i> Nuevo gasto</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-info" onclick="historial_gasto('{{ $charter->id }}', 'deluxe')" ><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table class="table table-condensed" style="font-size: 11px;" id="table-deluxe" data-charter-id="{{ $charter->id }}" width="100%">
                                            <thead style="background: gainsboro;">
                                                <th>REGISTRADO POR</th>
                                                <th>MONTO</th>
                                                <th>COMENTARIO</th>
                                                <th>F. DE REGISTRO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="comisiones">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed" style="font-size: 11px;" width="100%">
                                        <thead style="background: gainsboro;">
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
                                                    <td>
                                                        <a href="#" data-target="modal" onclick="agregar_abono_comision('{{ $comision->id }}')"><i class="fa fa-plus"></i></a> 
                                                        <a href="#" data-target="modal" onclick="historial_abonos_comision('{{ $comision->id }}')"><i class="fa fa-eye"></i></a> 
                                                        <a href="#" data-toggle="modal" onclick="historial_acciones_abonos('{{ encrypt($charter->id) }}', '{{ encrypt('COMISION '.$comision->socio->nombre) }}')"><i class="fa fa-history"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="apa">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">TOTAL RECIBIDO:</span>  <span id="total_apa">{{ $apa['total'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">TOTAL GASTADO:</span>  <span id="total_apa_pendiente">{{ $apa['saldo'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_gasto('{{ $charter->id }}', 'apa')"><i class="fa fa-plus"></i> Nuevo gasto</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-info" onclick="historial_gasto('{{ $charter->id }}', 'apa')" ><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table class="table table-condensed" style="font-size: 11px;" id="table-apa" data-charter-id="{{ $charter->id }}" width="100%">
                                            <thead style="background: gainsboro;">
                                                <th>REGISTRADO POR</th>
                                                <th>DETALLE</th>
                                                <th>PRECIO CLIENTE</th>
                                                <th>NETO</th>
                                                <th>GANANCIA</th>
                                                <th>F. DE REGISTRO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="other">
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-success" style="font-size: 12px;">DISPONIBLE:</span>  <span id="total_other">{{ $other['total'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <strong><span class="label label-danger" style="font-size: 12px;">SALDO:</span>  <span id="total_other_pendiente">{{ $other['saldo'] }}</span></strong>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-success" onclick="agregar_gasto('{{ $charter->id }}', 'other')"><i class="fa fa-plus"></i> Nuevo gasto</button>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 10px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-block btn-info" onclick="historial_gasto('{{ $charter->id }}', 'other')" ><i class="fa fa-history"></i> Historial</button>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive" style="margin-top: 20px;">
                                        <table id="table-other" class="table table-condensed" style="font-size: 11px;" data-charter-id="{{ $charter->id }}" width="100%">
                                            <thead style="background: gainsboro;">
                                                <th>REGISTRADO POR</th>
                                                <th>DETALLE</th>
                                                <th>PRECIO CLIENTE</th>
                                                <th>NETO</th>
                                                <th>GANANCIA</th>
                                                <th>F. DE REGISTRO</th>
                                                <th><i class="fa fa-gears"></i></th>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="resumen">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered" style="font-size: 12px;" width="100%">
                                        <thead style="background: gainsboro;">
                                            <th></th>
                                            <th>TOTAL</th>
                                            <th>ABONADO</th>
                                            <th>SALDO</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>BROKER</strong></td>
                                                <td><span id="resumen_broker_entrada">{{ $broker['total'] }}</span></td>
                                                <td><span id="resumen_broker_salida">{{ $broker['gastos'] }}</span></td>
                                                <td><span id="resumen_broker_saldo">{{ $broker['saldo'] }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>OPERADOR</strong></td>
                                                <td><span id="resumen_operador_entrada">{{ $operador['total'] }}</span></td>
                                                <td><span id="resumen_operador_salida">{{ $operador['gastos'] }}</span></td>
                                                <td><span id="resumen_operador_saldo">{{ $operador['saldo'] }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>DELUXE</strong></td>
                                                <td><span id="resumen_deluxe_entrada">{{ $deluxe['total'] }}</span></td>
                                                <td><span id="resumen_deluxe_salida">{{ $deluxe['gastos'] }}</span></td>
                                                <td><span id="resumen_deluxe_saldo">{{ $deluxe['saldo'] }}</span></td>
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
                                                <td><span id="resumen_apa_entrada">{{ $apa['total'] }}</span></td>
                                                <td><span id="resumen_apa_salida">{{ $apa['gastos'] }}</span></td>
                                                <td><span id="resumen_apa_saldo">{{ $apa['saldo'] }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>OTHER</strong></td>
                                                <td><span id="resumen_other_entrada">{{ $other['total'] }}</span></td>
                                                <td><span id="resumen_other_salida">{{ $other['gastos'] }}</span></td>
                                                <td><span id="resumen_other_saldo">{{ $other['saldo'] }}</span></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background: grey; color: white;">
                                                <th><strong>{{ strtoupper('GLOBAL') }}</strong></th>
                                                <th><span id="resumen_global_entrada">{{ $global['total'] }}</span></th>
                                                <th><span id="resumen_global_salida">{{ $global['gastos'] }}</span></th>
                                                <th><span id="resumen_global_saldo" style="color: yellow;">{{ $global['saldo'] }}</span></th>
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
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">TIPO</label>
                            <select class="form-control input-sm" name="entrada[tipo_gasto]">
                                <option value="">SELECCIONAR GASTO</option>
                                @foreach($tipos_gastos AS $key => $gasto)
                                    <option value="{{ $gasto->id }}">{{ $gasto->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
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

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">TIPO</label>
                                <select class="form-control input-sm" name="entrada[tipo_gasto]" id="entrada-tipo-gasto">
                                    <option value="">SELECCIONAR GASTO</option>
                                    @foreach($tipos_gastos AS $key => $gasto)
                                        <option value="{{ $gasto->id }}">{{ $gasto->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
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

    <div id="historial-entradas" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">HISTORIAL ENTRADAS</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-historial-entradas" class="table table-condensed table-bordered" data-item="{{ encrypt('ENTRADA') }}" data-charter-id="{{ encrypt($charter->id) }}" style="font-size: 11px;" width="100%">
                            <thead>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
                            </thead>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="nuevo-gasto" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="nuevo-gasto-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO GASTO</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="gasto[charter_id]" id="id_charter" value="{{ $charter->id }}">
                        <input class="form-control input-sm" type="hidden" name="gasto[categoria]" id="categoria_gasto">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FECHA</label>
                                <input class="form-control input-sm datepicker" type="text" name="gasto[fecha]" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">RAZÓN SOCIAL</label>
                                    <input class="form-control input-sm" type="text" name="gasto[razon_social]" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" style="display: none;" id="gasto_precio_cliente">
                            <div class="form-group">
                                <label style="font-size: 11px;">PRECIO CLIENTE</label>
                                <input class="form-control input-sm" type="text" name="gasto[precio_cliente]" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="monto_gasto">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">NETO</label>
                                <input class="form-control input-sm" type="text" name="gasto[neto]" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="monto_gasto">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">BANCO</label>
                                    <input class="form-control input-sm" type="text" name="gasto[banco]" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">REFERENCIA</label>
                                    <input class="form-control input-sm" type="text" name="gasto[referencia]" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">PAPELETA DE PAGO</label>
                                <br>
                                <label>
                                    <div class="icheckbox">
                                        <input type="radio" name="gasto[tipo_recibo]" value="archivo" id="gasto-tipo-r-archivo" checked> Archivo
                                    </div> 
                                </label>
                                <label style="margin-left: 20px;">
                                    <div class="icheckbox">
                                        <input type="radio" name="gasto[tipo_recibo]" value="link" id="gasto-tipo-r-link"> Link
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" id="gasto-tipo-archivo" style="display: block;">
                            <div class="form-group">
                                <label style="font-size: 11px;"></label>
                                <div class="input-group input-file" name="gasto[archivo]">
                                    <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" id="input-gasto-tipo-archivo"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" id="gasto-tipo-link" style="display: none;">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;"></label>
                                    <input class="form-control input-sm" type="text" name="gasto[link]" autocomplete="off" id="input-gasto-tipo-link">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIO</label>
                                <textarea class="form-control input-sm" type="text" name="gasto[comentario]" style="resize: none;"></textarea>
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

    <div id="editar-gasto" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="editar-gasto-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO GASTO</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="gasto[gasto_id]" id="id_gasto">
                        <input class="form-control input-sm" type="hidden" name="gasto[charter_id]" id="id_charter" value="{{ $charter->id }}">
                        <input class="form-control input-sm" type="hidden" name="gasto[categoria]" id="categoria_gasto">
                        <input class="form-control input-sm" type="hidden" name="gasto[tipo_gasto_id]" id="tipo_gasto_id">
                        
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FECHA</label>
                                <input class="form-control input-sm datepicker" type="text" name="gasto[fecha]" autocomplete="off" id="gasto_fecha">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">RAZÓN SOCIAL</label>
                                    <input class="form-control input-sm" type="text" name="gasto[razon_social]" autocomplete="off" id="gasto_razon_social">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" style="display: none;" id="edit_gasto_precio_cliente">
                            <div class="form-group">
                                <label style="font-size: 11px;">PRECIO CLIENTE</label>
                                <input class="form-control input-sm" type="text" name="gasto[precio_cliente]" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="gasto_monto_precio_cliente">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">NETO</label>
                                <input class="form-control input-sm" type="text" name="gasto[neto]" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="gasto_monto_neto">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">BANCO</label>
                                    <input class="form-control input-sm" type="text" name="gasto[banco]" autocomplete="off" id="gasto_banco">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;">REFERENCIA</label>
                                    <input class="form-control input-sm" type="text" name="gasto[referencia]" autocomplete="off" id="gasto_referencia">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">PAPELETA DE PAGO</label>
                                <br>
                                <label>
                                    <div class="icheckbox">
                                        <input type="radio" name="gasto[tipo_recibo]" value="archivo" id="edit-gasto-tipo-r-archivo" checked> Archivo
                                    </div> 
                                </label>
                                <label style="margin-left: 20px;">
                                    <div class="icheckbox">
                                        <input type="radio" name="gasto[tipo_recibo]" value="link" id="edit-gasto-tipo-r-link"> Link
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" id="edit-gasto-tipo-archivo" style="display: block;">
                            <div class="form-group">
                                <label style="font-size: 11px;"></label>
                                <div class="input-group input-file" name="gasto[archivo]">
                                    <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" id="edit-input-gasto-tipo-archivo"/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                    </span>
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12" id="edit-gasto-tipo-link" style="display: none;">
                            <div class="form-group">
                                <div class="form-group">
                                    <label style="font-size: 11px;"></label>
                                    <input class="form-control input-sm" type="text" name="gasto[link]" autocomplete="off" id="edit-input-gasto-tipo-link">
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIO</label>
                                <textarea class="form-control input-sm" type="text" name="gasto[comentario]" style="resize: none;" id="gasto_comentario"></textarea>
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
                        <table id="tabla_hist_gastos" class="table table-condensed table-bordered" style="font-size: 11px;" width="100%">
                            <thead>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
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
                        <table id="tabla_hist_abonos_comisiones" class="table table-condensed table-bordered" style="font-size: 11px;" width="100%">
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

    <div id="historial-comisiones" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">HISTORIAL COMISIONES</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-historial-comisiones" class="table table-condensed table-bordered" style="font-size: 11px;" width="100%">
                            <thead>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
                            </thead>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@stop