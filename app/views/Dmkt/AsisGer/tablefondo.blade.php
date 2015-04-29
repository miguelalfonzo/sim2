<table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>SISOL - Hospital</th>
            <th>Depositar a</th>
            <th>Nº Cuenta Bagó Bco. Crédito</th>
            <th>Total a depositar</th>
            <th>Supervisor</th>
            @if ( isset( $state ) )
                <th>Edicion</th>
            @endif
         </tr>
    </thead>
    <tbody>
        @if ( isset($solicituds) )
            @foreach( $solicituds as $solicitud )
                <tr>
                    <td>{{$solicitud->id}}</td>
                    <td style="text-align:center">
                        <span class="label label-info" style="margin-right:1em;background-color:{{$solicitud->etiqueta->color}}">
                                {{$solicitud->etiqueta->nombre}}
                        </span>
                        {{$solicitud->titulo}}
                    </td>
                    <td style="text-align:center">{{$solicitud->asignedTo->rm->full_name}}</td>
                    <td style="text-align:center">
                        {{json_decode($solicitud->detalle->detalle)->num_cuenta}}
                    </td>
                    <td style="text-align:center">{{$solicitud->detalle->fondo->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aprobado}}</td>
                    <td style="text-align:center">{{json_decode($solicitud->detalle->detalle)->supervisor}}</td>
                    @if ( isset( $state ) )
                        <td style="text-align:center">
                            @if ( $state == ACTIVE )
                                <div class="div-icons-solicituds">
                                    <a class="edit-fondo">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a  class="delete-fondo">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                            @elseif ( $state == BLOCKED )
                                TERMINADO
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>