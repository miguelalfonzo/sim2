@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitudes" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon>
                    Solicitudes
                </a>
            </li>
            @include('template.User.li_estado_cuenta')
            @if ( Auth::user()->type == ASIS_GER )
                <li>
                    <a href="#fondos" role="tab" data-toggle="tab">
                        <icon class="fa fa-home"></icon>
                        Registrar Fondos
                    </a>
                </li>
            @elseif( Auth::user()->type == CONT )
                <li>
                    <a href="#documentos" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Documentos
                    </a>
                </li>
                <li>
                    <a href="#mantenimiento" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Mantenimiento de Documentos
                    </a>
                </li>
                <li>
                    <a href="#fondo-cuenta" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Mantenimiento de Cuentas
                    </a>
                </li>
                <li>
                    <a href="#account-mark-rel" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Mantenimiento de Cuentas-Marcas
                    </a>
                </li>
            @elseif ( Auth::user()->type == GER_PROD )
                <li>
                    <a href="#tab-inversion" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Maestro de Inversiones
                    </a>
                </li>
                <li>
                    <a href="#tab-actividad" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Maestro de Actividades
                    </a>
                </li>
                <li>
                    <a href="#tab-inversion-actividad" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Maestro de Inversion-Actividad
                    </a>
                </li>
            @elseif ( Auth::user()->type == GER_COM )
                <li>
                    <a href="#reporte-fondo" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Reporte de Fondos
                    </a>
                </li>
            @elseif ( Auth::user()->type == TESORERIA )
                <li>
                    <a href="#sol-fondo" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Mantenimiento de Fondos
                    </a>
                </li>
            @endif  
        </ul>
        <div class="tab-content" style="margin-top: .75em;">
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default">
                    @include('template.User.menu')
                    @include('template.Modals.temporal_user')
                    @if( Auth::user()->type == TESORERIA )
                        @include('template.Modals.deposit')
                    @endif
                </div>
            </div>
            @include('template.tb_estado_cuenta')
            @if (Auth::user()->type == CONT)
                <!-- Busqueda de Documentos -->
                <div class="tab-pane fade" id="documentos">
                    <div class="panel panel-default">
                        @include('Dmkt.Cont.documents_menu')
                        @include('template.Modals.documents')   
                    </div>
                </div>

                <!-- Mantenimiento de Documentos -->
                <div class="tab-pane fade" id="mantenimiento">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_document_contabilidad"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary" id="add-doc" type="button" value="Agregar">
                    </div>
                </div>

                <!-- Mantenimiento de Cuenta del Fondo -->
                <div class="tab-pane fade" id="fondo-cuenta">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_fondo-cuenta"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary maintenance-add" type="hidden" case="fondo-cuenta">
                    </div>
                </div>

                <!-- Mantenimeinto de Cuentas y Marcas -->
                <div class="tab-pane fade" id="account-mark-rel">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_cuentas-marca"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary maintenance-add" type="button" case="cuentas-marca" value="Agregar">
                    </div>
                </div>
            @elseif ( Auth::user()->type == GER_PROD )
                <!-- Maestro de Inversiones -->
                <div class="tab-pane fade" id="tab-inversion">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_inversion"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary maintenance-add" type="button" case="inversion" value="Agregar">
                    </div>
                </div>
                <!-- Maestro de Actividades -->
                <div class="tab-pane fade" id="tab-actividad">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_actividad"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary maintenance-add" type="button" case="actividad" value="Agregar">
                    </div>
                </div>
                <!-- Maestro de Inversion - Actividad -->
                <div class="tab-pane fade" id="tab-inversion-actividad">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_inversionactividad"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary maintenance-add" type="button" case="inversionactividad" value="Agregar">
                    </div>
                </div>
            @elseif ( Auth::user()->type == GER_COM )
                <!-- Reporte de Fondos -->
                <div class="tab-pane fade" id="reporte-fondo">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_estado-fondos"></div>
                    </div>
                </div>        
            @elseif ( Auth::user()->type == TESORERIA )
                <!-- Mantenimiento de los Fondos de las Solicitudes -->
                <div class="tab-pane fade" id="sol-fondo">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_fondo"></div>
                    </div>
                    <div>
                       <input class="btn btn-primary maintenance-add" type="button" case="fondo" value="Agregar">
                    </div>
                </div>
            @endif
            <!-- Solicitud Institucional -->
            @include('template.User.institucion')
        </div>
        <div>
            <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
            <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#">Ocultar leyenda</a>
        </div>
    </div>
</div>
@include('template.leyenda')
@stop