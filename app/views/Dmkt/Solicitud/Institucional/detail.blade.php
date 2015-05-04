<!-- Titulo -->
@include('Dmkt.Solicitud.Detail.titulo')

<!-- Solicitante -->
@include('Dmkt.Solicitud.Detail.solicitante')

<!-- Asignado A -->
@include('Dmkt.Solicitud.Detail.asignado')

<!-- Supervisor -->
<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12" for="sup">Supervisor</label>
	 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input type="text" class="form-control" value="{{json_decode($solicitud->detalle->detalle)->supervisor}}" disabled>
	</div>
</div>

@include('Dmkt.Solicitud.Detail.fondo')

@include('Dmkt.Solicitud.Detail.monto')

<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Periodo</label>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="input-group">
			<span class="input-group-addon">
	            <i class="glyphicon glyphicon-calendar"></i>
	        </span>
			<input id="periodo" class="form-control date_month" type="text" value="{{$solicitud->detalle->periodo->periodo}}" disabled>
		</div>
	</div>
</div>

@if ($solicitud->estado == DEPOSITADO)
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
		<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Número de Operación, Transacción, Cheque</label>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<input type="text" class="form-control" value="{{json_decode($solicitud->detalle->detalle)->num_cuenta}}" disabled>	
		</div>
	</div>
@endif

<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Nº de Cuenta del Representante</label>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<input type="text" class="form-control" value="{{$detalle->num_cuenta}}" disabled>
	</div>
</div>

<!-- DEPOSITADO -->
@include('Dmkt.Solicitud.Detail.depositado')

<!-- Tasa del Día del Deposito -->
@include('Dmkt.Solicitud.Detail.tasa')

@if (!is_null( $solicitud->observacion) )
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
		<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Observación</label>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<textarea class="form-control" disabled>{{$solicitud->observacion}}</textarea>
		</div>
	</div>
@endif

<!-- Modal Deposito -->
@include('template.Modals.deposit-min')

