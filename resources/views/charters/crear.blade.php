@extends('adminlte::page')

@section('title', 'Crear Charter')

@section('content_header')
@stop

@section('content')
    <div class="box box-danger">
        <div class="box-header">
            <h3><a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-o-left"></i></a> NUEVO CHARTER </h3>
        </div>
        <div class="box-body">
            <form role="form" enctype="multipart/form-data" id="registrar-charter-form">
                {{ csrf_field() }}
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">F. INICIO</label>
                            <input class="form-control input-sm datepicker" type="text" name="fecha_inicio" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">F. FIN</label>
                            <input class="form-control input-sm datepicker" type="text" name="fecha_fin" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">CLIENTE</label>
                            <input class="form-control input-sm" type="text" name="cliente">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">BROKER</label>
                            <select class="form-control input-sm" name="broker">
                                <option value="0">SELECCIONAR BROKER</option>
                                @foreach($brokers AS $key => $broker)
                                    <option value="{{ $broker->id }}">{{ $broker->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label style="font-size: 11px;">
                            <input type="checkbox" name="nuevo_intermediario[check]" data-toggle="collapse" data-target="#nuevo_intermediario" value="1"><span class="label-text"> NUEVO BROKER</span>
                        </label>
                    </div>
                    
                    <div id="nuevo_intermediario" aria-expanded="false" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;">
                        <div class="row">
                            <div class="alert alert-danger alert-dismissible" role="alert" id="div_interm_error" hidden>
                                <button type="button" class="close" onclick="$('#div_interm_error').hide();" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <span id="detalles_interm_error"></span>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">NOMBRE Y APELLIDO <span style="color: red;">*</span></label>
                                    <input name="nuevo_intermediario[nombre]" id="interm_nombre" class="form-control input-sm" placeholder="Nombre y apellido">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">COMPAÑÍA <span style="color: red;">*</span></label>
                                    <input name="nuevo_intermediario[empresa]" id="interm_empresa" class="form-control input-sm" placeholder="Compañía">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">E-MAIL <span style="color: red;">*</span></label>
                                    <input name="nuevo_intermediario[email]" id="interm_email" class="form-control input-sm" placeholder="Correo">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">TELÉFONO <span style="color: red;">*</span></label>
                                    <input name="nuevo_intermediario[telefono]" id="interm_telef" class="form-control input-sm" placeholder="+593999999999">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">CHARTER RATE</label>
                            <input class="form-control input-sm" type="text" name="precio_venta" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_rate">
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
                            <input class="form-control input-sm" type="text" name="porcentaje_comision_broker" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="porcentaje_comision_broker">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">COMISIÓN BROKER</label>
                            <input class="form-control input-sm" type="text" name="comision_broker" readonly="readonly" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('crear')" autocomplete="off" id="comision_broker">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">APA</label>
                            <input class="form-control input-sm" type="text" name="apa" onKeyPress="return tipoNumeros(event)" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">PROGRAMA</label>
                            <select class="form-control input-sm" name="programa">
                                <option value="0">SELECCIONAR PROGRAMA</option>
                                @foreach($programas AS $key => $programa)
                                    <option value="{{ $programa->id }}">{{ $programa->desc_programa }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">ESTATUS</label>
                            <select class="form-control input-sm" name="status">
                                <option value="0">SELECCIONAR ESTATUS</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="CANCELADO">CANCELADO</option>
                                <option value="EJECUTADO">EJECUTADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label style="font-size: 11px;">ADJUNTAR CONTRATO</label>
                            <div class="input-group input-file" name="contrato">
                                <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf"/>
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
                                <option value="0">SELECCIONAR TIPO</option>
                                @foreach($tipos_patente AS $key => $tipo)
                                    @if($tipo->descripcion != 'Desconocida')
                                        <option value="{{ $tipo->id }}">{{ mb_strtoupper($tipo->descripcion) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="tipo-charter-info"></div>
                <div class="clearfix"></div>
                <div class="text-right" style="padding-top: 25px;"><button type="submit" class="btn btn-sm btn-flat btn-success submitBtn">Registrar</button></div>
            </form>
        </div>
    </div>
@stop