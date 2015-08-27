<div class="row">
	<div class="clearfix"></div>
	
	<!-- N° de Operacion del Deposito Actual -->
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	    <label class="control-label">N° de Tranferencia</label>
	    <div>
	        <input type="text" class="form-control input-md" value="{{$solicitud->detalle->deposit->num_transferencia}}" disabled>
	    </div>
	</div>
	<!-- N° de Operacion del Deposito Actual -->

	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	    <label class="control-label">Monto depositado</label>
	    
	    <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->deposit->account->typeMoney->simbolo}}</span>
                <input type="text" class="form-control input-md" value="{{$solicitud->detalle->deposit->total}}" disabled>
            </div>
	</div>
	<!-- Nuevo N° de Operacion del Deposito -->
	<!--<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	    <label class="control-label">Nuevo N° de Operacion</label>
	    <div>
	        <input type="text" class="form-control input-md" id="nuevo_num_ope" maxlength="200">
	    </div>
	</div>-->
</div>
