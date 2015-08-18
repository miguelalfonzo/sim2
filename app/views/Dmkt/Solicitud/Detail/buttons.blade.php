<div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">  
        @if ( $politicStatus ) 
            <a class="btn btn-success" id="search_responsable">
                Aceptar
            </a>
            <a id="deny_solicitude" class="btn btn-danger">
                Rechazar
            </a>
        @endif

        @if ( $solicitud->id_user_assign == Auth::user()->id ) 
            @if ( $solicitud->id_estado == GASTO_HABILITADO )
                <a id="finish-expense" class="btn btn-success">Terminar</a>
            @else( $solicitud->id_estado == REGISTRADO && $solicitud->devolutions()->where( 'id_estado_devolucion' , DEVOLUCION_POR_REALIZAR )->get()->count() !== 0 )
                <a id="finish-expense" class="btn btn-success">Registrar Devolución</a>
            @endif
        @endif
        @if ( Auth::user()->type == CONT )
            @if( $solicitud->idtiposolicitud == SOL_REP )
                @if($solicitud->id_estado == APROBADO )
                    <a id="enable-deposit" class="btn btn-success">Confirmar</a>
                @endif
            @endif
            @if( $solicitud->id_estado == DEPOSITADO )
                <a id="seat-solicitude" class="btn btn-success">Generar Asiento</a>
            @elseif( $solicitud->id_estado == REGISTRADO )
                @if( $solicitud->idtiposolicitud != REEMBOLSO && is_null( $solicitud->detalle->monto_descuento ) )
                    <a id="confirm-discount" class="btn btn-success">Registrar Descuento por Planilla</a>
                    @if ( $solicitud->devolutions()->whereIn( 'id_estado_devolucion' , array( DEVOLUCION_POR_REALIZAR , DEVOLUCION_POR_VALIDAR ) )->get()->count() == 0 )
                        <a id="confirm-discount" class="btn btn-success" style="display:none">Terminar Registro de Gasto</a>
                    @endif
                @endif
            @endif
        @elseif ( Auth::user()->type == TESORERIA )
            @if ( $solicitud->id_estado == DEPOSITO_HABILITADO )
                <a class="btn btn-success" data-toggle="modal" data-target="#myModal" >Registrar Depósito</a>
            @elseif ( $solicitud->id_estado == REGISTRADO && $solicitud->devolutions()->where( 'id_estado_devolucion' , DEVOLUCION_POR_VALIDAR )->get()->count() !== 0 )
                <a class="btn btn-success" id="confirm-devolucion">Confirmar Devolución</a>
            @endif
        @endif
        <a href="{{URL::to('show_user')}}" class="btn btn-primary">
            Regresar
        </a>
    </div>
</div>