<table class="table table-hover table-bordered table-condensed dataTable" id="table_actividad" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Tipo Cliente</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $actividad as $activity )
            <tr row-id="{{$activity->id}}" type="actividad">
                <td style="text-align:center">{{$activity->id}}</td>
                <td class="nombre" editable="4" style="text-align:center">{{$activity->nombre}}</td>
                <td class="tipo_cliente" editable=1 style="text-align:center">{{ $activity->client->descripcion }}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit" href="#">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    @if ( is_null( $activity->deleted_at ) )
                        <a class="maintenance-disable" href="#">
                            <span class="glyphicon glyphicon-remove red"></span>
                        </a>
                    @else
                        <a class="maintenance-enable" href="#">
                            <span class="glyphicon glyphicon-ok green"></span>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>