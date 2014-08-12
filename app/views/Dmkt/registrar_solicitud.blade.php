@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>

<div class="nueva_solicitud col-sm-12 col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading"><h4>Nueva Solicitud</h4></div>
        <div class="panel-body">
    <form class="form-horizontal">

            <div class="col-sm-6 col-md-6" style="">

                <div class="form-group col-sm-12 col-md-12">

                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>

                    <div class="col-sm-10 col-md-10">
                        <input id="textinput" name="textinput" type="text" placeholder=""
                               class="form-control input-md">

                    </div>
                </div>

                <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Presupuesto</label>

                    <div class="col-sm-10 col-md-10">
                        <input id="textinput" name="textinput" type="text" placeholder=""
                               class="form-control input-md">

                    </div>
                </div>

                <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-8 col-md-8 control-label" for="selectproduct">Producto</label>

                    <div class="col-sm-12 col-md-12">
                        <ul id="listprod" class="col-sm-12 col-md-12">

                            <li>
                                <div class="form-group col-sm-12 col-md-12">
                                    <select  id="selectproduct" name="selectbasic" class="form-control selectproduct" style="margin-top: 10px ; margin-left: -15px">

                                        @foreach($products as $product)
                                        <option value="{{$product->foalias}}">{{$product->fodescripcion}}</option>
                                        @endforeach
                                    </select>
                                    <button type='button' class='btn-delete-prod' style="display: none"><span class='glyphicon glyphicon-remove'></span></button>
                                </div>

                            </li>
                        </ul>

                        <button type="button" class="btn btn-default" id="btn-add-prod">Agregar Otro Producto</button>
                    </div>


                </div>

            </div>


            <div class="col-sm-6 col-md-6" style="">

                <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Tipo de Actividad</label>

                    <div class="col-sm-10 col-md-10">
                        <select id="selectbasic" name="selectbasic" class="form-control">
                            <option value="1">Option one</option>
                            <option value="2">Option two</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>

                    <div class="col-sm-10 col-md-10">
                        <input id="textinput" name="textinput" type="date" placeholder=""
                               class="form-control input-md">

                    </div>
                </div>

                <div class="form-group col-sm-12 col-md-12 ">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Cliente</label>

                    <ul id="listclient" class="col-sm-10 col-md-10">

                        <li>
                            <div style="position: relative" class="">
                            <input id="project1" name="cliente" type="text" placeholder="" style="margin-top: 10px"
                                   class="form-control input-md project">
                            <span class="span-alert"></span>
                            <button type='button' class='btn-delete-client' style="display: none"><span class='glyphicon glyphicon-remove'></span></button>
                            </div>
                        </li>

                    </ul>
                    <span class="clients_repeat col-sm-10 col-md-10" style="color: red">Datos Repetidos</span>
                    <button type="button" class="btn btn-default" id="btn-add-client">Agregar Otro Cliente</button>

                </div>

            </div>
            <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>

                    <div class="col-sm-11 col-md-11">
                        <textarea class="form-control" id="textarea" name="textarea"></textarea>
                    </div>
                </div>
            </div>


            <!-- Button (Double) -->
            <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">


                <div class="col-sm-12 col-md-12" style="text-align: center">
                    <button id="button1id" name="button1id" class="btn btn-primary register_solicitude">Crear</button>
                    <a id="button2id" href="{{URL::to('show')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                </div>
            </div>

        </fieldset>
    </form>
            </div>
</div>
@stop
<script>


</script>