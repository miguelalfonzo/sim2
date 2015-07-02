@foreach( $solicitud->policies()->orderBy( 'orden' , 'ASC')->get() as $flujo )
	@if ( is_null( $solicitud->fromUserHistory( $flujo->tipo_usuario ) ) )
		<div class="stage col-md-3 col-sm-3">
			<div class="stage-header"></div>
	@else
		@if ( $solicitud->fromUserHistory( $flujo->tipo_usuario )->toState->id_estado == R_NO_AUTORIZADO )
			<div class="stage col-md-3 col-sm-3 rejected">
				<div class="stage-header stage-rejected"></div>
		@else
			<div class="stage col-md-3 col-sm-3 success">
				<div class="stage-header stage-success"></div>
		@endif
	@endif
		<div class="stage-content">
			@if( is_null( $flujo->desde ) && is_null( $flujo->hasta ) )
				<h3 class="stage-title">Validación</h3>
			@else
				<h3 class="stage-title" style="white-space:nowrap">Aprob. desde {{{ is_null($flujo->desde) ? 'S/.0' : 'S/.' . $flujo->desde }}} {{{ is_null( $flujo->hasta ) ? '' : ' hasta S/.' . $flujo->hasta }}}</h3>
			@endif
			<div class="stage-info" style="white-space:nowrap">
				@if( !is_null( $solicitud->histories[ $flujo->orden ] ) )
					@if ( $solicitud->histories[ $flujo->orden ]->user_from == REP_MED )
						{{ $solicitud->histories[ $flujo->orden ]->createdBy->rm->full_name}}
					@elseif ( $flujo->tipo_usuario == SUP )
						{{ $solicitud->histories[ $flujo->orden ]->createdBy->sup->full_name}}
					@elseif ( $flujo->tipo_usuario == GER_PROD )
						{{ $solicitud->histories[ $flujo->orden ]->createdBy->gerProd->full_name}}
					@else
						{{ $solicitud->histories[ $flujo->orden ]->createdBy->person->full_name}}
					@endif
				@else
					{{$flujo->userType->descripcion}}
				@endif
			</div>
		</div>
	</div>
@endforeach