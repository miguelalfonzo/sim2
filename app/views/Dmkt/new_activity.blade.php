@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>

<div class="nueva_solicitud col-sm-12 col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading"><h4>Nueva Actividad</h4></div>
        <div class="panel-body">
            <form class="form-horizontal">

                <div class="col-sm-6 col-md-6" style="">

                    <div class="form-group col-sm-12 col-md-12">

                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Actividad</label>

                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="Torta de cumpleaños para el Dr. Mejia" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Presupuesto</label>

                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder="" value="50.00" readonly
                                   class="form-control input-md">

                        </div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12">

                        <div class=col-md-12>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Productos</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="50.00" readonly
                                                   class="form-control input-md">

                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="50.00" readonly
                                                   class="form-control input-md">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="col-sm-6 col-md-6" style="">

                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Tipo de Actividad</label>

                        <div class="col-sm-12 col-md-12">
                            <select id="selectbasic" name="selectbasic" class="form-control">
                                <option value="1">Option one</option>
                                <option value="2">Option two</option>
                            </select>
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

                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="50.00" readonly
                                                   class="form-control input-md">

                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-12">


                                        <div class="col-sm-12 col-md-12">
                                            <input id="textinput" name="textinput" type="text" placeholder=""
                                                   value="50.00" readonly
                                                   class="form-control input-md">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la
                            Solicitud</label>

                        <div class="col-sm-12 col-md-12">
                            <textarea class="form-control" id="textarea" name="textarea"></textarea>
                        </div>
                    </div>
                </div>


                <!-- Button (Double) -->
                <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">


                    <div class="col-sm-12 col-md-12" style="text-align: center">

                        <button id="button1id" name="button1id" class="btn btn-primary register_solicitude">Crear
                        </button>
                        <a id="button2id" href="{{URL::to('show')}}" name="button2id"
                           class="btn btn-primary">Cancelar</a>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>
@stop
<script>


</script>