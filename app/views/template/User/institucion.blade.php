@if ( Auth::user()->type == ASIS_GER )
    <div class="tab-pane fade" id="fondos" data-url="fondos">
        <div class="panel panel-default " style="margin-top: 10px">
            <div class="panel-heading">
                <h3 class="panel-title" style="height: 15px"></h3>
                <small style="float: right; margin-top: -15px">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="icon-collapse">
                        <span class="glyphicon glyphicon-chevron-down"></span>
                    </a>
                </small>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
       			<div class="panel-body">
                    <div>
                        <input id="idsolicitud" name="idsolicitud" type="hidden">
                        <!-- PERIODO -->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Mes a Registrar</label>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon" >
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control date_month" data-type="fondos" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- SISOL -->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">SISOL - Hospital</label>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">  
                                <input id="fondo_institucion" name="institucion" type="text" class="form-control input-md">
                            </div>
                        </div>

                        <!-- ETIQUETA -->
                        @include('Dmkt.Register.Detail.actividad')

                        <!-- REPRESENTANTE -->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Depositar a</label>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <input id="fondo_repmed" name="repmed" type="text" style="display:inline"
                                data-select="false" class="form-control input-md rep-seeker">
                                <a id="edit-rep" class="edit-repr glyphicon glyphicon-pencil" href="#" style="display:inline">
                                    <!-- <span class=""></span> -->
                                </a>
                            </div>
                        </div>
      
                        <!-- Fondos -->
                        @include('Dmkt.Solicitud.Detail.fondo')
                        
                        <!-- Cuenta -->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">N° de Cuenta</label>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <input id="fondo_cuenta" name="cuenta" type="text" class="form-control input-md" maxlength="25">
                            </div>
                        </div>

                        <!-- SUPERVISOR -->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Supervisor</label>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <input id="fondo_supervisor" name="supervisor" data-cod="0" type="text" class="form-control input-md">
                            </div>
                        </div>

                        <!-- MONTO -->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Total a depositar</label>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">    
                                <input id="fondo_total" name="total" type="text" class="form-control input-md">
                            </div>
                        </div>
                      
                       <!-- Button (Double) -->
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <button class="btn btn-primary register_fondo ladda-button" data-style="zoom-in" data-size="l">
                                    Registrar
                                </button>
                                <button class="btn btn-primary btn_edit_fondo ladda-button" data-style="zoom-in" data-size="l">
                                    Actualizar
                                </button>
                                <button class="btn btn-primary btn_cancel_fondo ladda-button" data-style="zoom-in" data-size="l">
                                    Cancelar
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
            <div class="panel-body table-solicituds-fondos" style="position: relative"></div>
        </div>
    </div>
@endif