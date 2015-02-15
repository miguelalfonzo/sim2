@extends('template.main')
@section('content')
<div id="loading-fondo" class="hide" style="z-index: 9999 ; position: absolute; top:45% ; left: 45%">
<img src="{{URL::to('img/loading.gif')}}">
</div>
<div class="content">
  <ul class="nav nav-tabs" role="tablist">
              <li class="active">
                  <a href="#fondos" role="tab" data-toggle="tab">
                      <icon class="fa fa-home"></icon> Registrar Fondos
                  </a>
              </li>
              <li><a href="#solicitudes" role="tab" data-toggle="tab">
                  <i class="fa fa-user"></i>Solicitudes
                  </a>
              </li>
  </ul>
   <div class="tab-content">
   <!-- Registro de Fondos -->
   <div class="tab-pane fade active in" id="fondos" data-url="fondos">
   <div class="panel panel-default " style="margin-top: 10px">
   <div class="panel-heading">
       <h3 class="panel-title" style="height: 15px"></h3>
       <small style="float: right; margin-top: -15px"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="icon-collapse"><span class="glyphicon glyphicon-chevron-down"></span></a></small>

   </div>

   <div id="collapseOne" class="panel-collapse collapse in">
   			<div class="panel-body">

               <div>


               <input value="" id="idfondo" name="idfondo" type="hidden">

               <input value="{{csrf_token()}}" name="_token" id="_token" type="hidden">
        
               <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-expense">
                  <label>Mes a Registrar</label>
                  <div class="input-group">
                    <span class="input-group-addon" ><i class="glyphicon glyphicon-calendar"></i></span>
                    <input type="text" id="date_reg_fondo" readonly class="form-control" data-type="fondos">
                  </div>
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-expense">
                  <label>Sisol - Hospital</label>
                  <input id="fondo_institucion" name="institucion" type="text" placeholder=""
                              value="{{isset($fondo->institucion)? $fondo->institucion : null }}"
                              class="form-control input-md">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-expense">
                  <label>Depositar a</label>
                  <input id="fondo_repmed"  name="repmed" type="text" placeholder="" style="position: relative"
                              value="{{isset($fondo->repmed)? $fondo->repmed : null }}" data-select="false"
                              class="form-control input-md change_before_rep" data-cod="">
                        <a id="edit-rep" class="edit-repr" href="#" style="display: inline;"><span class="glyphicon glyphicon-pencil"></span></a>
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-expense">
                  <label>N° de Cuenta</label>
                  <input id="fondo_cuenta" name="cuenta" type="text" placeholder=""
                              value="{{isset($fondo->cuenta) ? $fondo->cuenta : null }}"
                              class="form-control input-md" maxlength="25">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-expense">
                  <label>Total a depositar</label>
                   <input id="fondo_total" name="total" type="text" placeholder=""
                              value="{{isset($fondo->total) ? $fondo->total : null }}"
                              class="form-control input-md">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="form-expense">
                  <label>Supervisor</label>
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
       <h3 class="panel-title">Fondos Institucionales</h3>


   </div>
   <div class="panel-body table-solicituds-fondos" style="position: relative">


        <div id="" class="form-group col-xs-6 col-sm-3 col-md-3">
                        <div class="input-group">
                    <input type="text" id="datefondo" readonly class="form-control" data-type="fondos"><span class="input-group-addon" ><i class="glyphicon glyphicon-calendar"></i></span>
                </div>
        </div>
        
   </div>
   </div>
   </div>
   <!-- Solicitudes -->
   <div class="tab-pane fade table-solicituds-ager" id="solicitudes" >

   </div>
   </div>


</div>
@stop