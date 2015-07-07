@if ( ( ( $solicitud->detalle->id_motivo != REEMBOLSO || $solicitud->idtiposolicitud == SOL_INST )  && is_null( $solicitud->toDepositHistory ) ) || 
( $solicitud->detalle->id_motivo == REEMBOLSO && is_null( $solicitud->expenseHistory ) ) )
	@if ( ( is_null( $solicitud->approvedHistory ) && $solicitud->idtiposolicitud == SOL_REP ) || 
	( is_null( $solicitud->toPendingHistory ) && $solicitud->idtiposolicitud == SOL_INST ) || $solicitud->id_estado == CANCELADO )
		<div class="stage col-md-3 col-sm-3">
			<div class="stage-header"></div>	
	@else
		<div class="stage col-md-3 col-sm-3 pending">
			<div class="stage-header stage-pending"></div>
	@endif
@else
	<div class="stage col-md-3 col-sm-3 success">
		<div class="stage-header stage-success"></div>
@endif
	<div class="stage-content">
		@if ( $solicitud->idtiposolicitud == SOL_REP )
			<h3 class="stage-title">Validación Cont.</h3>
		@else
			<h3 class="stage-title" style="white-space:nowrap">Habilitación Fondo Inst.</h3>
		@endif		
		<!-- <div class="stage-info" style="white-space:nowrap"> -->
			<span class="label label-info">
			@if ( is_null( $solicitud->toDepositHistory ) )
				@if ( $solicitud->idtiposolicitud == SOL_REP )
					CONTABILIDAD
				@else
					ASISTENTE GERENCIA
				@endif
			@else
				{{ strtoupper($solicitud->toDepositHistory->createdBy->person->full_name) }}
			@endif
			</span>
		<!-- </div> -->
	</div>
</div>