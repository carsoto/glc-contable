@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>PEDIDOS</h1>
@stop

@section('content')
    <div class="box box-danger">
    	<div class="box-header">
            <div class="hidden-xs hidden-sm">
                <div style="float: left;">
                    <button class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#registrarPedido"><i class="fa fa-plus"></i> Nuevo pedido</button>
                    <button class="btn btn-sm btn-info btn-flat" data-toggle="modal" onclick="historial_pedidos()"><i class="fa fa-history"></i> Historial</button>
                </div>
                <!--<div style="float: right;">
                    <button class="btn btn-sm btn-danger btn-flat" data-toggle="modal" onclick="historial_status_pedidos('inactivo')"><i class="fa fa-ban"></i> Pedidos inactivos</button>
                    <button class="btn btn-sm btn-primary btn-flat" data-toggle="modal" onclick="historial_status_pedidos('vendido')"><i class="fa fa-check"></i> Pedidos vendidos</button>
                </div>-->
            </div>
            <div class="hidden-md hidden-lg">
                <button class="btn btn-block btn-sm btn-success btn-flat" data-toggle="modal" data-target="#registrarPedido"><i class="fa fa-plus"></i> Nuevo pedido</button>
                <!--<button class="btn btn-block btn-sm btn-danger btn-flat" data-toggle="modal" onclick="historial_status_pedidos('inactivo')"><i class="fa fa-ban"></i> Pedidos inactivos</button>
                <button class="btn btn-block btn-sm btn-primary btn-flat" data-toggle="modal" onclick="historial_status_pedidos('vendido')"><i class="fa fa-check"></i> Pedidos vendidos</button>-->
                <button class="btn btn-block btn-sm btn-info btn-flat" data-toggle="modal" onclick="historial_pedidos()"><i class="fa fa-history"></i> Historial</button>
            </div>
    	</div>
    	<div class="box-body">
    		<div class="table-responsive">
    			<table class="table table-striped table-condensed table-bordered" style="font-size: 11px;" width="100%" id="tabla_pedidos">
    				<thead>
                        <tr>
                            <th style="vertical-align:middle; text-align:center;">Fecha</th>
                            <th style="vertical-align:middle; text-align:center;">Compañía</th>
                            <th style="vertical-align:middle; text-align:center;">Solicitante</th>
                            <th style="vertical-align:middle; text-align:center;">F. Inicio</th>
                            <th style="vertical-align:middle; text-align:center;">F. Fin</th>
                            <th style="vertical-align:middle; text-align:center;">Próximo contacto</th>
                            <th style="vertical-align:middle; text-align:center;">Status</th>
                            <th><i class="fa fa-gears"></i></th>
                        </tr>
    				</thead>
    			</table>
    		</div>
    	</div>
    </div>

    <!-- Modal -->
    <div id="registrarPedido" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="registrar-pedido-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO PEDIDO</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">DATE OF INQUIRY</label>
                                <input class="form-control input-sm datepicker" type="text" name="fecha_pedido" autocomplete="off" id="create_pedido_fecha">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">TYPE OF CONTACT</label>
                                <div class="form-group">
                                    <select class="form-control input-sm" type="text" name="tipo_contacto" autocomplete="off">
                                        @foreach($tipos_contacto AS $key => $contacto)
                                        <option value="{{ $contacto->id }}">{{ $contacto->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMPANY</label>
                                <div class="form-group">
                                    <input class="form-control input-sm" type="text" name="company">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">NAME</label>
                                <div class="form-group">
                                    <input class="form-control input-sm" type="text" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">PHONE</label>
                                <input class="form-control input-sm" type="text" name="phone" onKeyPress="return tipoNumeros(event)" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">EMAIL</label>
                                <input class="form-control input-sm" type="text" name="email" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">START REQUESTED</label>
                                <input class="form-control input-sm datepicker" type="text" name="date_start" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FINISH REQUESTED</label>
                                <input class="form-control input-sm datepicker" type="text" name="date_finish" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">DETAILS</label>
                                <textarea class="form-control input-sm" type="text" name="details" autocomplete="off" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">NEXT FOLLOW UP</label>
                                <input class="form-control input-sm datepicker" type="text" name="next_follow" autocomplete="off" id="create_next_follow">
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

    <div id="editarPedido" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="actualizar-pedido-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">EDITAR PEDIDO</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="pedido_id" autocomplete="off" id="pedido_id">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">DATE OF INQUIRY</label>
                                <input class="form-control input-sm datepicker" type="text" name="fecha_pedido" autocomplete="off" id="pedido_fecha">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">TYPE OF CONTACT</label>
                                <div class="form-group">
                                    <select class="form-control input-sm" type="text" name="tipo_contacto" autocomplete="off" id="pedido_contacto">
                                        @foreach($tipos_contacto AS $key => $contacto)
                                            <option value="{{ $contacto->id }}">{{ $contacto->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">COMPANY</label>
                                <div class="form-group">
                                    <input class="form-control input-sm" type="text" name="company" id="pedido_company">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">NAME</label>
                                <div class="form-group">
                                    <input class="form-control input-sm" type="text" name="name" id="pedido_name">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">PHONE</label>
                                <input class="form-control input-sm" type="text" name="phone" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="pedido_phone">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">EMAIL</label>
                                <input class="form-control input-sm" type="text" name="email" autocomplete="off" id="pedido_email">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">START REQUESTED</label>
                                <input class="form-control input-sm datepicker" type="text" name="date_start" autocomplete="off" id="pedido_date_start">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">FINISH REQUESTED</label>
                                <input class="form-control input-sm datepicker" type="text" name="date_finish" autocomplete="off" id="pedido_date_finish">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">DETAILS</label>
                                <textarea class="form-control input-sm" type="text" name="details" autocomplete="off" style="resize: none;" id="pedido_details"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">STATUS</label>
                                <div class="form-group">
                                    <select class="form-control input-sm" type="text" name="pedido_status" autocomplete="off" id="pedido_status">
                                        @foreach($statuses AS $key => $status)
                                            <option value="{{ $status->id }}">{{ $status->descripcion }}</option>
                                        @endforeach
                                    </select>
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

    <div id="seguimientosPedido" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="seguimiento-pedido-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">SEGUIMIENTOS</h4>
                    </div>
                    <div class="modal-body">
                        <input class="form-control input-sm" type="hidden" name="pedido_id" autocomplete="off" id="seguimiento_pedido_id">
                        <input class="form-control input-sm" type="hidden" name="seguimiento_id" autocomplete="off" id="seguimiento_id">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">DATE</label>
                                <input class="form-control input-sm datepicker" type="text" name="fecha_seguimiento" autocomplete="off" id="fecha_seguimiento">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label style="font-size: 11px;">DETAILS</label>
                                <textarea class="form-control input-sm" type="text" name="details_seguimiento" autocomplete="off" style="resize: none;" id="details_seguimiento"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="hidden-lg hidden-md" style="padding-bottom: 15px;"><button type="submit" class="btn btn-block btn-sm btn-flat btn-success submitBtn seguimientoBtn"><i class="fa fa-plus"></i> ADD</button></div>
                            <div class="hidden-sm hidden-xs" style="padding-top: 40px;"><button type="submit" class="btn btn-block btn-sm btn-flat btn-success submitBtn seguimientoBtn"><i class="fa fa-plus"></i> ADD</button></div>
                            
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-condensed table-bordered" style="font-size: 11px;" width="100%" id="tabla_seguimientos">
                                    <thead>
                                        <tr>
                                            <th style="vertical-align:middle; text-align:center;">DATE</th>
                                            <th style="vertical-align:middle; text-align:center;">USER</th>
                                            <th style="vertical-align:middle; text-align:center;">DETAILS</th>
                                            <th><i class="fa fa-gears"></i></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="historial-pedidos" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">HISTORIAL</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-pedidos-eliminados" style="font-size: 12px;" width="100%">
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

    <div id="estatus-pedidos" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="hist-estatus-pedidos-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed table-bordered" style="font-size: 11px;" width="100%" id="tabla_pedidos_por_estatus">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle; text-align:center;">Fecha</th>
                                    <th style="vertical-align:middle; text-align:center;">Compañía</th>
                                    <th style="vertical-align:middle; text-align:center;">Solicitante</th>
                                    <th style="vertical-align:middle; text-align:center;">F. Inicio</th>
                                    <th style="vertical-align:middle; text-align:center;">F. Fin</th>
                                    <th style="vertical-align:middle; text-align:center;">Próximo contacto</th>
                                    <th style="vertical-align:middle; text-align:center;">Status</th>
                                    <th><i class="fa fa-gears"></i></th>
                                </tr>
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