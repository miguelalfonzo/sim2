<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">Clientes</div>
        <ul class="list-group">                 
            @foreach( $solicitud->clients as $client )
                <li class="list-group-item">
                    @if ( is_null( $client->id_cliente) )
                       No hay cliente Asignado
                    @else
                        {{ $client->{$client->clientType->relacion}->full_name }}
                    @endif
                </li>
            @endforeach
    </div>
</div>