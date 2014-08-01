@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>
<form class="form-horizontal">
    <fieldset>

        <!-- Form Name -->
        <legend>Nueva Solicitud</legend>

        <div class="col-sm-6 col-md-6" style="">

            <div class="form-group col-sm-12 col-md-12">

                <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Actividad</label>

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
                <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Producto</label>

                <div class="col-sm-10 col-md-10">
                    <ul id="listprod">

                        <li>
                            <select id="selectbasic" name="selectbasic" class="form-control selectproduct">
                                <option value="1">Option one</option>
                                <option value="2">Option two</option>
                            </select>

                            <div class='porcentaje_error' id='tooltip1' data-toggle='tooltip' data-placement='bottom'>
                                <button type='button' class='btn-delete'><span
                                        class='glyphicon glyphicon-remove'></span></button>
                            </div>
                        </li>
                    </ul>

                </div>

                <button type="button" class="btn btn-default" id="btn-add-prod">Agregar Otro Producto</button>
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

            <div class="form-group col-sm-12 col-md-12">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Cliente</label>

                <ul id="listclient">

                    <li>
                        <div class="col-sm-10 col-md-10">
                            <input id="" name="cliente" type="text" placeholder=""
                                   class="form-control input-md tags">

                        </div>
                    </li>
                </ul>
                <button type="button" class="btn btn-default" id="btn-add-client">Agregar Otro Cliente</button>
            </div>

        </div>
        <div class="col-sm-12 col-md-12" style="margin-top: 100px;position: relative">
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
                <button id="button1id" name="button1id" class="btn btn-primary">Crear</button>
                <button id="button2id" name="button2id" class="btn btn-primary">Cancelar</button>
            </div>
        </div>

    </fieldset>
</form>
@stop
<script>


</script>