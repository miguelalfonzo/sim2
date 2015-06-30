<div class="form-group col-sm-12 col-md-6 col-lg-6">
    <div style="padding:0 15px" >
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Clientes</h3>
            </div>
            <div class="panel-body">
                @foreach( $solicitud->clients as $client )
                    @if ( is_null( $client->id_cliente) )
                        <div class="form-group ">
                            <div>
                                <input class="form-control input-md" type="text"
                                value="No hay cliente Asignado" readonly>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <div>
                                <input class="form-control input-md" type="text"
                                value="{{ $client->{$client->clientType->relacion}->full_name }}" readonly>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>