<div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">
        @if ( Auth::user()->type == SUP )
            @if( $solicitude->idestado == PENDIENTE )
                <a class="btn btn-primary" id="search_responsable">
                    Aceptar
                </a>
                <a id="deny_solicitude" name="button1id" class="btn btn-primary">
                    Rechazar
                </a>
            @endif
        @elseif ( Auth::user()->type == GER_PROD )
            @if($solicitude->idestado == DERIVADO)
                <a id="search_responsable" class="btn btn-primary">
                    Aceptar
                </a>
                <a id="deny_solicitude" name="button1id" class="btn btn-primary">
                    Rechazar
                </a>
            @endif
        @elseif ( Auth::user()->type == GER_COM )
            @if($solicitude->idestado == ACEPTADO)
                <a name="button1id" data-token ="{{$solicitude->token}}" class="btn btn-primary approved_solicitude">
                    Aprobar
                </a>
                <a id="deny_solicitude" name="button1id" class="btn btn-primary">
                    Rechazar
                </a>
            @endif
        @elseif ( Auth::user()->type == CONT )
            @if($solicitude->idestado == APROBADO)
                <a id="enable-deposit" class="btn btn-success">Habilitar Depósito</a>
            @elseif( $solicitude->idestado == DEPOSITADO )
                <a id="seat-solicitude" class="btn btn-success">Generar Asiento Solicitud</a>
            @endif
        @elseif ( Auth::user()->type == TESORERIA && $solicitude->idestado == DEPOSITO_HABILITADO )
            <a class="btn btn-success" data-toggle="modal" data-target="#myModal" >Registrar Depósito</a>
        @endif
        <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">
            Cancelar
        </a>
    </div>
</div>