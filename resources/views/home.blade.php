@extends('adminlte::page')

@section('title', 'Galapagos Luxury Charters')

@section('content_header')
    <h1>DASHBOARD</h1>
@stop

@section('content')
    <div class="box box-danger">
    	<div class="box-header">
    		<div class="text-center">
    			<span style="font-size: 15px; color: #E42223; font-family: Arial, Helvetica, sans-serif;"><strong>VENTAS POR MESES</strong></span>
    		</div>
    	</div>
    	<div class="box-body">
				<canvas id="bar-chart-uno" data-render="chart-js"></canvas>
				<canvas id="bar-chart-dos" data-render="chart-js"></canvas>
    	</div>
    </div>
@stop