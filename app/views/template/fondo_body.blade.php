<input type="hidden" id="idfondo" value="{{$fondo->idfondo}}">
{{Form::token()}}
<input type="hidden" id="token" value="{{$fondo->token}}">
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>SISOL - Hospital</label>
	    <input id="institucion" class="form-control" type="text" value="{{$fondo->titulo}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Solicitante</label>
		<input id="ager-user" class="form-control" type="text" value="{{$fondo->createdBy->person->nombres.' '.$fondo->createdBy->person->apellidos}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Rep. Medico</label>
		<input type="text" class="form-control" value="{{$fondo->asignedTo->rm->nombres.' '.$fondo->asignedTo->rm->apellidos}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Supervisor</label>
		<input type="text" class="form-control" value="{{json_decode($fondo->detalle->detalle)->supervisor}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Fondo</label>
		<input class="form-control" type="text" value="{{$fondo->detalle->fondo->nombre}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Monto Asignado</label>
		<div class="input-group">
	    	<div id="type-money" class="input-group-addon">{{$fondo->detalle->fondo->typeMoney->simbolo}}</div>
	      	<input id="deposit" class="form-control" type="text" value="{{json_decode($fondo->detalle->detalle)->monto_aprobado}}" disabled>
	    </div>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Periodo</label>
		<div class="input-group">
			<span class="input-group-addon">
                <i class="glyphicon glyphicon-calendar"></i>
            </span>
			<input id="periodo" class="form-control date_month" type="text" value="{{json_decode($fondo->detalle->detalle)->periodo}}" disabled>
		</div>
	</div>
</div>
@if ($fondo->estado == DEPOSITADO)
	<div class="col-xs-12 col-sm-6 col-md-4">
		<div class="form-expense">
			<label>Número de Operación, Transacción, Cheque</label>
			<input type="text" class="form-control" value="{{json_decode($fondo->detalle->detalle)->num_cuenta}}" disabled>	
		</div>
	</div>
@endif
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Nº de Cuenta</label>
		<input type="text" class="form-control" value="{{json_decode($fondo->detalle->detalle)->num_cuenta}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4"></div>