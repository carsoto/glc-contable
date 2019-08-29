@extends('adminlte::page')

@section('title', 'VENTAS')

@section('content_header')
    <h1>VENTAS</h1>
@stop

@section('content')
    <div class="box box-danger">
    	<div class="box-header">
    		<button class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#registrarCharter"><i class="fa fa-plus"></i> Nuevo charter</button>
            <button class="btn btn-sm btn-info btn-flat" data-toggle="modal" data-target="#historial-charter"><i class="fa fa-history"></i> Historial</button>
    	</div>
    	<div class="box-body">
    		<div class="table-responsive">
    			<table class="table table-striped table-condensed table-bordered" style="font-size: 11px;" id="tabla_comisiones">
    				<thead>
    					<tr>
                            <th style="vertical-align: middle;text-align:center;" rowspan="2" width="90px">CLIENTE</th>
                            <th style="vertical-align: middle;text-align:center;" rowspan="2" width="90px">F. INICIO</th>
                            <th style="vertical-align: middle;text-align:center;" rowspan="2" width="90px">F. FIN</th>
	    					<th style="vertical-align: middle;text-align:center;" rowspan="2" width="100px">YACHT</th>
	    					<th style="vertical-align: middle;text-align:center;" rowspan="2" width="80px">VENTA</th>
                            <th class="text-center" colspan="3">DELUXE</th>
                            <th class="text-center" colspan="3">GLOBAL</th>
	    					<!--@foreach($socios AS $key => $socio)
	    						<th class="text-center" colspan="3">{{ $socio->nombre }} ({{ $socio->porcentaje }}%)</th>
	    					@endforeach-->
	    					<th style="vertical-align: middle;text-align:center;" rowspan="2"><i class="fa fa-gears"></i></th>
						</tr>
						<tr>
                            <th class="text-center success" width="80px">Total</th>
                            <th class="text-center warning" width="80px">Gastos</th>
                            <th class="text-center danger" width="80px">Saldo</th>
                            <th class="text-center success" width="80px">Ingreso</th>
                            <th class="text-center warning" width="80px">Salida</th>
                            <th class="text-center danger" width="80px">Saldo</th>
							<!--@foreach($socios AS $key => $socio)
		    					<th class="text-center success" width="80px">Total</th>
		    					<th class="text-center warning" width="80px">Abono</th>
                                <th class="text-center danger" width="80px">Saldo</th>
                                <!-- MONTO - ABONO = SALDO --	
	    					@endforeach-->
						</tr>
    				</thead>
    			</table>
    		</div>
    	</div>
    </div>

    <!-- Modal -->
    <div id="registrarCharter" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="registrar-charter-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">NUEVO CHARTER</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        	<div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">YACHT</label>
                                    <div class="form-group">
                                        <input class="form-control input-sm" type="text" name="yacht">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">BROKER</label>
                                    <input class="form-control input-sm" type="text" name="broker">
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
                                    <label style="font-size: 11px;">YACHT RACK</label>
                                    <input class="form-control input-sm" type="text" name="yacht_rack" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="yacht_rack">
                                </div>
                            </div>
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
                                    <label style="font-size: 11px;">CHARTER RATE</label>
                                    <input class="form-control input-sm" type="text" name="precio_venta" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_rate">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">NETO</label>
                                    <input class="form-control input-sm" type="text" name="neto" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('crear')" autocomplete="off" id="charter_neto">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">% BROKER</label>
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
                                    <label style="font-size: 11px;">DELUXE COSTO</label>
                                    <input class="form-control input-sm" type="text" name="costo_deluxe" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('crear')" autocomplete="off" value="0" id="costo_deluxe">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">COMISIÓN GLC</label>
                                    <input class="form-control input-sm" type="text" name="comision_glc" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('crear')" autocomplete="off" readonly="readonly" id="comision_glc">
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

    <div id="editarCharter" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form role="form" enctype="multipart/form-data" id="actualizar-charter-form">
            {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">EDITAR CHARTER</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input class="form-control input-sm" type="hidden" name="id_charter" id="id_charter">
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">YACHT</label>
                                    <div class="form-group">
                                        <input class="form-control input-sm" type="text" name="yacht" readonly="readonly" id="charter_yacht">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">BROKER</label>
                                    <input class="form-control input-sm" type="text" name="broker" id="charter_broker">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">CLIENTE</label>
                                    <input class="form-control input-sm" type="text" name="cliente" id="charter_cliente">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">YACHT RACK</label>
                                    <input class="form-control input-sm" type="text" name="yacht_rack" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="charter_yacht_rack">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">F. INICIO</label>
                                    <input class="form-control input-sm datepicker" type="text" name="fecha_inicio" autocomplete="off" id="charter_f_inicio">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">F. FIN</label>
                                    <input class="form-control input-sm datepicker" type="text" name="fecha_fin" autocomplete="off" id="charter_f_fin">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">CHARTER RATE</label>
                                    <input class="form-control input-sm" type="text" name="precio_venta" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('editar')" autocomplete="off" id="charter_precio_rate">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">NETO</label>
                                    <input class="form-control input-sm" type="text" name="neto" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('editar')" autocomplete="off" id="charter_precio_neto">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">% BROKER</label>
                                    <input class="form-control input-sm" type="text" name="porcentaje_comision_broker" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('editar')" autocomplete="off" id="charter_porcentaje_comision_broker">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">COMISIÓN BROKER</label>
                                    <input class="form-control input-sm" type="text" name="comision_broker" readonly="readonly" onKeyPress="return tipoNumeros(event);" onKeyUp="calcular_comision('editar')" autocomplete="off" id="charter_comision_broker">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">DELUXE COSTO</label>
                                    <input class="form-control input-sm" type="text" name="costo_deluxe" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('editar')" autocomplete="off" id="charter_costo_deluxe">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">COMISIÓN GLC</label>
                                    <input class="form-control input-sm" type="text" name="comision_glc" onKeyPress="return tipoNumeros(event)" onKeyUp="calcular_comision('editar')" autocomplete="off" readonly="readonly" id="charter_comision_glc">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">APA</label>
                                    <input class="form-control input-sm" type="text" name="apa" onKeyPress="return tipoNumeros(event)" autocomplete="off" id="charter_apa">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label style="font-size: 11px;">ADJUNTAR CONTRATO</label>
                                    <div class="input-group input-file" name="contrato">
                                        <input type="type" class="form-control input-sm" placeholder='.pdf' accept=".pdf" id="charter_contrato" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary btn-choose btn-sm" type="button"><i class="fa fa-paperclip"></i></button>
                                        </span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger btn-reset btn-sm" type="button"><i class="fa fa-refresh"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="charter_contrato_view"></div>
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

    <div id="historial-charter" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">HISTORIAL</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-charters-eliminados" style="font-size: 12px;" width="100%">
                            <thead>
                                <th>Usuario</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
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