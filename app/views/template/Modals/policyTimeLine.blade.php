@foreach( $solicitud->policies()->orderBy( 'orden' , 'ASC')->get() as $flujo )
	@if( isset( $solicitud->orderHistories[ $flujo->orden ] ) && $solicitud->id_estado != CANCELADO && in_array( $solicitud->orderHistories[ $flujo->orden ]->status_to , array( DERIVADO , ACEPTADO , APROBADO , RECHAZADO ) ) )
		@if ( $solicitud->orderHistories[ $flujo->orden ]->toState->id_estado == R_NO_AUTORIZADO )
			<div class="stage col-md-3 col-sm-3 rejected">
				<div class="stage-header stage-rejected"></div>
		@else
			<div class="stage col-md-3 col-sm-3 success">
				<div class="stage-header stage-success"></div>
		@endif
	@else
		@if( isset( $solicitud->orderHistories[ $flujo->orden - 1 ] ) &&  $solicitud->orderHistories[ $flujo->orden - 1 ]->status_to != APROBADO && in_array( $solicitud->orderHistories[ $flujo->orden - 1 ]->status_to , array( PENDIENTE , DERIVADO ) ) && $solicitud->orderHistories[ $flujo->orden - 1 ]->toState->id_estado != R_NO_AUTORIZADO 
		&& in_array( $solicitud->orderHistories[ $flujo->orden - 1 ]->user_from , array ( GER_COM , 'GG' , GER_PROM , REP_MED ) ) )
			<div class="stage col-md-3 col-sm-3 pending">
				<div class="stage-header stage-pending"></div>
		@else
			<div class="stage col-md-3 col-sm-3">
				<div class="stage-header"></div>
		@endif
	@endif
		<div class="stage-content">
			@if( is_null( $flujo->desde ) && is_null( $flujo->hasta ) )
				<h3 class="stage-title">Validación {{$flujo->tipo_usuario}}.</h3>
			@else
				<h3 class="stage-title" style="white-space:nowrap">Aprob. de {{{ is_null($flujo->desde) ? 'S/.0' : 'S/.' . $flujo->desde }}} {{{ is_null( $flujo->hasta ) ? '' : ' a S/.' . $flujo->hasta }}}</h3>
			@endif
			<div class="stage-info" style="white-space:nowrap">
				@if( isset( $solicitud->orderHistories[ $flujo->orden ] ) && in_array( $solicitud->orderHistories[ $flujo->orden ]->status_to , array( DERIVADO , ACEPTADO , APROBADO , RECHAZADO ) ) )
					@if ( $solicitud->orderHistories[ $flujo->orden ]->user_from == REP_MED )
						{{ $solicitud->orderHistories[ $flujo->orden ]->createdBy->rm->full_name}}
					@elseif ( $solicitud->orderHistories[ $flujo->orden ]->user_from == SUP )
						{{ $solicitud->orderHistories[ $flujo->orden ]->createdBy->sup->full_name}}
					@elseif ( $solicitud->orderHistories[ $flujo->orden ]->user_from == GER_PROD )
						{{ $solicitud->orderHistories[ $flujo->orden ]->createdBy->gerProd->full_name}}
					@else
						{{ $solicitud->orderHistories[ $flujo->orden ]->createdBy->person->full_name}}
					@endif
				@else
					{{$flujo->userType->descripcion}}
				@endif
			</div>
		</div>
	</div>
@endforeach