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
            <!-- <li>
                <a href="#new-solicitud" role="tab" data-toggle="tab">
                    <i class="fa fa-user"></i>
                    Nueva Solicitud
                </a>
            </li> -->
            @endif
            @include('template.li_estado_cuenta')
            @if ( Auth::user()->type == REP_MED || Auth::user()->type == CONT || Auth::user()->type == TESORERIA)
                <!-- <li>
                    <a href="#fondos" role="tab" data-toggle="tab">
                        <i class="fa fa-user"></i>
                        Fondos Institucionales
                    </a>
                </li> -->
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
                        <div class="modal fade" id="enable_deposit_Modal" tabindex="-1" role="dialog" aria-labelledby="enable_deposit_ModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                            <span class="sr-only">Close</span>
                                        </button>
                                        <h4 class="modal-title" id="enable_deposit_ModalLabel">Registro del Depósito</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-expense">
                                                    <label>Solicitud</label>
                                                    <div class="input-group">
                                                        <div id="id-solicitude" class="input-group-addon" value=""></div>
                                                        <input id="sol-titulo" class="form-control" type="text" disabled>
                                                        <input id="token" type="hidden">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-expense">
                                                    <label>Beneficiario</label>
                                                    <input id="beneficiario" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-expense">
                                                    <label>Monto Solicitado</label>
                                                    <input id="tes-mon-sol" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-expense">
                                                    <label>Retencion</label>
                                                    <input id="tes-mon-ret" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <div class="form-expense">
                                                    <label>Monto a Depositar</label>
                                                    <input id="total-deposit" class="form-control" type="text" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                             <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-expense">
                                                    <label for="op-number">Número de Operación, Transacción, Cheque</label>
                                                    <input id="op-number" type="text" class="form-control">
                                                    <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a id="" href="#" class="btn btn-success register-deposit" style="margin-right: 1em;">Confirmar Operación</a>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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