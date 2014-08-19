@extends('template.main')
@section('solicitude')
<div class="col-md-11" style="margin-bottom: 10px; margin-top: 10px">
</div>

<div class="col-md-12" style="">
    <!-- Default panel contents -->
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Solicitudes</h3></div>

        <div class="panel-body table-solicituds">
            <div class="form-inline col-xs-6" style="padding: 0">


                <!-- Select Basic -->
                <div class="form-group">

                    <div class="col-md-4" style="padding: 0">
                        <select id="selectestatesolicitude" name="selectbasic" class="form-control selectestatesolicitude">

                            @foreach($states as $state)
                            <option value="{{$state->idestado}}">{{$state->nombre}}</option>
                            @endforeach


                        </select>
                    </div>
                </div>

                <div class="form-group">

                    <div class="col-md-4">
                        <a href="{{URL::to('nueva-solicitud')}}" id="singlebutton" name="singlebutton" class="btn btn-primary">Nueva
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

<div class="col-md-12" style="">
    <!-- Default panel contents -->
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Actividades</h3></div>

        <div class="panel-body table-activities">
            <div class="form-inline col-xs-6" style="padding: 0">


                <!-- Select Basic -->
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
            <!-- Table -->

        </div>
    </div>
</div>
<script type="application/javascript" class="init">

    $('#table_activity').dataTable();
</script>
@stop
