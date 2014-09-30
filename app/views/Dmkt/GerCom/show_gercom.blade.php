@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12" style="">
        <!-- Default panel contents -->
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Solicitudes Gerente Comercial</h3><small style="float: right; margin-top: -10px"><strong>Usuario : Gerente Comercial</strong></small></div>

            <div class="panel-body table-solicituds-gercom">
                <div class="col-md-12" style="padding: 0">
                    <form method="post" action="" class="">
                        <input type="hidden" id="state_view" value="{{isset($state) ? $state : ACEPTADO}}">
                        <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                            <div class="">
                                <select id="idState" name="idState"
                                        class="form-control select_state_solicitude_gercom">

                                    @foreach($states as $state1)
                                        @if($state1->idestado == ACEPTADO || $state1->idestado == RECHAZADO || $state1->idestado == APROBADO)
                                            @if($state1->idestado == $state)
                                                <option  selected value="{{$state1->idestado}}">{{$state1->nombre}}</option>
                                            @else
                                                <option value="{{$state1->idestado}}">{{$state1->nombre}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-4 col-md-3">
                            <div class="" style="padding: 0">
                                <div class="input-group ">
                                    <span class="input-group-addon">Desde</span>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                                    <input id="date_start" type="text" name="date_start"
                                           value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}"
                                           class="form-control" maxlength="10" readonly placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-4 col-md-3">
                            <div class="" style="padding: 0">
                                <div class="input-group ">
                                    <span class="input-group-addon">Hasta</span>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                                    <input id="date_end" type="text" name="date_end"
                                           value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}"
                                           class="form-control" maxlength="10" readonly placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-1 col-md-2">

                            <div class="" style="padding: 0">
                                <a id="search_solicitude_gercom" class="btn btn-sm btn-primary ladda-button"
                                   data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                            </div>

                        </div>
                    </form>
                    <div class="form-group col-sm-6 col-md-2 button-new-solicitude" style="text-align: right; padding: 0">



                    </div>

                </div>
                <!-- Table -->

            </div>
        </div>
    </div>
</div>
@stop
