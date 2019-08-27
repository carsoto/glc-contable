@extends('adminlte::page')

@section('title', 'Comisiones Charters')

@section('content_header')
    <h1>CHARTERS</h1>
@stop

@section('content')
    <div class="box box-danger">
    	<div class="box-header">
    		<button class="btn btn-sm btn-success btn-flat" data-toggle="modal" data-target="#registrarCharter"><i class="fa fa-plus"></i> Nuevo charter</button>
            <button class="btn btn-sm btn-info btn-flat" data-toggle="modal" data-target="#historial-charter"><i class="fa fa-history"></i> Historial</button>
    	</div>
    	<div class="box-body">
    		<div class="table-responsive">
    			<table class="table table-striped table-condensed table-bordered" style="font-size: 11px;" id="tabla_charters">
    				<thead>
    					<tr>
                            <th style="vertical-align: middle; text-align:center;">CÓDIGO</th>
                            <th style="vertical-align: middle; text-align:center;">BROKER</th>
                            <th style="vertical-align: middle; text-align:center;">CLIENTE</th>
                            <th style="vertical-align: middle; text-align:center;">YACHT</th>
                            <th style="vertical-align: middle; text-align:center;">F. INICIO</th>
                            <th style="vertical-align: middle; text-align:center;">F. FIN</th>
	    					<th style="vertical-align: middle; text-align:center;">PATENTE</th>
	    					<th style="vertical-align: middle; text-align:center;">PROGRAMA</th>
                            <th style="vertical-align: middle; text-align:center;">ESTATUS</th>
						</tr>
    				</thead>
    			</table>
    		</div>
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