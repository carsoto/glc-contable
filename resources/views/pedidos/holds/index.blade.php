@extends('adminlte::page')

@section('title', 'Holds')

@section('content_header')
    <h1>HOLDS</h1>
@stop

@section('content')
    <div class="box box-danger">
    	<div class="box-header">
            <div class="hidden-xs hidden-sm">
                <div style="float: left;">
                    <button class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#registrarHold"><i class="fa fa-plus"></i> Nuevo hold</button>
                    <button class="btn btn-sm btn-info btn-flat" data-toggle="modal" onclick="historial_holds()"><i class="fa fa-history"></i> Historial</button>
                </div>
            </div>
            <div class="hidden-md hidden-lg">
                <button class="btn btn-block btn-sm btn-success btn-flat" data-toggle="modal" data-target="#registrarHold"><i class="fa fa-plus"></i> Nuevo hold</button>
                <button class="btn btn-block btn-sm btn-info btn-flat" data-toggle="modal" onclick="historial_holds()"><i class="fa fa-history"></i> Historial</button>
            </div>
    	</div>
    	<div class="box-body">
    		<div class="table-responsive">
    			<table class="table table-striped table-condensed table-bordered" style="font-size: 11px;" width="100%" id="tabla_holds">
    				<thead>
                        <tr>
                            <th style="vertical-align:middle; text-align:center;">F. Inicio Charter</th>
                            <th style="vertical-align:middle; text-align:center;">F. Fin Charter</th>
                            <th style="vertical-align:middle; text-align:center;">Yacht</th>
                            <th style="vertical-align:middle; text-align:center;">Itinerario</th>
                            <th style="vertical-align:middle; text-align:center;">Tarifa</th>
                            <th style="vertical-align:middle; text-align:center;">F. Límite</th>
                            <th style="vertical-align:middle; text-align:center;">Estatus</th>
                            <th><i class="fa fa-gears"></i></th>
                        </tr>
    				</thead>
    			</table>
    		</div>
    	</div>
    </div>

    <!-- Modal -->
    <div id="registrarHold" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="registrar-hold-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO HOLD</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">F. INICIO CHARTER</label>
                                <input class="form-control input-sm datepicker" type="text" name="f_inicio_charter" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">F. FIN CHARTER</label>
                                <input class="form-control input-sm datepicker" type="text" name="f_fin_charter" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">ESTATUS</label>
                                <select class="form-control input-sm" name="status">
                                    <option value="">SELECCIONAR ESTATUS</option>
                                    <option value="BLOQUEADO">BLOQUEADO</option>
                                    <option value="LISTA DE ESPERA">LISTA DE ESPERA</option>
                                    <option value="EXTENSIÓN">EXTENSIÓN</option>
                                    <option value="ANULADO">ANULADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">YACHT</label>
                                <select class="form-control input-sm" name="yacht" onchange="cargar_itinerario(this, 0, '')">
                                    <option value="0">SELECCIONAR BARCO</option>
                                    @foreach($embarcaciones AS $key => $yacht)
                                        <option value="{{ $yacht->id }}">{{ strtoupper($yacht->nombre) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">ITINERARIO</label>
                                <select class="form-control input-sm select_itinerario_" name="itinerario_yacht">
                                    <option value="0">SELECCIONAR ITINERARIO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">TARIFA</label>
                                <input class="form-control input-sm" type="text" onKeyPress="return tipoNumeros(event)" name="tarifa_yacht">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIOS</label>
                                <textarea class="form-control input-sm" type="text" name="comentario" autocomplete="off" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">F. LÍMITE</label>
                                <input class="form-control input-sm datepicker" type="text" name="f_limite" autocomplete="off">
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

    <div id="editarHold" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="actualizar-hold-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">EDITAR HOLD</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="hold_id" autocomplete="off" id="hold_id">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">F. INICIO CHARTER</label>
                                <input class="form-control input-sm datepicker" type="text" name="f_inicio_charter" autocomplete="off" id="hold_f_inicio_charter">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">F. FIN CHARTER</label>
                                <input class="form-control input-sm datepicker" type="text" name="f_fin_charter" autocomplete="off" id="hold_f_fin_charter">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">ESTATUS</label>
                                <select class="form-control input-sm" name="status" id="hold_status">
                                    <option value="">SELECCIONAR ESTATUS</option>
                                    <option value="BLOQUEADO">BLOQUEADO</option>
                                    <option value="LISTA DE ESPERA">LISTA DE ESPERA</option>
                                    <option value="EXTENSIÓN">EXTENSIÓN</option>
                                    <option value="ANULADO">ANULADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">YACHT</label>
                                <select class="form-control input-sm" name="yacht" onchange="cargar_itinerario(this, 0, 'hold')" id="hold_yacht">
                                    <option value="0">SELECCIONAR BARCO</option>
                                    @foreach($embarcaciones AS $key => $yacht)
                                        <option value="{{ $yacht->id }}">{{ strtoupper($yacht->nombre) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">ITINERARIO</label>
                                <select class="form-control input-sm select_itinerario_hold" name="itinerario_yacht" id="hold_itinerario_yacht">
                                    <option value="0">SELECCIONAR ITINERARIO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">TARIFA</label>
                                <input class="form-control input-sm" type="text" onKeyPress="return tipoNumeros(event)" name="tarifa_yacht" id="hold_tarifa_yacht">
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMENTARIOS</label>
                                <textarea class="form-control input-sm" type="text" name="comentario" autocomplete="off" style="resize: none;" id="hold_comentario"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">F. LÍMITE</label>
                                <input class="form-control input-sm datepicker" type="text" name="f_limite" autocomplete="off" id="hold_f_limite">
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

    <div id="historial-holds" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">HISTORIAL</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-historial-holds" style="font-size: 12px;" width="100%">
                            <thead>
                                <th>Usuario</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                            </thead>
                            <tbody></tbody>
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