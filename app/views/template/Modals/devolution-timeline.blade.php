@if( $devolutions->count() !== 0 )
	<div class="container-fluid hide">
		<h6 class="text-center">DEVOLUCION</h6>
		<div class="stage-container">
			<div class="stage col-md-3 col-sm-3 success">
				<div class="stage-header stage-success"></div>
				<div class="stage-content">
					<h3 class="stage-title">
						@if( $devolutions[ 0 ]->histories[ 0 ]->status_to == 2 )
							Inicio por Saldo Pendiente
						@else
							Inicio por Revision de Documentos
						@endif
					</h3>
					<span class="label label-info">{{ strtoupper( $devolutions[ 0 ]->createdBy->personal->full_name ) }}</span>
					<span class="label label-info">
						@if ( $devolutions[ 0 ]->histories[ 0 ]->status_to == 2 )
							{{ $solicitud->expenses()->orderBy( 'updated_at' , 'DESC')->first()->updated_at }}      
						@else
							{{ $devolution[ 0 ]->created_at }}
						@endif
					</span>
				</div>
			</div>
			@foreach( $devolutions as $devolution )
				@foreach( $devolution->histories as $devolutionHistory )
					<div class="stage col-md-3 col-sm-3 success">
						<div class="stage-header stage-success"></div>
						<div class="stage-content">
							<h3 class="stage-title">
								@if( $devolutionHistory->status_to == 1 )
									Inicio por Revision de Documentos
								@elseif( $devolutionHistory->status_to == 2 )
									Pago
								@elseif( $devolutionHistory->status_to == 3 )
									Confirmacion
								@endif
							</h3>
							<span class="label label-info">
								{{ strtoupper( $devolutionHistory->updatedBy->personal->full_name ) }}
							</span>
							<span class="label label-info">{{ $devolutionHistory->updated_at }}</span>      	
						</div>
					</div>
				@endforeach
				@if( $devolution->id_estado_devolucion == 1 )
					---
				@elseif( $devolution->id_estado_devolucion == 2 )
					<div class="stage col-md-3 col-sm-3 pending">
						<div class="stage-header stage-pending"></div>
						<div class="stage-content">
							<h3 class="stage-title">Confirmacion</h3>
							<span class="label label-info">
								TESORERIA
							</span>
						</div>
					</div>
				@endif
			@endforeach
		</div>
	</div>
@endif