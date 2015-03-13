@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12" style="">
        <!-- Default panel contents -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Solicitudes Gerente Producto</h3>
                <small style="float: right; margin-top: -10px">
                    <strong>Usuario : {{Auth::user()->Gerprod->descripcion}}</strong>
                </small>
            </div>
            @include('template/searchmenu')
            <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
            <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#" >Ocultar leyenda</a>
        </div>
    </div>
</div>
@include('template/leyenda')
@stop
