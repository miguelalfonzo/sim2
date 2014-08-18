@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>

<div class="nueva_solicitud col-sm-12 col-md-12">

<div class="panel panel-default">
<div class="panel-heading">
    <h4>Nueva Solicitud</h4>
</div>
<div class="panel-body">
<form id="form-register-solicitude" class="form-horizontal" method="post"
      action="{{ URL::to( isset($solicitude) ? 'editar-solicitud' : 'registrar-solicitud' )}}">

@if(isset($solicitude))
<input value="{{$solicitude->idsolicitud}}" name="idsolicitude" type="hidden">
@endif
<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>

    <div class="col-sm-12 col-md-12">
        <input id="idtitle" name="titulo" type="text" placeholder=""
               value="{{isset($solicitude->titulo)? $solicitude->titulo : null }}"
               class="form-control input-md" required>

    </div>
</div>


<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo Solicitud</label>

    <div class="col-sm-12 col-md-12">
        <select id="" name="type_solicitude" class="form-control selecttypesolicitude">
            <option value="Actividad">Actividad</option>
            <option value="Regalos">Regalos</option>
            <option value="Reembolso">Reembolso</option>
        </select>

    </div>
</div>


<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Presupuesto</label>

    <div class="col-sm-12 col-md-12">
        <input id="idestimate" name="estimate" type="text" placeholder=""
               value="{{isset($solicitude->presupuesto) ? $solicitude->presupuesto : null }}"
               class="form-control input-md">

    </div>
</div>

<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Moneda</label>

    <div class="col-sm-12 col-md-12">
        <select id="money" name="money" class="form-control">

            <option value="1">Soles</option>
            <option value="2">Dolares</option>

        </select>

    </div>
</div>

<div class="solicitude_monto form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Factura</label>

    <div class="col-sm-12 col-md-12">
        <input id="idfactura" name="amount" type="text" placeholder=""
               value="{{isset($solicitude->monto) ? $solicitude->monto : null }}"
               class="form-control input-md">

    </div>
</div>

<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Tipo de Actividad</label>

    <div class="col-sm-12 col-md-12">
        <select id="type_activity" name="type_activity" class="form-control">
            @foreach($typeactivities as $type)
            @if(isset($solicitude) && $type->idtipoactividad == $solicitude->subtype->idtipoactividad)
            <option selected value="{{$type->idtipoactividad}}">{{$type->nombre}}</option>
            @else
            <option value="{{$type->idtipoactividad}}">{{$type->nombre}}</option>
            @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Sub Tipo Actividad</label>

    <div class="col-sm-12 col-md-12">
        <select id="sub_type_activity" name="sub_type_activity" class="form-control">
            @foreach($subtypeactivities as $sub)
            @if(isset($solicitude) && $sub->idsubtipoactividad == $solicitude->subtype->idsubtipoactividad)
            <option selected value="{{$sub->idsubtipoactividad}}">{{$sub->nombre}}</option>
            @else
            <option value="{{$sub->idsubtipoactividad}}">{{$sub->nombre}}</option>
            @endif
            @endforeach
        </select>

    </div>
</div>


<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>

    <div class="col-sm-12 col-md-12">

        <div class="input-group date">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input id="date" type="text" name="delivery_date" value="{{isset($solicitude)? $solicitude->fecha_entrega : null }}" class="form-control" maxlength="10" readonly placeholder="">
        </div>

    </div>
</div>
<div class="solicitude_factura form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Factura</label>

    <div class="col-sm-10 col-md-10">
        <input id="filebutton" name="filefac" class="input-file" type="file">

    </div>
</div>

<div class="form-group col-sm-6 col-md-4 ">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Cliente</label>

    <ul id="listclient" class="col-sm-12 col-md-12">

        @if(isset($clients))
        @foreach($clients as $client)
        <li>
            <div style="position: relative" class="">
                <input id="project1" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px"
                       class="form-control input-md project"
                       value="{{isset($client->clnombre) ? $client->clcodigo.' - '.$client->clnombre : null }}">

                <button type='button' class='btn-delete-client' style="z-index: 2"><span
                        class='glyphicon glyphicon-remove'></span></button>
            </div>
        </li>
        @endforeach
        @else
        <li>
            <div style="position: relative" class="">
                <input id="project1" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px"
                       class="form-control input-md project"
                       value="{{isset($client->clnombre) ? $client->clcodigo.' - '.$client->clnombre : null }}">

                <button type='button' class='btn-delete-client' style="display: none; z-index: 2"><span
                        class='glyphicon glyphicon-remove'></span></button>
            </div>
        </li>

        @endif
    </ul>
    <span class="
    col-sm-10 col-md-10 clients_repeat" style=""></span>
    <button type="button" class="btn btn-default" id="btn-add-client">Agregar Otro Cliente</button>

</div>
<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="selectfamily">Familia</label>


    <ul id="listfamily" class="col-sm-10 col-md-10" style="">

        @if(isset($families2))
        @foreach($families2 as $family2)
        <li>
            <div class="" style="position: relative">

                <select id="selectfamily" name="families[]" class="form-control selectfamily"
                        style="margin-bottom:10px ">
                    @foreach($families as $family)
                    @if($family->id == $family2->id)
                    <option value="{{$family->id}}" selected>{{$family->descripcion}}</option>
                    @else
                    <option value="{{$family->id}}">{{$family->descripcion}}</option>
                    @endif
                    @endforeach
                </select>
                <button type='button' class='btn-delete-family' style=""><span
                        class='glyphicon glyphicon-remove'></span></button>
            </div>

        </li>
        @endforeach
        @else
        <li>
            <div class="" style="position: relative">

                <select id="selectfamily" name="families[]" class="form-control selectfamily"
                        style="margin-bottom:10px ">
                    @foreach($families as $family)
                    <option value="{{$family->id}}">{{$family->descripcion}}</option>
                    @endforeach
                </select>
                <button type='button' class='btn-delete-family' style="display: none"><span
                        class='glyphicon glyphicon-remove'></span></button>
            </div>

        </li>
        @endif


    </ul>

    <button type="button" class="btn btn-default" id="btn-add-family">Agregar Otra Familia</button>


</div>


<div class="col-sm-12 col-md-12" style="margin-top: 10px">
    <div class="form-group col-sm-12 col-md-12">
        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>

        <div class="col-sm-11 col-md-11">
            <textarea class="form-control" id="iddescriptionsolicitude" name="description"></textarea>
        </div>
    </div>
</div>


<!-- Button (Double) -->
<div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">


    <div class="col-sm-12 col-md-12" style="text-align: center">
        <button id="button1id" name="button1id" class="btn btn-primary register_solicitude">{{isset($solicitude) ?
            'Actualizar' : 'Crear'}}
        </button>
        <a id="button2id" href="{{URL::to('show_rm')}}" name="button2id" class="btn btn-primary">Cancelar</a>
    </div>
</div>


</form>
</div>
</div>
</div>
@stop
<script>


</script>