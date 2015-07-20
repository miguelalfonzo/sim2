<section class="row" style="padding:0.3em 1em">
	<div class="page-header">
        <h2>{{$solicitud->titulo}} <span class="label label-default">{{{ $solicitud->activity->nombre or '' }}}</span></h2>
    </div>

	<!-- Monto -->
	@include('Dmkt.Solicitud.Detail.monto')

	<!-- Periodo -->
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
		<label class="control-label">Periodo</label>
		<div>
			<div class="input-group">
				<span class="input-group-addon">
		            <i class="glyphicon glyphicon-calendar"></i>
		        </span>
				<input id="periodo" class="form-control date_month" type="text" value="{{$detalle->periodo->periodo}}" disabled>
			</div>
		</div>
	</div>

	<!-- Solicitante -->
	@include('Dmkt.Solicitud.Detail.solicitante')

	<!-- Asignado A -->
	@include('Dmkt.Solicitud.Detail.asignado')

	<!-- Supervisor -->
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
		<label class="control-label" for="sup">Supervisor</label>
		 <div class="input-group">
		 	<span class="input-group-addon">S</span>   
	        <input type="text" class="form-control input-md" value="{{$detalle->supervisor}}" disabled>
		</div>
	</div>

	@include('Dmkt.Solicitud.Detail.fondo')

	@if ( ! is_null( $solicitud->toAdvanceSeatHistory ) )
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="control-label">Número de Operación, Transacción, Cheque</label>
			<div>
				<input type="text" class="form-control" value="{{ $detalle->num_cuenta }}" disabled>	
			</div>
		</div>
	@endif

	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
		<label class="control-label">Nº de Cuenta del Representante</label>
		<div>
			<input type="text" class="form-control" value="{{$detalle->num_cuenta}}" disabled>
		</div>
	</div>

	<!-- DEPOSITADO -->
	@include('Dmkt.Solicitud.Detail.depositado')

	<!-- Tasa del Día del Deposito -->
	@include('Dmkt.Solicitud.Detail.tasa')

	<!-- Fecha de Descuento al Responsable del Gasto -->
	@if ( ! is_null( $detalle->descuento ) )
        @include('Dmkt.Solicitud.Detail.discount')
    @endif

	@if ( ! is_null( $solicitud->observacion) )
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="control-label">Observación</label>
			<div>
				<textarea class="form-control" disabled>{{$solicitud->observacion}}</textarea>
			</div>
		</div>
	@endif

    <div class="clearfix"></div>
    
    <!-- CLIENTES -->
    @include('Dmkt.Solicitud.Detail.clients')

</section>