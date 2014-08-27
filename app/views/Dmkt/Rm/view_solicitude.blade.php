@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>

<div class="content">

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Solicitud</h3></div>
        <div class="panel-body">




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
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$solicitude->titulo}}" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto</label>

                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->monto}}" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">

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

                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Tipo de Actividad</label>


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
                                <input id="date" type="text" class="form-control" maxlength="10" disabled placeholder="" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}">
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

                                    @foreach($clients as $client)
                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="{{$client->clnombre}}" readonly
                                                   class="form-control input-md ">

                                        </div>
                                    </div>
                                   @endforeach
                                </div>
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
                        <a id="button2id" href="{{URL::to('show_rm')}}" name="button2id"
                           class="btn btn-primary">Cancelar</a>
                    </div>
                </div>



        </div>
    </div>
</div>
@stop
<script>


</script>