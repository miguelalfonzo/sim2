@foreach( $solicitud->policies()->orderBy( 'orden' , 'ASC')->get() as $flujo )
	@if($solicitud->detalle->monto_actual > $flujo->desde)
	@if( isset( $solicitud->orderHistories[ $flujo->orden ] ) && $solicitud->id_estado != CANCELADO && in_array( $solicitud->orderHistories[ $flujo->orden ]->status_to , array( DERIVADO , ACEPTADO , APROBADO , RECHAZADO ) ) )
		@if ( $solicitud->orderHistories[ $flujo->orden ]->toState->id_estado == R_NO_AUTORIZADO )
			<div class="stage col-md-3 col-sm-3 rejected">
				<div class="stage-header stage-rejected"></div>
		@else
			<div class="stage col-md-3 col-sm-3 success">
				<div class="stage-header stage-success"></div>
		@endif
	@else
		@if( isset( $solicitud->orderHistories[ $flujo->orden - 1 ] ) &&  $solicitud->orderHistories[ $flujo->orden - 1 ]->status_to != APROBADO && in_array( $solicitud->orderHistories[ $flujo->orden - 1 ]->status_to , array( PENDIENTE , DERIVADO , ACEPTADO ) ) && $solicitud->orderHistories[ $flujo->orden - 1 ]->toState->id_estado != R_NO_AUTORIZADO 
		&& in_array( $solicitud->orderHistories[ $flujo->orden - 1 ]->user_from , array ( REP_MED , SUP , GER_COM , GER_PROD , GER_PROM  , 'GG' ) ) && $solicitud->id_estado != CANCELADO )
			<div class="stage col-md-3 col-sm-3 pending">
				<div class="stage-header stage-pending"></div>
		@else
			<div class="stage col-md-3 col-sm-3">
				<div class="stage-header"></div>
		@endif
	@endif
		<div class="stage-content">
<<<<<<< HEAD
			<p>{{ $flujo->orderHisteries }}</p>
			@if( is_null( $flujo->policy->desde ) && is_null( $flujo->policy->hasta ) )
				<h3 class="stage-title">Validación {{$flujo->policy->tipo_usuario}}.</h3>
=======
			@if( is_null( $flujo->desde ) && is_null( $flujo->hasta ) )
				<h3 class="stage-title">Validación {{$flujo->tipo_usuario}}.</h3>
>>>>>>> 6af0523bc23035a57ae9f5a8e90a9a65764e377d
			@else
				<h3 class="stage-title" style="white-space:nowrap">Aprobacion</h3>
			@endif
			<!-- <div class="stage-info" style="white-space:nowrap"> -->
				<span class="label label-info">
				@if( isset( $solicitud->orderHistories[ $flujo->orden ] ) && in_array( $solicitud->orderHistories[ $flujo->orden ]->status_to , array( DERIVADO , ACEPTADO , APROBADO , RECHAZADO ) ) )
					@if ( $solicitud->orderHistories[ $flujo->orden ]->user_from == REP_MED )
						{{ strtoupper($solicitud->orderHistories[ $flujo->orden ]->createdBy->rm->full_name) }}
					@elseif ( $solicitud->orderHistories[ $flujo->orden ]->user_from == SUP )
						{{ strtoupper($solicitud->orderHistories[ $flujo->orden ]->createdBy->sup->full_name) }}
					@elseif ( $solicitud->orderHistories[ $flujo->orden ]->user_from == GER_PROD )
						{{ strtoupper($solicitud->orderHistories[ $flujo->orden ]->createdBy->gerProd->full_name) }}
					@else
						{{ strtoupper($solicitud->orderHistories[ $flujo->orden ]->createdBy->person->full_name) }}
					@endif
				@else
					{{ strtoupper($flujo->userType->descripcion) }}
				@endif
				</span>
			<!-- </div> -->
		</div>
	</div>
	@endif
@endforeach