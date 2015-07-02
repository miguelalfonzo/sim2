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
		<div class="stage-info" style="white-space:nowrap">
			@if( is_null( $solicitud->id_user_assign ) )
				Responsable del Gasto
			@else
				@if( $solicitud->asignedTo->type == REP_MED )
					{{$solicitud->asignedTo->rm->full_name}}
				@elseif( $solicitud->asignedTo->type == SUP )
					{{$solicitud->asignedTo->sup->full_name}}
				@elseif( $solicitud->asignedTo->type == GER_PROD )
					{{$solicitud->asignedTo->gerProd->full_name}}
				@else
					{{$solicitud->asignedTo->person->full_name}}
				@endif
			@endif
		</div> 
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
		<div class="stage-info" style="white-space:nowrap">
			@if ( is_null( $solicitud->toGenerateHistory ) )
				Contabilidad
			@else
				{{$solicitud->toGenerateHistory->createdBy->person->full_name}}
			@endif
		</div> 
	</div>
</div>