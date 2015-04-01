<input type="hidden" id="idfondo" value="{{$fondo->idfondo}}">
{{Form::token()}}
<input type="hidden" id="token" value="{{$fondo->token}}">
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>SISOL - Hospital</label>
	    <input id="institucion" class="form-control" type="text" value="{{$fondo->institucion}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Solicitante</label>
		<input id="ager-user" class="form-control" type="text" value="{{$fondo->ager->person->nombres.' '.$fondo->ager->person->apellidos}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Rep. Medico</label>
		<input type="text" class="form-control" value="{{mb_convert_case($fondo->repmed, MB_CASE_TITLE, 'UTF-8')}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Supervisor</label>
		<input type="text" class="form-control" value="{{mb_convert_case($fondo->supervisor, MB_CASE_TITLE, 'UTF-8')}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Fondo</label>
		<input class="form-control" type="text" value="{{$fondo->account->nombre_mkt}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Monto Solicitado</label>
		<div class="input-group">
	    	<div id="type-money" class="input-group-addon">S/.</div>
	      	<input id="deposit" class="form-control" type="text" value="{{$fondo->monto}}" disabled>
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
			<input id="periodo" class="form-control date_month" type="text" value="{{substr($fondo->periodo,4,6).'-'.substr($fondo->periodo,0,4)}}" disabled>
		</div>
	</div>
</div>
@if ($fondo->estado == DEPOSITADO)
	<div class="col-xs-12 col-sm-6 col-md-4">
		<div class="form-expense">
			<label>Número de Operación, Transacción, Cheque</label>
			<input type="text" class="form-control" value="{{$fondo->deposit->num_transferencia}}" disabled>	
		</div>
	</div>
@endif
<div class="col-xs-12 col-sm-6 col-md-4">
	<div class="form-expense">
		<label>Nº de Cuenta</label>
		<input type="text" class="form-control" value="{{$fondo->rep_cuenta}}" disabled>
	</div>
</div>
<div class="col-xs-12 col-sm-6 col-md-4"></div>