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
                  <i class="fa fa-user"></i> Fondos Institucionales
                </a>
            </li>
        </ul>
        <div class="tab-content" style="margin-top: .75em;">
            <!-- Solicitudes -->
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default">
                    <div class="panel-body table-solicituds-rm">
                        <div class="col-md-12" style="padding: 0">
                            <form method="post" action="" class="">
                                {{Form::token()}}
                                <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                                    <div class="">
                                        <select id="idState" name="idstate" class="form-control selectestatesolicitude">
                                            @foreach($states as $state)
                                                @if($state->idestado == PENDIENTE || $state->idestado == ACEPTADO || $state->idestado == APROBADO || $state->idestado == DEPOSITADO || $state->idestado == REGISTRADO || $state->idestado == ENTREGADO || $state->idestado == GENERADO || $state->idestado == CANCELADO || $state->idestado == RECHAZADO || $state->idestado == TODOS)
                                                    <option value="{{$state->idestado}}">{{$state->nombre}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4 col-md-3" style="padding-right: 0">
                                    <div class="" style="padding: 0">
                                        <div class="input-group ">
                                            <span class="input-group-addon">Desde</span>
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input id="date_start" type="text" name="date_start" value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}" class="form-control" maxlength="10" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4 col-md-3" style="padding-right: 0">
                                    <div class="" style="padding: 0">
                                        <div class="input-group ">
                                            <span class="input-group-addon">Hasta</span>
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input id="date_end" type="text" name="date_end" value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}" class="form-control" maxlength="10" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-1 col-md-2">
                                    <div class="" style="padding: 0">
                                        <a id="search-solicitude" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group col-sm-6 col-md-2 button-new-solicitude" style="text-align: right; padding: 0">
                                <div class="" style="padding: 0">
                                    <a href="{{URL::to('nueva-solicitud-rm')}}" id="singlebutton" name="singlebutton" class="btn btn-primary">Nueva Solicitud</a>
                                </div>
                            </div>
                        </div>
                        <!-- Table -->

                    </div>
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
<div class="container" id="leyenda" style="display: none">
    <table style="border-collapse:separate; border-spacing: 5px">
        <tbody>
            @foreach($states as $state)
            <tr>
                <td>
                    <div class="" style='background-color: {{$state->color}} ; border-radius: 5px; text-align: center ;width: 120px'><span style="color: #ffffff">{{$state->nombre}}</span></div>
                </td>
                <td>
                    <span style="text-indent:50px;">{{$state->descripcion}}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop