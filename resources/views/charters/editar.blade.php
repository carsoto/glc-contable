@extends('adminlte::page')

@section('title', 'Editar Charter')

@section('content_header')
@stop

@section('content')
    <div class="box box-danger">
        <div class="box-header">
            <h3><a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i></a> EDITAR CHARTER </h3>
        </div>
        <div class="box-body">
            <form role="form" enctype="multipart/form-data" id="actualizar-charter-form">
                {{ csrf_field() }}
                    <input type="hidden" name="id_charter" value="{{ encrypt($charter->id) }}">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">F. INICIO</label>
                            <input class="form-control input-sm datepicker" type="text" name="fecha_inicio" autocomplete="off" value="{{ Carbon\Carbon::parse($charter->fecha_inicio)->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">F. FIN</label>
                            <input class="form-control input-sm datepicker" type="text" name="fecha_fin" autocomplete="off" value="{{ Carbon\Carbon::parse($charter->fecha_fin)->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">CLIENTE</label>
                            <input class="form-control input-sm" type="text" name="cliente" value="{{ strtoupper($charter->cliente) }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">BROKER</label>
                            <select class="form-control input-sm" name="broker">
                                <option value="0">SELECCIONAR BROKER</option>
                                @foreach($brokers AS $key => $broker)
                                    @if($charter->brokers_id == $broker->id)
                                    <option value="{{ $broker->id }}" selected="selected">{{ $broker->nombre }}</option>
                                    @else
                                    <option value="{{ $broker->id }}">{{ $broker->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">CHARTER RATE</label>
                            <input class="form-control input-sm" type="text" name="precio_venta" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_rate" value="{{ $charter->precio_venta }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">% BROKER</label>
                            <input class="form-control input-sm" type="hidden" name="charter_neto" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_neto" value="0">
                            <input class="form-control input-sm" type="hidden" name="costo_deluxe" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="costo_deluxe" value="0">
                            <input class="form-control input-sm" type="hidden" name="comision_glc" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="comision_glc" value="0">
                            <input class="form-control input-sm" type="hidden" name="charter_comision_broker" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_comision_broker" value="0">
                            <input class="form-control input-sm" type="hidden" name="charter_comision_glc" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_comision_glc" value="0">
                            <input class="form-control input-sm" type="text" name="porcentaje_comision_broker" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="porcentaje_comision_broker" value="{{ $charter->porcentaje_comision_broker }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">COMISIÓN BROKER</label>
                            <input class="form-control input-sm" type="text" name="comision_broker" readonly="readonly" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="comision_broker" value="{{ $charter->comision_broker }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">APA</label>
                            <input class="form-control input-sm" type="text" name="apa" onKeyPress="return tipoNumeros(event)" autocomplete="off" value="{{ $charter->apa }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">PROGRAMA</label>
                            <select class="form-control input-sm" name="programa">
                                <option value="0">SELECCIONAR PROGRAMA</option>
                                @foreach($programas AS $key => $programa)
                                    @if($charter->programa_id == $programa->id)
                                        <option value="{{ $programa->id }}" selected="selected">{{ $programa->desc_programa }}</option>
                                    @else
                                        <option value="{{ $programa->id }}" selected="selected">{{ $programa->desc_programa }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">ESTATUS</label>
                            <select class="form-control input-sm" name="status">
                                {!! 
                                    $activo = $pendiente = $cancelado = $ejecutado = "";

                                    if($charter->status == 'ACTIVO'){
                                        $activo = 'selected';
                                    }else if($charter->status == 'CANCELADO'){
                                        $cancelado = 'selected';
                                    }else if($charter->status == 'EJECUTADO'){
                                        $ejecutado = 'selected';
                                    }else{
                                        $pendiente = 'selected';
                                    }
                                !!}
                                <option value="0" {{ $pendiente }}>SELECCIONAR ESTATUS</option>
                                <option value="ACTIVO" {{ $activo }}>ACTIVO</option>
                                <option value="CANCELADO" {{ $cancelado }}>CANCELADO</option>
                                <option value="EJECUTADO" {{ $ejecutado }}>EJECUTADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">ADJUNTAR CONTRATO</label>
                            <div class="input-group input-file" name="contrato">
                                <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" value="{{ $charter->contrato }}"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">TIPO CHARTER</label>
                            <br>
                            <select class="form-control input-sm" name="tipo_charter" id="select_tipo_charter">
                                @if($tipo_charter == 0)
                                    <option value="0" selected="selected">SELECCIONAR TIPO</option>
                                @else
                                    <option value="0">SELECCIONAR TIPO</option>
                                @endif
                                @foreach($tipos_patente AS $key => $tipo)
                                    @if($tipo->descripcion != 'Desconocida')
                                        @if($tipo_charter == $tipo->id)
                                            <option value="{{ $tipo->id }}" selected="selected">{{ mb_strtoupper($tipo->descripcion) }}</option>
                                        @else
                                            <option value="{{ $tipo->id }}">{{ mb_strtoupper($tipo->descripcion) }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="tipo-charter-info-select"></div>
                    <div id="tipo-charter-info">
                        @if(count($charter_embarcacion) > 0)
                            @foreach($charter_embarcacion AS $key => $e)

                            <div class="col-lg-12 col-md-12 col-sm-12"><label style="font-size: 11px;">CHARTER {{ $e['patente'] }}</label></div>
                            <div class="col-lg-6 col-md-6 col-sm-12" style="padding-bottom: 10px;">
                                <label style="font-size: 11px;">EMBARCACIÓN</label>
                                <br>
                                <select class="form-control input-sm" name="embarcacion[{{ $e['id'] }}]" id="select_embarcacion_{{ $e['patente'] }}" onchange="cargar_itinerario(this, {{ $key }}, '{{ $e['patente'] }}')">
                                    <option value="0">SELECCIONAR EMBARCACIÓN</option>
                                    @foreach($e['embarcaciones'] AS $key_em => $em)
                                        @if($em->id == $e['embarcacion_selected'])
                                        <option value="{{ $em->id }}" selected="selected">{{ $em->nombre }}</option>
                                        @else
                                        <option value="{{ $em->id }}">{{ $em->nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12" style="padding-bottom: 10px;">
                                <label style="font-size: 11px;">ITINERARIO</label>
                                <br>
                                <select class="form-control input-sm select_itinerario_{{ $e['patente'] }}" name="itinerario[{{ $e['id'] }}]">
                                    <option value="0">SELECCIONAR ITINERARIO</option>
                                    @foreach($e['itinerarios'] AS $key_it => $it)
                                        @if($key_it == $e['itinerario_selected'])
                                        <option value="{{ $key_it }}" selected="selected">{{ $it }}</option>
                                        @else
                                        <option value="{{ $key_it }}">{{ $it }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @endforeach
                        @endif
                    </div>
                <div class="clearfix"></div>
                <div class="text-right" style="padding-top: 25px;"><button type="submit" class="btn btn-sm btn-flat btn-primary submitBtn">Actualizar</button></div>
            </form>
        </div>
    </div>
@stop