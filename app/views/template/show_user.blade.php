@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitudes" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon>
                    Solicitudes
                </a>
            </li>
            @if ( in_array( Auth::user()->type , array (REP_MED, SUP ) ) )
            
            @endif
            @include('template.li_estado_cuenta')
            @if ( Auth::user()->type == REP_MED || Auth::user()->type == CONT || Auth::user()->type == TESORERIA)
                @if( Auth::user()->type == CONT)
                    <li>
                        <a href="#mantenimiento" role="tab" data-toggle="tab">
                            <i class="fa fa-user"></i>
                            Mantenimiento de Documentos
                        </a>
                    </li>
                @endif
            @endif
        </ul>
        <div class="tab-content" style="margin-top: .75em;">
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default">
                    @include('template.searchmenu')
                    @if( Auth::user()->type == TESORERIA )
                        @include('template.Modals.deposit')
                    @endif
                </div>
            </div>
            <div class="tab-pane fade" id="fondos">
                <div class="panel panel-default"  style="margin-top: 10px">
                    @if (Auth::user()->type == REP_MED)
                        <div class="panel-body table_fondos_rm"></div>
                    @elseif (Auth::user()->type == CONT)
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
                    @elseif (Auth::user()->type == TESORERIA)
                        <div class="panel-body table_solicitude_fondos-tesoreria table-solicituds-fondos-tesoreria">
                            <div id="" class="form-group col-xs-6 col-sm-3 col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control date_month" style="background-color:#FFF" data-type="fondos-tesoreria" readonly>
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @include('template.tb_estado_cuenta')
            @if (Auth::user()->type == CONT)
                <!-- Mantenimiento de Documentos -->
                <div class="tab-pane fade" id="mantenimiento">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_document_contabilidad">
                            <div id="" class="form-group col-xs-6 col-sm-3 col-md-3"></div>
                        </div>
                    </div>
                </div>
            @endif
            @if ( in_array( Auth::user()->type , array (REP_MED , SUP ) ))
                <!-- Nueva Solicitud -->
                <div class="tab-pane fade" id="new-solicitud">
                        <!-- <div class="panel panel-default">
                            <div class="panel-body panel-default table_document_contabilidad">
                                <div id="" class="form-group col-xs-6 col-sm-3 col-md-3"></div>
                            </div>
                        </div> -->
                </div>
            @endif
        </div>
        <div>
            <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
            <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#">Ocultar leyenda</a>
        </div>
    </div>
    @if ( Auth::user()->type == TESORERIA)
    
    @endif
</div>
@include('template.leyenda')
@if(isset($warnings[status]))
    @if ($warnings[status] == ok )
        <script type="text/javascript">
            $(document).ready(function() {
                bootbox.alert('<h4>' + {{$warnings[data]}} + '</h4>');
            });
        </script>
    @endif
@endif
@stop