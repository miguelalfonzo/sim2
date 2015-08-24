<table class="table table-hover table-bordered table-condensed dataTable" id="table_movimientos">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Responsable</th>
            <th>Aprobado por</th>
            <th>Fecha de Creación</th>
            <th>Culminación</th>
            <th>Productos</th>
            <th>Deposito</th>
            <th>Descargo</th>
        </tr>
    </thead>
    <tbody>
        @if ( $solicituds->count() !== 0 )
            @foreach( $solicituds as  $solicitud )
                @if ( ( $solicitud->idtiposolicitud == SOL_REP && $solicitud->products[ 0 ]->thisSubFondo->subcategoria_id == $subCategoriaId ) || 
                    ( $solicitud->idtiposolicitud == SOL_INST && $solicitud->detalle->id_fondo == $subCategoriaId ) )
                    <tr>
                        <td class="text-center">{{ $solicitud->id }}</td>
                        <td class="text-left">
                            @if ( ! is_null( $solicitud->activity ) )
                            <span class="label label-info" style="margin-right:1em;background-color:{{$solicitud->activity->color}}">
                                {{$solicitud->activity->nombre}}
                            </span>
                        @endif
                            <label>{{ $solicitud->titulo }}</label>
                        </td>
                        <td class="text-center">
                            {{ $solicitud->asignedTo->personal->full_name }}
                        </td>
                        <td class="text-center">
                            @if ( $solicitud->idtiposolicitud == SOL_INST )
                                {{$solicitud->createdBy->personal->full_name}}
                            @elseif ( $solicitud->idtiposolicitud == SOL_REP )
                                {{ $solicitud->approvedHistory->user->personal->full_name }}
                            @endif
                        </td>
                        <td class="text-center">{{$solicitud->created_at}}</td>
                        <td class="text-center">{{$solicitud->updated_at}}</td>
                        <td class="text-center">
                            @if ( $solicitud->products->count() == 0 )
                                -
                            @else
                                @foreach( $solicitud->products as $product )
                                    <span class="label label-info">{{ $product->marca->descripcion }}</span>
                                @endforeach
                            @endif
                        </td>   
                        <td class="text-center">
                            {{ $solicitud->detalle->deposit->account->typeMoney->simbolo . ' ' . $solicitud->detalle->deposit->total }}
                        </td>
                        <td class="text-center">
                            {{ $solicitud->detalle->typeMoney->simbolo . ' ' . $solicitud->expenses->sum( 'monto' ) }}
                        </td>          
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>