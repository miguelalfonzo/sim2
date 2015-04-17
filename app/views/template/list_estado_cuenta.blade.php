<div class="table-responsive">
    <table class="table table-hover table-bordered table-condensed dataTable" id="table_estado_cuenta" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Solicitud</th>
                <th>Solicitado por</th>
                <th>Aprobado por</th>
                <th>Monto Depositado</th>
                <th>Fecha Creación</th>
                <th>Fecha de Culminación</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($solicituds))
                @foreach($solicituds as  $cuenta)
                    <tr>
                        <td class="text-center">{{$cuenta->idsolicitud}}</td>
                        <td class="text-left">
                            @if (!is_null($cuenta->idetiqueta))
                            <span class="label label-info" style="margin-right:1em;background-color:{{$cuenta->etiqueta->color}}">
                                {{$cuenta->etiqueta->nombre}}
                            </span>
                        @endif
                            <label>{{$cuenta->titulo}}</label>
                        </td>
                        @if ($cuenta->user->type == REP_MED)
                            <td class="text-center">{{$cuenta->rm->nombres.' '.$cuenta->rm->apellidos}}</td>
                        @else
                            <td class="text-center">{{$cuenta->sup->nombres.' '.$cuenta->sup->apellidos}}</td>
                        @endif
                        @if ($cuenta->aproved->type == SUP)
                            <td class="text-center">{{$cuenta->aprovedSup->nombres.' '.$cuenta->aprovedSup->apellidos}}</td>
                        @else ($cuenta->aproved->type == GER_PROD)
                            <td class="text-center">{{$cuenta->aprovedGerProd->descripcion}}</td>
                        @endif
                        <td class="text-center">{{$cuenta->deposit->total}}</td>
                        <td class="text-center">{{$cuenta->created_at}}</td>
                        <td class="text-center">{{$cuenta->updated_at}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>