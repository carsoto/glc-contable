@extends('adminlte::page')

@section('title', 'Galapagos Luxury Charters')

@section('content_header')
    <h1>DASHBOARD</h1>
@stop

@section('content')
    <div class="box box-danger">
    	<div class="box-body">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="text-center">
                    <span style="font-size: 15px; color: #E42223; font-family: Arial, Helvetica, sans-serif;"><strong>VENTAS</strong></span>
                </div>
                <hr>
                <canvas id="ventas-por-meses" data-render="chart-js"></canvas>    
            </div>
            <div class="hidden-lg"><hr></div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="text-center">
                    <span style="font-size: 15px; color: #E42223; font-family: Arial, Helvetica, sans-serif;"><strong>GANANCIAS</strong></span>
                </div>
                <hr>
                <canvas id="ganancias-por-meses" data-render="chart-js"></canvas>    
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="text-center">
                    <span style="font-size: 15px; color: #E42223; font-family: Arial, Helvetica, sans-serif;"><strong>PEDIDOS</strong></span>
                </div>
                <hr>
                <canvas id="pedidos-por-meses" data-render="chart-js"></canvas>    
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="text-center">
                    <span style="font-size: 15px; color: #E42223; font-family: Arial, Helvetica, sans-serif;"><strong>BALANCE DE PEDIDOS</strong></span>
                </div>
                <hr>
                <canvas id="pedidos-por-status" data-render="chart-js"></canvas>    
            </div>
    	</div>
    </div>
@stop