<!-- Large modal -->
<div class="timeLineModal">
	<div class="container-fluid hide">
		<div class="thumbnail">
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
							<div class="stage-info" style="white-space:nowrap">
								@if( $solicitud->createdBy->type == REP_MED )
									{{$solicitud->createdBy->rm->full_name}}
								@elseif( $solicitud->createdBy->type == SUP )
									{{$solicitud->createdBy->sup->full_name}}
								@elseif( $solicitud->createdBy->type == GER_PROD )
									{{$solicitud->createdBy->gerProd->full_name}}
								@else
									{{$solicitud->createdBy->person->full_name}}
								@endif
							</div>
						</div>
					</div>
					@include('template.Modals.policyTimeLine')
					@if ( is_null( $solicitud->toDepositHistory ) )
						@if ( is_null( $solicitud->approvedHistory ) )
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
							<h3 class="stage-title">Validación C.</h3>
							<div class="stage-info" style="white-space:nowrap">
								@if ( is_null( $solicitud->toDepositHistory ) )
									Contabilidad
								@else
									{{$solicitud->toDepositHistory->createdBy->person->full_name}}
								@endif
							</div>
						</div>
					</div>
				@if ( $solicitud->detalle->id_motivo != REEMBOLSO )
						@include( 'template.Modals.depSeatTimeLine' )
					@endif
				@elseif( $solicitud->idtiposolicitud == SOL_INST )
					<div class="stage col-md-3 col-sm-3">
						<div class="stage-header"></div>
						<div class="stage-content">
							<h3 class="stage-title">Reg. del F. Institucional</h3>
							<div class="stage-info">
								Informacion Registrada por el Asistente de Gerencia
							</div>
							<div class="stage-info">
								{{$solicitud->createdBy->person->full_name}}
							</div>	
						</div>
					</div>
					<div class="stage col-md-3 col-sm-3">
						<div class="stage-header"></div>
						<div class="stage-content">
							<h3 class="stage-title">Habilitacion del Dep.</h3>
							<div class="stage-info">
								Habilitacion del Fondo por Asis. Gerencia
							</div>
							@if ( ! is_null( $solicitud->toDepositHistory ) )
								<div class="stage-info">
									{{$solicitud->toDepositHistory->createdBy->person->full_name}}
								</div>
							@endif
						</div>
					</div>
					@include( 'template.Modals.depSeatTimeLine' )
				@endif
				@include( 'template.Modals.registroGasto')
			</div>
			<div class="clearfix"></div>
			<br>
		</div>
	</div>
</div>
