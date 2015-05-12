<div class="form-group col-sm-12 col-md-6 col-lg-6">
    <div style="padding:0 15px" >
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Clientes</h3>
            </div>
            <div class="panel-body">
                @foreach($solicitud->clients as $client)
                    @if (is_null($client->idcliente))
                        <div class="form-group ">
                            <div>
                                <input class="form-control input-md" type="text"
                                value="No hay cliente Asignado" readonly>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <div>
                                @if ( $client->from_table == TB_DOCTOR )
                                    <input class="form-control input-md" type="text"
                                    value="{{$client->doctors->full_name}}" readonly>
                                @elseif ( $client->from_table == 'FICPEF.PERSONAJUR' )
                                    <input class="form-control input-md" type="text"
                                    value="{{$client->farmacia->full_name}}" readonly>
                                @elseif ( $client->from_table == 'FICPE.PERSONAJUR' )
                                    <input class="form-control input-md" type="text"
                                    value="{{$client->institution->full_name}}" readonly>
                                @elseif ( $client->from_table == 'VTADIS.CLIENTES')
                                    <input class="form-control input-md" type="text"
                                    value="{{$client->distrimed->full_name}}" readonly>
                                @else
                                    <input class="form-control input-md" type="text"
                                    value="Repositorio Desconocido" readonly>    
                                @endif                                
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>