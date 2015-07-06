@if ( is_null( $solicitud->registerHistory ) )
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
		<!-- <div class="stage-info" style="white-space:nowrap"> -->
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
		<!-- </div>  -->
	</div>
</div>
@if ( is_null( $solicitud->toGenerateHistory ) )
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
		<h3 class="stage-title">Asiento de Díario</h3>
		<!-- <div class="stage-info" style="white-space:nowrap"> -->
			<span class="label label-info">
			@if ( is_null( $solicitud->toGenerateHistory ) )
				CONTABILIDAD
			@else
				{{ strtoupper($solicitud->toGenerateHistory->createdBy->person->full_name) }}
			@endif
			</span>
		<!-- </div>  -->
	</div>
</div>