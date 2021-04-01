@extends('adminlte::page')

@section('title', 'Opciones Charter')

@section('content_header')
@stop

@section('content')
    <div class="box box-danger">
        <div class="box-header">
            <div class="text-right text-muted" style="float: absolute;">{{ strtoupper($charter->cliente) }}</div>
            <h3><a href="{{ route('admin.charters.index') }}"><i class="fa fa-arrow-circle-o-left"></i></a> {{ $charter->codigo }}</h3>
        </div>
        <div class="box-body">
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="panel-box bg-red">
                    <a href="{{ route('admin.editar-charter', ['id' => encrypt($charter->id) ]) }}">
                        <div class="box-header with-border">
                            <div class="inner">
                                <h3>EDITAR <div class="icon"><i class="fa fa-pencil"></i></div></h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="panel-box bg-red">
                    <a data-toggle="collapse" data-parent="#accordion" href="#opciones-logistica" aria-expanded="false" class="collapsed">
                        <div class="box-header with-border">
                            <div class="inner">
                                <h3>LOGÍSTICA <div class="icon"><i class="fa fa-list-ol"></i></div></h3>
                            </div>
                        </div>
                    </a>
                    <div id="opciones-logistica" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-box-footer">
                            <ul>
                                <li><a href="#"><i class="fa fa-file-text-o fa-1x"></i> Preference sheet</a></li>
                                <li><a href="#"><i class="fa fa-list-ul fa-1x"></i> Checklist</a></li>
                                <li><a href="#"><i class="fa fa-file-text fa-1x"></i> Diving</a></li>
                                <li><a href="#"><i class="fa fa-sliders fa-1x"></i> Extra activities</a></li>
                                <li><a href="#"><i class="fa fa-spinner fa-1x"></i> Ecuador extensions</a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart fa-1x"></i> Provisiones</a></li>
                                <li><a href="#"><i class="fa fa-paperclip fa-1x"></i> Procedimiento estándar</a></li>
                                <li><a href="#"><i class="fa fa-users fa-1x"></i> Staff</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="panel-box bg-red">
                    <a data-toggle="collapse" data-parent="#accordion" href="#opciones-apa" aria-expanded="false" class="collapsed">
                        <div class="box-header with-border">
                            <div class="inner">
                                <h3>APA <div class="icon"><i class="fa fa-tasks"></i></div></h3>
                            </div>
                        </div>
                    </a>
                    <div id="opciones-apa" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-box-footer">
                            <ul>
                                <li><a href="#"><i class="fa fa-eye fa-1x"></i> Check APA</a></li>
                                <li><a href="#"><i class="fa fa-upload fa-1x"></i> Cargar APA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="panel-box bg-red">
                    <a href="https://www.google.com">
                        <div class="box-header with-border">
                            <div class="inner">
                                <h3>GALERÍA <div class="icon"><i class="fa fa-picture-o"></i></div></h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop