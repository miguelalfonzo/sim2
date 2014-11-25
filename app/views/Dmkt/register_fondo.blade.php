@extends('template.main')
@section('content')
<div id="loading-fondo" class="hide" style="z-index: 9999 ; position: absolute; top:45% ; left: 45%">
<img src="{{URL::to('img/loading.gif')}}">
</div>
<div class="content">

<div class="panel panel-default ">
<div class="panel-heading">
    <h3 class="panel-title">Registrar Fondo</h3>
    <small style="float: right; margin-top: -10px"><strong>Usuario : NN</strong> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="icon-collapse"><span class="glyphicon glyphicon-chevron-down"></span></a></small>

</div>

<div id="collapseOne" class="panel-collapse collapse in">
			<div class="panel-body">
			
            <div>


            <input value="" id="idfondo" name="idfondo" type="hidden">

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
                    <input id="fondo_repmed"  name="repmed" type="text" placeholder="" style="position: relative"
                           value="{{isset($fondo->repmed)? $fondo->repmed : null }}"
                           class="form-control input-md change_before_rep" data-cod="">
                     <a id="edit-rep" class="edit-repr" href="#" style="display: inline;"><span class="glyphicon glyphicon-pencil"></span></a>
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

                        <button id="" name="button1id" class="btn btn-primary register_fondo ladda-button" data-style="zoom-in" data-size="l">Registrar
                        </button>
                        <button id="" name="button1id" class="btn btn-primary btn_edit_fondo ladda-button" data-style="zoom-in" data-size="l">Actualizar
                        </button>
                        <button id="" name="button1id" class="btn btn-primary btn_cancel_fondo ladda-button" data-style="zoom-in" data-size="l">Cancelar
                        </button>
                </div>
            </div>


            </div>
            </div>
		</div>

</div>



<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title">Fondos Institucionales 2014</h3>


</div>
<div class="panel-body table-solicituds-fondos" style="position: relative">


    <div id="" class="form-group col-xs-3 col-sm-3 col-md-3">
  					<div class="input-group">
  						<input type="text" id="datefondo" readonly class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
  					</div>
     </div>

    <div class="col-xs-2 col-sm-2 col-md-1 pull-right" >
            <a id="export-fondo" class="btn btn-sm btn-primary ladda-button" href=""
                                         data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-print"></i> Exportar</a>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-1 pull-right" >
            <a id="terminate-fondo" class="btn btn-sm btn-danger" href=""
                                         data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-download"></i> Terminar</a>
    </div>

</div>
</div>
</div>
@stop