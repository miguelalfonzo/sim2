@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12" style="">
        <!-- Default panel contents -->
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Solicitudes Gerente Producto</h3><small style="float: right; margin-top: -10px"><strong>Usuario : {{Auth::user()->Gerprod->descripcion}}</strong></small></div>

            <div class="panel-body table-solicituds-gerprod">
                <div class="col-md-12" style="padding: 0">
                    <form method="post" action="" class="">
                        <input type="hidden" id="state_view" value="{{isset($state) ? $state : PENDIENTE}}">
                        <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                            <div class="">
                                <select id="idState" name="idstate"
                                        class="form-control selectestatesolicitude">
                                    @foreach($states as $state)
                                    <option value="{{$state->idestado}}">{{$state->nombre}}</option>
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
                                <a id="search_solicitude_gerprod" class="btn btn-sm btn-primary ladda-button"
                                   data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                            </div>

                        </div>
                    </form>


                </div>
                <!-- Table -->

            </div>
            <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
            <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#" >Ocultar leyenda</a>
        </div>
    </div>
</div>
<div class="container" id="leyenda" style="display: none">
    <table style=  "border-collapse: separate;border-spacing: 5px" >
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
