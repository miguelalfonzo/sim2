<div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">
        @if( Auth::user()->type == REP_MED )
            @if ( $solicitud->idestado == GASTO_HABILITADO && $solicitud->iduserasigned == Auth::user()->id )
                <a id="finish-expense" class="btn btn-success">
                    Terminar
                </a>
            @endif
        @elseif ( Auth::user()->type == ASIS_GER )
            @if ( $solicitud->idestado == GASTO_HABILITADO && $solicitud->iduserasigned == Auth::user()->id )
                <a id="finish-expense" class="btn btn-success">
                    Terminar
                </a>
            @endif
        @elseif ( Auth::user()->type == SUP )
            @if( $solicitud->idestado == PENDIENTE )
                <a class="btn btn-primary" id="search_responsable">
                    Aceptar
                </a>
                <a class="btn btn-primary" id="derived">
                    Derivar
                </a>
                <a class="btn btn-danger" id="deny_solicitude">
                    Rechazar
                </a>
            @endif
        @elseif ( Auth::user()->type == GER_PROD )
            @if($solicitud->idestado == DERIVADO)
                <a id="search_responsable" class="btn btn-primary">
                    Aceptar
                </a>
                <a id="deny_solicitude" name="button1id" class="btn btn-primary">
                    Rechazar
                </a>
            @endif
        @elseif ( Auth::user()->type == GER_COM )
            @if($solicitud->idestado == ACEPTADO)
                <a name="button1id" data-token ="{{$solicitud->token}}" class="btn btn-primary approved_solicitude">
                    Aprobar
                </a>
                <a id="deny_solicitude" name="button1id" class="btn btn-primary">
                    Rechazar
                </a>
            @endif
        @elseif ( Auth::user()->type == CONT )
            @if( $solicitud->idtiposolicitud == SOL_REP )
                @if($solicitud->idestado == APROBADO)
                    <a id="enable-deposit" class="btn btn-success">Habilitar Depósito</a>
                @endif
            @endif
            @if( $solicitud->idestado == DEPOSITADO )
                <a id="seat-solicitude" class="btn btn-success">Generar Asiento Diario</a>
            @endif
        @elseif ( Auth::user()->type == TESORERIA && $solicitud->idestado == APROBADO )
            <a class="btn btn-success" data-toggle="modal" data-target="#myModal" >Registrar Depósito</a>
        @endif
        <a id="button2id" href="{{URL::to('show_user')}}" class="btn btn-primary">
            Regresar
        </a>
    </div>
</div>