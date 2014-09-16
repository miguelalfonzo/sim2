@extends('template.main')
@section('solicitude')
<div class="content">
<div class="col-md-12" style="">
    <!-- Default panel contents -->
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Solicitudes Supervisor</h3><small style="float: right; margin-top: -10px"><strong>Usuario : {{Auth::user()->Sup->nombres}}</strong></small></div>
        <input type="hidden" value="{{Auth::user()->type}}" id="idUser">
        <div class="panel-body table-solicituds-sup">
            <div class="col-md-12" style="padding: 0">
                <form method="post" action="" class="">
                    <input type="hidden" id="state_view" value="{{isset($state) ? $state : PENDIENTE}}">
                    <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                        <div class="">
                            <select id="select_state_solicitude_sup" name="idstate"
                                    class="form-control selectestatesolicitude">
                                @foreach($states as $state1)
                                @if($state1->idestado == $state)
                                <option  selected value="{{$state1->idestado}}">{{$state1->nombre}}</option>
                                @else
                                <option value="{{$state1->idestado}}">{{$state1->nombre}}</option>
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

                                <input id="date_start" type="text" name="date_start"
                                       value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}"
                                       class="form-control" maxlength="10" readonly placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-4 col-md-3" style="padding-right: 0">
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
                            <a id="search_solicitude_sup" class="btn btn-sm btn-primary ladda-button"
                               data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                        </div>

                    </div>
                </form>
                <div class="form-group col-sm-6 col-md-2 button-new-solicitude" style="text-align: right; padding: 0">

                    <div class="" style="padding: 0">
                        <a href="{{URL::to('nueva-solicitud-sup')}}" id="singlebutton" name="singlebutton"
                           class="btn btn-primary">Nueva
                            Solicitud</a>
                    </div>

                </div>

            </div>
            <!-- Table -->

        </div>
    </div>
</div>
</div>

<div class="container">
<table style=  "border-collapse: separate;border-spacing: 5px">
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
