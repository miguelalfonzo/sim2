@extends('template.main')
@section('solicitude')
<!-- <div id="loading-fondo" class="hide" style="z-index: 9999 ; position: absolute; top:45% ; left: 45%">
   <img src="{{URL::to('img/loading.gif')}}">
</div> -->
<div class="content">
    <div class="col-md-12" style="">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitudes" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon> Solicitudes a Depositar
                </a>
            </li>
            <li>
                <a href="#fondos" role="tab" data-toggle="tab">
                    <i class="fa fa-user"></i> Fondos Institucionales a Depositar
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Solicitudes -->     
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default" style="margin-top: 10px">
                @include('template/searchmenu')      
                 </div>
            </div>
            <!-- Fondos -->
            <div class="tab-pane fade" id="fondos">
                <div class="panel panel-default" style="margin-top: 10px">
                    <div class="panel-body table_solicitude_fondos-tesoreria table-solicituds-fondos-tesoreria">
                        <div id="" class="form-group col-xs-6 col-sm-3 col-md-3">
                            <div class="input-group">
                                <input type="text" id="datefondo" readonly class="form-control" data-type="fondos-tesoreria">
                                    <span class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </span>
                            </div></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
<!-- 
<div class="panel-body table-solicituds-tes">
    <div class="col-md-12" style="padding: 0">
        <form method="post" action="" class="">
            <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                <div class="">
                    <select id="idState" name="idstate"
                            class="form-control select_state_solicitude_tes">
                        @foreach($states as $state)
                          @if($state->idestado == APROBADO || $state->idestado == DEPOSITADO)
                            <option value="{{$state->idestado}}">{{$state->nombre}}</option>
                          @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-4 col-md-3">
                <div class="" style="padding: 0">
                    <div class="input-group ">
                      {{Form::token()}}
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
                    <a id="search_solicitude_tes" class="btn btn-sm btn-primary ladda-button"
                       data-style="zoom-out" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                </div>

            </div>
        </form>
        <div class="form-group col-sm-6 col-md-2 button-new-solicitude" style="text-align: right; padding: 0">



        </div>

    </div>
     Table
</div> 
-->