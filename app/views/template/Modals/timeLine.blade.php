<!-- Large modal -->
<div class="timeLineModal">
	<div class="container-fluid hide">
		<!-- <div class="thumbnail"> -->
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
							<!-- <div class="stage-info" style="white-space:nowrap"> -->
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
							<!-- </div> -->
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
							<!-- <div class="stage-info" style="white-space:nowrap"> -->
								<span class="label label-info">
								{{ strtoupper($solicitud->createdBy->person->full_name) }}
								</span>
							</div>
						</div>
					</div>
					@include('template.Modals.toDepositTimeLine')
					@include( 'template.Modals.depSeatTimeLine' )
				@endif
				@include( 'template.Modals.registroGasto')
			</div>
			<div class="clearfix"></div>
			<br>
		<!-- </div> -->
	</div>
</div>
