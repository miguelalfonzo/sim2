@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>

<div class="nueva_solicitud col-sm-12 col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading"><h4>Solicitud Sup</h4></div>
        <div class="panel-body">
            <form id="form_make_activity" class="form-horizontal" method="post" action="">
                <input id="textinput" name="idsolicitude" type="hidden" placeholder="" value="{{$solicitude->idsolicitud}}">
                <div class="col-sm-6 col-md-6" style="">

                    <div class="form-group col-sm-12 col-md-12">

                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo Solicitud</label>

                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$solicitude->tipo_solicitud}}" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">

                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>

                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="titulo" type="text" placeholder=""
                                   value="{{$solicitude->titulo}}" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Presupuesto</label>

                        <div class="col-sm-12 col-md-12">
                            @if($solicitude->estado == 1)
                            <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->presupuesto}}"
                                   class="form-control input-md">
                            @else
                            <input id="textinput" name="estimate" type="text" placeholder="" value="{{$solicitude->presupuesto}}"
                                   class="form-control input-md" readonly>
                            @endif

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">

                        <div class=col-md-12>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Productos</h3>
                                </div>
                                <div class="panel-body">

                                    @foreach($families as $family)
                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="{{$family->descripcion}}" readonly
                                                   class="form-control input-md">

                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="col-sm-6 col-md-6" style="">

                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Tipo de Actividad</label>


                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$solicitude->subtype->nombre}}" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>

                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="date" placeholder=""
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">

                        <div class=col-md-12>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Clientes</h3>
                                </div>
                                <div class="panel-body">

                                    @foreach($clients as $client)
                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="{{$client->clnombre}}" readonly
                                                   class="form-control input-md">

                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">

                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>

                        <div class="col-sm-12 col-md-12">
                            @if($solicitude->estado == 1)
                            <textarea id="textinput" name="observacion"  placeholder=""
                                   class="form-control"></textarea>
                            @else
                            <textarea id="textinput" name="observacion"  placeholder=""
                                      class="form-control" disabled></textarea>
                            @endif
                        </div>
                    </div>


                </div>
                <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la
                            Solicitud</label>

                        <div class="col-sm-12 col-md-12">
                            <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                        </div>
                    </div>
                </div>


                <!-- Button (Double) -->
                <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">


                    <div class="col-sm-12 col-md-12" style="text-align: center">
                        @if($solicitude->estado == 1)
                        <button id="button1id" name="button1id" class="btn btn-primary register_solicitude">Editar
                        </button>
                        <button  id="register_activity" name="button1id" class="btn btn-primary register_solicitude">Crear Actividad
                        </button>
                        <button  id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude">Rechazar
                        </button>
                        <a id="button2id" href="{{URL::to('show_sup')}}" name="button2id"
                           class="btn btn-primary">Cancelar</a>
                        @else
                        <a id="button2id" href="{{URL::to('show_sup')}}" name="button2id"
                           class="btn btn-primary">Cancelar</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
