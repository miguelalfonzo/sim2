@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12" style="">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitudes" role="tab" data-toggle="tab">
                    <i class="fa fa-home"></i>
                    Solicitudes
                </a>
            </li>
            @include('template.li_estado_cuenta')
            <li>
                <a href="#fondos" role="tab" data-toggle="tab">
                    <i class="fa fa-user"></i>
                    Fondos Institucionales
                </a>
            </li>
            <li>
                <a href="#mantenimiento" role="tab" data-toggle="tab">
                    <i class="fa fa-user"></i>
                    Mantenimiento de Documentos
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Solicitudes -->
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default" style="margin-top: 10px">
       	        @include('template.searchmenu')       
     	        </div>
            </div>
            @include('template.tb_estado_cuenta')
            <!-- Fondos -->
            <div class="tab-pane fade" id="fondos">
                <div class="panel panel-default" style="margin-top: 10px">
                    <div class="panel-body panel-default table_solicitude_fondos-contabilidad table-solicituds-fondos-contabilidad">
                        <div id="" class="form-group col-xs-6 col-sm-3 col-md-3">
        					<div class="input-group">
        						<input type="text" class="form-control date_month" style="background-color:#FFF" data-type="fondos-contabilidad" readonly>
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </span>
        					</div>
                        </div>
                        <div id="" class="form-group col-xs-6 col-sm-3 col-md-3">
                            <select id="estado_fondo_cont" class="form-control">
                                <option value="1">DEPOSITADO</option>
                                <option value="2">REGISTRADO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@stop