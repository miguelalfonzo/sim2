@extends('template.main')
@section('solicitude')
<div class="col-md-11" style="margin-bottom: 10px; margin-top: 10px">
</div>

<div class="col-md-12" style="">
    <!-- Default panel contents -->
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Solicitudes</h3></div>

        <div class="panel-body table-solicituds">
            <div class="col-md-12" style="padding: 0">
               <form method="post" action="">
                <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                    <div class="" ">
                        <select id="idstate" name="idstate"
                                class="form-control selectestatesolicitude">
                            @foreach($states as $state)
                            <option value="{{$state->idestado}}">{{$state->nombre}}</option>
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
                        <a  id="search-solicitude" class="btn btn-sm btn-primary ladda-button" data-style="expand-right" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                    </div>

                </div>
                </form>
                <div class="form-group col-sm-6 col-md-2 button-new-solicitude" style="text-align: right; padding: 0">

                    <div class="" style="padding: 0">
                        <a href="{{URL::to('nueva-solicitud')}}" id="singlebutton" name="singlebutton"
                           class="btn btn-primary">Nueva
                            Solicitud</a>
                    </div>

                </div>

            </div>
            <!-- Table -->

        </div>
    </div>
</div>

@stop
@section('actividad')
<!--
<div class="col-md-12" style="">
    <!-- Default panel contents
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Actividades</h3></div>

        <div class="panel-body table-activities">
            <div class="form-inline col-xs-6" style="padding: 0">


                <!-- Select Basic
                <div class="form-group">

                    <div class="col-md-4" style="padding: 0">
                        <select id="selectestateactivity" name="selectbasic" class="form-control selectestateactivity">

                            @foreach($states as $state)
                            <option value="{{$state->idestado}}">{{$state->nombre}}</option>
                            @endforeach


                        </select>
                    </div>
                </div>


            </div>
            <!-- Table

        </div>
    </div>
</div>-->
<script type="application/javascript" class="init">

    $('#table_activity').dataTable();
</script>
@stop
