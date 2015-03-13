@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12" style="">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitudes" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon> Solicitudes Rep. Medicos
                </a>
            </li>
            <li>
                <a href="#fondos" role="tab" data-toggle="tab">
                    <i class="fa fa-user"></i>
                    Fondos Institucionales
                </a>
            </li>
        </ul>
        <div class="tab-content" style="margin-top: .75em;">
            <!-- Solicitudes -->
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default">
                    @include('template/searchmenu') 
                    <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
                    <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#" >Ocultar leyenda</a>
                </div>
            </div>
            <!-- Fondos -->
            <div class="tab-pane fade" id="fondos">
                <div class="panel panel-default">
                    <div class="panel-body table_fondos_rm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('template/leyenda')
@stop