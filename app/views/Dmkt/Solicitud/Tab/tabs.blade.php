<div class="content">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active">
             @include('Dmkt.Solicitud.tabSolicitud')
        </li>
        @if ( Auth::user()->type == CONT && ( ( ! is_null( $solicitud->expenseHistory ) && $solicitud->idtiposolicitud != REEMBOLSO )  || 
            ( ( $solicitud->id_estado == GENERADO && $solicitud->idtiposolicitud != REEMBOLSO ) || ( ! is_null( $solicitud->toGenerateHistory ) && $solicitud->idtiposolicitud == REEMBOLSO ) ) ) )
            <li>
                <a href="#seats-tab" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon>
                    Asientos
                </a>
            </li>
        @endif
        @if ( ! is_null( $solicitud->registerHistory ) && ( Auth::user()->type == CONT || Auth::user()->id == $solicitud->id_user_assign ) )
            <li>
                <a href="#expense-tab" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon>
                    Documentos
                </a>
            </li>
        @endif
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="solicitud" style="margin-top:20px">
            @if ( $solicitud->idtiposolicitud == SOL_REP )
                @include('Dmkt.Solicitud.Representante.detail')
            @elseif ( $solicitud->idtiposolicitud == SOL_INST )
                @include('Dmkt.Solicitud.Institucional.detail')
            @endif
        </div>
        @if ( ( ( ! is_null( $solicitud->expenseHistory ) && $solicitud->idtiposolicitud != REEMBOLSO ) && Auth::user()->type == CONT ) || 
            ( ( $solicitud->id_estado == GENERADO && $solicitud->idtiposolicitud != REEMBOLSO ) || ( ! is_null( $solicitud->toGenerateHistory ) && $solicitud->idtiposolicitud == REEMBOLSO ) ) )
            <div class="tab-pane fade" id="seats-tab" style="margin-top:20px; margin-bottom:20px">
                @if ( ( ! is_null( $solicitud->expenseHistory ) && $solicitud->idtiposolicitud != REEMBOLSO ) && Auth::user()->type == CONT )
                    <h1>Asiento de Anticipo</h1>
                    @include( 'Dmkt.Cont.advance-table')
                @endif
                @if ( ( $solicitud->id_estado == GENERADO && $solicitud->idtiposolicitud != REEMBOLSO ) || ( ! is_null( $solicitud->toGenerateHistory ) && $solicitud->idtiposolicitud == REEMBOLSO ) )
                    <h1>Asiento de Diario</h1>
                    @include( 'Dmkt.Cont.daily-table')        
                @endif        
            </div>
        @endif
        @if ( ! is_null( $solicitud->registerHistory ) && ( Auth::user()->type == CONT || Auth::user()->id == $solicitud->id_user_assign ) )
            <div class="tab-pane fade" id="expense-tab" style="margin-top:20px; margin-bottom:20px">
                @include( 'Dmkt.Solicitud.Section.gasto-table' )
            </div>    
        @endif
    </div>
</div>