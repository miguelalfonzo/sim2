@extends('template.main')
@section('content')

<div class="content">

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Ver Solicitud Supervisor</h3>
    </div>
    <div class="panel-body">
        <form id="form_make_activity" class="" method="post" action="">
            {{Form::token()}}
            <input id="textinput" name="idsolicitude" type="hidden" placeholder=""
                   value="{{$solicitude->idsolicitud}}">

            <div class="form-group col-sm-6 col-md-4">

                <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo Solicitud</label>

                <div class="col-sm-12 col-md-12">
                    <input id="textinput" name="textinput" type="text" placeholder=""
                           value="{{$solicitude->typesolicitude->nombre}}" readonly
                           class="form-control input-md">

                </div>
            </div>

            <div class="form-group col-sm-6 col-md-4">

                <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>

                <div class="col-sm-12 col-md-12">
                    <input id="textinput" name="titulo" type="text" placeholder=""
                           value="{{$solicitude->titulo}}" readonly
                           class="form-control input-md">

                </div>
            </div>

            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Solicitado</label>

                <div class="col-sm-12 col-md-12">
                    @if($solicitude->estado == 2)
                    <div class="input-group">
                        <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                        <input id="idamount" name="monto" type="text" placeholder="" value="{{$solicitude->monto}}"
                               class="form-control input-md">
                    </div>

                    @else
                    <div class="input-group">
                        <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                        <input id="idamount" name="monto" type="text" placeholder="" value="{{$solicitude->monto}}"
                               class="form-control input-md" readonly>
                    </div>

                    @endif

                </div>
            </div>

            <div class="form-group col-sm-12 col-md-4">

                <div class=col-md-12>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Productos</h3>
                        </div>
                        <div class="panel-body">
                            <?php $t=0;?>
                            @foreach($solicitude->families as $family)
                            <div class="form-group col-sm-12 col-md-12" style="padding: 0">


                                <div class="col-sm-8 col-md-8">

                                    <input id="textinput" name="textinput" type="text" placeholder=""
                                           value="{{$family->marca->descripcion}}" readonly
                                           class="form-control input-md">

                                </div>
                                <div class="col-sm-4 col-md-4" style="padding: 0">
                                    <div class="input-group">
                                        <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                        <input id="" name="amount_assigned[]" type="text" class="form-control input-md amount_families" value="{{isset($family->monto_asignado)? $family->monto_asignado : round($solicitude->monto/count($solicitude->families),2)}}">
                                    </div>
                                </div>

                            </div>
                            <?php $t = $t + round($solicitude->monto/count($solicitude->families),2)?>
                            @endforeach
                            <div class="form-group col-sm-12 col-md-12">
                                <span id="amount_error_families"></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Fondo</label>


                <div class="col-sm-12 col-md-12">
                    <input id="textinput" name="textinput" type="text" placeholder=""
                           value="{{$solicitude->subtype->nombre}}" readonly
                           class="form-control input-md">

                </div>
            </div>

            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>

                <div class="col-sm-12 col-md-12">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input id="date" type="text" name="delivery_date"
                               value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}"
                               class="form-control" maxlength="10" readonly placeholder="">
                    </div>

                </div>
            </div>

            <div class="form-group col-sm-6 col-md-4">

                <div class=col-md-12>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Clientes</h3>
                        </div>
                        <div class="panel-body">

                            @foreach($solicitude->clients as $client)
                            <div class="form-group" style="padding: 0 10px">


                                <div class="">
                                    <input id="textinput" name="textinput" type="text" placeholder=""
                                           value="{{$client->client->clnombre}}" readonly
                                           class="form-control input-md">

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group col-sm-6 col-md-4">

                <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>

                <div class="col-sm-12 col-md-12">
                    @if($solicitude->estado == 2)
                    <textarea id="textinput" name="observacion" placeholder=""
                              class="form-control"></textarea>
                    @else
                    <textarea id="textinput" name="observacion" placeholder=""
                              class="form-control" disabled></textarea>
                    @endif
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
                    @if($solicitude->estado == 2)
                    <a href="{{URL::to('aceptar_solicitud')}}" id="test" name="button1id"
                       class="btn btn-primary accepted_solicitude_sup">Aceptar
                    </a>
                    <a class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        Derivar
                    </a>
                    <a id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude">Rechazar
                    </a>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Escoja Gerente</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    {{Form::token()}}
                    <fieldset>

                        <!-- Form Name -->


                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="selectbasic"></label>

                            <div class="col-md-5">
                                <select id="selectbasic" name="selectbasic" class="form-control">
                                    @foreach($managers as $manager)
                                    <option value="{{$manager->id}}"> {{ $manager->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </fieldset>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Derivar</button>
            </div>
        </div>
    </div>
</div>
@stop
