@extends('template.main')
@section('content')
<div class="content">

<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title">Registrar Fondo</h3>
    <small style="float: right; margin-top: -10px"><strong>Usuario : NN</strong></small>
</div>
<div class="panel-body">
<div>
{{Form::token()}}
@if(isset($fondo))
<input value="{{$fondo->idfondo}}" name="idfondo" type="hidden">
@endif
<input value="{{csrf_token()}}" name="_token" id="_token" type="hidden">
<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">SiSol - Hospital</label>

    <div class="col-sm-12 col-md-12">
        <input id="fondo_institucion" name="institucion" type="text" placeholder=""
               value="{{isset($fondo->institucion)? $fondo->institucion : null }}"
               class="form-control input-md">

    </div>
</div>
<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Depositar a</label>

    <div class="col-sm-12 col-md-12">
        <input id="fondo_repmed" name="repmed" type="text" placeholder=""
               value="{{isset($fondo->repmed)? $fondo->repmed : null }}"
               class="form-control input-md">

    </div>
</div>



<div class="form-group col-sm-6 col-md-4" id="">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">N° de Cuenta</label>

    <div class="col-sm-12 col-md-12">
        <input id="fondo_cuenta" name="cuenta" type="text" placeholder=""
               value="{{isset($fondo->cuenta) ? $fondo->cuenta : null }}"
               class="form-control input-md" maxlength="25">

    </div>
</div>


<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Total a depositar</label>

    <div class="col-sm-12 col-md-12">
        <input id="fondo_total" name="total" type="text" placeholder=""
               value="{{isset($fondo->total) ? $fondo->total : null }}"
               class="form-control input-md">

    </div>
</div>



<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Supervisor</label>

    <div class="col-sm-12 col-md-12">
        <input id="fondo_supervisor" name="supervisor" type="text" placeholder=""
               value="{{isset($fondo->supervisor) ? $fondo->supervisor : null }}"
               class="form-control input-md">

    </div>
</div>



<!-- Button (Double) -->
<div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">


    <div class="col-sm-12 col-md-12" style="text-align: center">

            <button id="" name="button1id" class="btn btn-primary register_fondo ladda-button" data-style="zoom-in" data-size="l">{{isset($fondo) ?
                'Actualizar' : 'Registrar'}}
            </button>


    </div>
</div>


</div>
</div>
</div>



<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title">Fondos Institucionales 2014</h3>
   
</div>
<div class="panel-body table-solicituds-fondos">


</div>
</div>
</div>
@stop