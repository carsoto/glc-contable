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
            <form role="form" enctype="multipart/form-data" id="crear-charter-form">
                {{ csrf_field() }}
                

                <div class="clearfix"></div>
            </form>
        </div>
    </div>
@stop