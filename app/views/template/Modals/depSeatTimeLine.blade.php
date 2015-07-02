@if ( is_null( $solicitud->toAdvanceSeatHistory ) )
	@if( is_null( $solicitud->toDepositHistory ) )
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
		<h3 class="stage-title">Deposito del Anticipo</h3>	
		<div class="stage-info" style="white-space:nowrap">
			@if ( is_null( $solicitud->toAdvanceSeatHistory ) )
				Tesorería
			@else
				{{$solicitud->toAdvanceSeatHistory->createdBy->person->full_name}}
			@endif
		</div>
	</div>
</div>
@if ( is_null( $solicitud->expenseHistory ) )
	@if ( is_null( $solicitud->toAdvanceSeatHistory ) )	
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
		<h3 class="stage-title">Asiento de Anticipo</h3>
		<div class="stage-info" style="white-space:nowrap">
			@if ( is_null( $solicitud->expenseHistory ) )
				Contabilidad
			@else
				{{$solicitud->expenseHistory->createdBy->person->full_name}}
			@endif
		</div>
	</div>
</div>