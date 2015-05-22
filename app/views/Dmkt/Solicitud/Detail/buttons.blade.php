<div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">
        
        @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) )
              && $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario === Auth::user()->type
              && in_array( Auth::user()->id , $solicitud->managerEdit->lists( 'id_gerprod' ) ) )
            <a class="btn btn-success" id="search_responsable">
                Aceptar
            </a>
            <a id="deny_solicitude" class="btn btn-danger">
                Rechazar
            </a>
        @endif

        @if( Auth::user()->type == REP_MED )
            @if ( $solicitud->id_estado == GASTO_HABILITADO && $solicitud->iduserasigned == Auth::user()->id )
                <a id="finish-expense" class="btn btn-success">
                    Terminar
                </a>
            @endif
        @elseif ( Auth::user()->type == ASIS_GER )
            @if ( $solicitud->id_estado == GASTO_HABILITADO && $solicitud->iduserasigned == Auth::user()->id )
                <a id="finish-expense" class="btn btn-success">
                    Terminar
                </a>
            @endif
        @elseif ( Auth::user()->type == CONT )
            @if( $solicitud->idtiposolicitud == SOL_REP )
                @if($solicitud->id_estado == APROBADO )
                    <a id="enable-deposit" class="btn btn-success">Confirmar</a>
                @endif
            @endif
            @if( $solicitud->id_estado == DEPOSITADO )
                <a id="seat-solicitude" class="btn btn-success">Generar Asiento</a>
            @endif
        @elseif ( Auth::user()->type == TESORERIA && $solicitud->id_estado == DEPOSITO_HABILITADO )
            <a class="btn btn-success" data-toggle="modal" data-target="#myModal" >Registrar Depósito</a>
        @endif
        <a href="{{URL::to('show_user')}}" class="btn btn-primary">
            Regresar
        </a>
    </div>
</div>