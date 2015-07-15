<!-- Large modal -->
<div class="timeLineModal">
	<div class="container-fluid hide">
		<div class="stage-container">
			@if ( $solicitud->idtiposolicitud == SOL_REP )
				@if ( $solicitud->id_estado == CANCELADO )
					<div class="stage col-md-3 col-sm-3 rejected">
						<div class="stage-header stage-rejected"></div>
				@else
					<div class="stage col-md-3 col-sm-3 success">
						<div class="stage-header stage-success"></div>
				@endif
					<div class="stage-content">
						<h3 class="stage-title">Inicio Solicitud</h3>
						<span class="label label-info">
							@if( $solicitud->createdBy->type == REP_MED )
								{{ strtoupper($solicitud->createdBy->rm->full_name) }}
							@elseif( $solicitud->createdBy->type == SUP )
								{{ strtoupper($solicitud->createdBy->sup->full_name) }}
							@elseif( $solicitud->createdBy->type == GER_PROD )
								{{ strtoupper($solicitud->createdBy->gerProd->full_name) }}
							@else
								{{ strtoupper($solicitud->createdBy->person->full_name) }}
							@endif
						</span>
					</div>
				</div>
				@include('template.Modals.policyTimeLine')
				@include('template.Modals.toDepositTimeLine')
				@if ( $solicitud->detalle->id_motivo != REEMBOLSO )
					@include( 'template.Modals.depSeatTimeLine' )
				@endif
			@elseif( $solicitud->idtiposolicitud == SOL_INST )
				@if ( $solicitud->id_estado == CANCELADO )
					<div class="stage col-md-3 col-sm-3 rejected">
						<div class="stage-header stage-rejected"></div>
				@else
					<div class="stage col-md-3 col-sm-3 success">
						<div class="stage-header stage-success"></div>
				@endif
					<div class="stage-content">
						<h3 class="stage-title" style="white-space:nowrap">Inicio Fondo Institucional</h3>
						<span class="label label-info">
							{{ strtoupper($solicitud->createdBy->person->full_name) }}
						</span>
					</div>
				</div>
				@include('template.Modals.toDepositTimeLine')
				@include( 'template.Modals.depSeatTimeLine' )
			@endif
			@include( 'template.Modals.registroGasto')
			@if( $solicitud->detalle->id_motivo == REEMBOLSO )
				@if ( is_null( $solicitud->toGenerateHistory ) )
					@if ( is_null( $solicitud->toDepositHistory ) )
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
					<h3 class="stage-title" style="white-space:nowrap">Deposito del Reembolso</h3>
					<span class="label label-info">
						@if ( is_null( $solicitud->toGenerateHistory ) )
							TESORERIA
						@else
							{{ strtoupper($solicitud->toGenerateHistory->createdBy->person->full_name) }}
						@endif
					</span>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
