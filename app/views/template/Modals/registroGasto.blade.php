@if ( is_null( $solicitud->registerHistory ) && is_null( $solicitud->toDevolutionHistory ) )
	@if ( is_null( $solicitud->expenseHistory ) )
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
		<h3 class="stage-title">Reg. de Gastos</h3>
		<span class="label label-info">
			@if( is_null( $solicitud->id_user_assign ) )
				RESPONSABLE DEL GASTO
			@else
				@if( $solicitud->asignedTo->type == REP_MED )
					{{ strtoupper($solicitud->asignedTo->rm->full_name) }}
				@elseif( $solicitud->asignedTo->type == SUP )
					{{ strtoupper($solicitud->asignedTo->sup->full_name) }}
				@elseif( $solicitud->asignedTo->type == GER_PROD )
					{{ strtoupper($solicitud->asignedTo->gerProd->full_name) }}
				@else
					{{ strtoupper($solicitud->asignedTo->person->full_name) }}
				@endif
			@endif
		</span>
	</div>
</div>
@if( ! is_null( $solicitud->toDevolutionHistory ) )
	@if( ! is_null( $solicitud->registerHistory ) )
		<div class="stage col-md-3 col-sm-3 success">
			<div class="stage-header stage-success"></div>
	@else
		<div class="stage col-md-3 col-sm-3 pending">
			<div class="stage-header stage-pending"></div>
	@endif
	<div class="stage-content">
		<h3 class="stage-title">Devolucion</h3>
		<span class="label label-info">
			@if( is_null( $solicitud->registerHistory ) )
				TESORERÍA
			@else
				{{ strtoupper($solicitud->registerHistory->createdBy->person->full_name) }}
			@endif
		</span>
	</div>
</div>
@endif

@if ( ( is_null( $solicitud->toGenerateHistory ) && $solicitud->idtiposolicitud != REEMBOLSO ) || ( is_null( $solicitud->toDepositHistory ) && $solicitud->idtiposolicitud == REEMBOLSO ) )
	@if( is_null( $solicitud->registerHistory ) )
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
		<h3 class="stage-title">Asiento de Diario</h3>
		<span class="label label-info">
		@if ( ( is_null( $solicitud->toGenerateHistory ) && $solicitud->idtiposolicitud != REEMBOLSO ) || ( is_null( $solicitud->toDepositHistory ) && $solicitud->idtiposolicitud == REEMBOLSO ) )
			CONTABILIDAD
		@else
			@if ( $solicitud->idtiposolicitud == REEMBOLSO )
				{{ strtoupper( $solicitud->toDepositHistory->createdBy->person->full_name ) }}
			@else
				{{ strtoupper( $solicitud->toGenerateHistory->createdBy->person->full_name ) }}
			@endif
		@endif
		</span>
	</div>
</div>