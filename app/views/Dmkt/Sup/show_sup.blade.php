@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12" style="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Solicitudes Supervisor</h3>
                <small style="float: right; margin-top: -10px">
                    <strong>Usuario : {{Auth::user()->Sup->nombres}}</strong>
                </small>
            </div>
            <input type="hidden" value="{{Auth::user()->type}}" id="idUser">
            @include('template/searchmenu')
            <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
            <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#" >Ocultar leyenda</a>
        </div>
    </div>
</div>
@include('template/leyenda')
@stop
