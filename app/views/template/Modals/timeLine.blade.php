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
				{{--@include('template.Modals.policyTimeLine')--}}

                @foreach( $solicitud->investmentPolicy as $flujo )
                    @if($solicitud->detalle->monto_actual > $flujo->policy->desde )
                        @if( isset( $solicitud->orderHistories[ $flujo->policy->orden ] ) && $solicitud->id_estado != CANCELADO && in_array( $solicitud->orderHistories[ $flujo->policy->orden ]->status_to , array( DERIVADO , ACEPTADO , APROBADO , RECHAZADO ) ) )
                            @if ( $solicitud->orderHistories[ $flujo->policy->orden ]->toState->id_estado == R_NO_AUTORIZADO )
                                <div class="stage col-md-3 col-sm-3 rejected">
                                    <div class="stage-header stage-rejected"></div>
                            @else
                                <div class="stage col-md-3 col-sm-3 success">
                                    <div class="stage-header stage-success"></div>
                            @endif
                        @else
                            @if( isset( $solicitud->orderHistories[ $flujo->policy->orden - 1 ] ) &&  $solicitud->orderHistories[ $flujo->policy->orden - 1 ]->status_to != APROBADO && in_array( $solicitud->orderHistories[ $flujo->policy->orden - 1 ]->status_to , array( PENDIENTE , DERIVADO , ACEPTADO ) ) && $solicitud->orderHistories[ $flujo->policy->orden - 1 ]->toState->id_estado != R_NO_AUTORIZADO
                                            && in_array( $solicitud->orderHistories[ $flujo->policy->orden - 1 ]->user_from , array ( REP_MED , SUP , GER_COM , GER_PROD , GER_PROM  , 'GG' ) ) && $solicitud->id_estado != CANCELADO )
                                <div class="stage col-md-3 col-sm-3 pending">
                                    <div class="stage-header stage-pending"></div>
                            @else
                                <div class="stage col-md-3 col-sm-3">
                                    <div class="stage-header"></div>
                            @endif
                        @endif
                                <div class="stage-content">
                                    <p>{{ $flujo->orderHisteries }}</p>
                            @if( is_null( $flujo->policy->desde ) && is_null( $flujo->policy->hasta ) )
                                    <h3 class="stage-title">Validaci&oacute;n {{$flujo->policy->tipo_usuario}} .</h3>
                            @else
                                    <h3 class="stage-title" style="white-space:nowrap">Aprobaci&oacute;n</h3>
                            @endif
                                    <!-- <div class="stage-info" style="white-space:nowrap"> -->
                                    <span class="label label-info">
                                @if( isset( $solicitud->orderHistories[ $flujo->policy->orden ] ) && in_array( $solicitud->orderHistories[ $flujo->policy->orden ]->status_to , array( DERIVADO , ACEPTADO , APROBADO , RECHAZADO ) ) )
                                    @if ( $solicitud->orderHistories[ $flujo->policy->orden ]->user_from == REP_MED )
                                    {{ strtoupper($solicitud->orderHistories[ $flujo->policy->orden ]->createdBy->rm->full_name) }}
                                    @elseif ( $solicitud->orderHistories[ $flujo->policy->orden ]->user_from == SUP )
                                    {{ strtoupper($solicitud->orderHistories[ $flujo->policy->orden ]->createdBy->sup->full_name) }}
                                    @elseif ( $solicitud->orderHistories[ $flujo->policy->orden ]->user_from == GER_PROD )
                                    {{ strtoupper($solicitud->orderHistories[ $flujo->policy->orden ]->createdBy->gerProd->full_name) }}
                                    @else
                                    {{ strtoupper($solicitud->orderHistories[ $flujo->policy->orden ]->createdBy->person->full_name) }}
                                    @endif
                                @else
                                    {{ strtoupper($flujo->policy->userType->descripcion) }}
                                @endif
                                    </span>
                                                                    <!-- </div> -->
                                </div>
                            </div>
                    @endif
                @endforeach
                {{---------------------------------------------------------}}
				{{--@include('template.Modals.toDepositTimeLine')--}}

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
                                <h3 class="stage-title">Validaci&oacute;n Cont.</h3>
                @else
                                <h3 class="stage-title" style="white-space:nowrap">Habilitaci&oacute;n Fondo Inst.</h3>
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
                {{---------------------------------------------------------}}
				@if ( $solicitud->detalle->id_motivo != REEMBOLSO )
{{--					@include( 'template.Modals.depSeatTimeLine' )--}}
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
                        <!-- <div class="stage-info" style="white-space:nowrap"> -->
                        <span class="label label-info">
                    @if ( is_null( $solicitud->toAdvanceSeatHistory ) )
                            TESORERÍA
                        @else
                            {{ strtoupper($solicitud->toAdvanceSeatHistory->createdBy->person->full_name) }}
                        @endif
                        </span>
                                                    <!-- </div> -->
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
                            <!-- <div class="stage-info" style="white-space:nowrap"> -->
                            <span class="label label-info">
                                @if ( is_null( $solicitud->expenseHistory ) )
                                    CONTABILIDAD
                                @else
                                    {{ strtoupper($solicitud->expenseHistory->createdBy->person->full_name) }}
                                @endif
                            </span>
                            <!-- </div> -->
                        </div>
                    </div>
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
