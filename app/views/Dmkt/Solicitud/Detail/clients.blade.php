<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">Clientes

             @if ( isset( $tipo_usuario ) && in_array( $tipo_usuario , array( GER_PROD, GER_PROM , GER_COM , GER_GER  ) ) )
                <label class="pull-right">
                 <input type="checkbox" name="modificacion-clientes" id="is-client-change"> Modificar
                 
                </label>
            @endif
        </div>
        <ul class="list-group" id="list-client">                 
            @foreach( $solicitud->clients as $client )
                <li class="list-group-item">
                    @if ( is_null( $client->id_cliente) )
                       No hay cliente Asignado
                    @else
                        {{ $client->{$client->clientType->relacion}->full_name }}
                    @endif
                    <span class="badge">{{$client->clientType->descripcion}}</span>
                </li>
            @endforeach
        </ul>
        @if ( isset( $tipo_usuario ) && in_array( $tipo_usuario , array( GER_PROD, GER_PROM , GER_COM , GER_GER  ) ) )
        <ul class="list-group" id="clientes" style="display: none">                 
            @foreach( $solicitud->clients as $client )
             @if ( is_null( $client->id_cliente) )
                       No hay cliente Asignado
                    @else
                <li class="list-group-item" tipo_cliente="{{ $client->clientType->id }}" pk="{{ $client->id_cliente }}">
                        <b>{{ $client->{$client->clientType->relacion}->full_name }}</b>
                        <span class="badge" >{{$client->clientType->descripcion}}</span>
                        <button type="button" class="btn-delete-client" style="z-index:2">
                            <span class="glyphicon glyphicon-remove red" style="margin-left:20px ; float:right;"></span>
                        </button>
                </li>
                @endif
            @endforeach
        </ul>
        <button type="button" style="display:none" id="open_modal_add_client" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#approval-client-modal">
                Agregar Clientes
        </button>
        @endif
    </div>
</div>
@if ( $politicStatus && isset( $tipo_usuario ) && in_array( $tipo_usuario , array( GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
    @include('Dmkt.Solicitud.Section.modal-select-client')
@endif