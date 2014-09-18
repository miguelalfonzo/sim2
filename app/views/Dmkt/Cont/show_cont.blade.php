@extends('template.main')
@section('solicitude')
	<div class="content">
	    <div class="col-md-12" style="">
	        <!-- Default panel contents -->
	        <div class="panel panel-default">
	            <div class="panel-heading"><h3 class="panel-title">Solicitudes Aprobada</h3><small style="float: right; margin-top: -10px"><strong>Usuario : Contabilidad</strong></small></div>

	            <div class="panel-body table-solicituds-cont">
	                <div class="col-md-12" style="padding: 0">
	                    <form method="post" action="" class="">
	                        <div class="form-group col-sm-3 col-md-2" style="padding: 0">
	                            <div class="">
	                                <select id="select_state_solicitude_cont" name="idstate"
	                                        class="form-control select_state_solicitude_cont">
	                                    @foreach($states as $state)
		                                    @if($state->idestado == APROBADO || $state->idestado == DEPOSITADO || $state->idestado == REGISTRADO || $state->idestado == GENERADO )
		                                    	<option value="{{$state->idestado}}">{{$state->nombre}}</option>
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
	                                <a id="search_solicitude_cont" class="btn btn-sm btn-primary ladda-button"
	                                   data-style="expand-right" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
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