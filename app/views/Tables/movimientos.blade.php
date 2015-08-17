<table class="table table-hover table-bordered table-condensed dataTable" id="table_movimientos">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Solicitado por</th>
            <th>Fecha de Solicitud</th>
            <th>Aprobado por</th>
            <th>Fecha de Aprobación</th>
            <th>Deposito</th>
            <th>Destinatario</th>
            <th>Productos</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @if ( $solicituds->count() !== 0 )
            @foreach($solicituds as  $cuenta)
                <tr>
                    <td class="text-center">{{ $cuenta->id }}</td>
                    <td class="text-left">
                        @if ( ! is_null( $cuenta->activity ) )
                        <span class="label label-info" style="margin-right:1em;background-color:{{$cuenta->activity->color}}">
                            {{$cuenta->activity->nombre}}
                        </span>
                    @endif
                        <label>{{ $cuenta->titulo }}</label>
                    </td>
                    <td class="text-center">
                        {{ $cuenta->createdBy->personal->full_name }}
                    </td>
                    <td class="text-center">{{$cuenta->created_at}}</td>
                    <td class="text-center">
                        @if ( $cuenta->idtiposolicitud == SOL_INST )
                            {{$cuenta->createdBy->personal->full_name}}
                        @elseif ( $cuenta->idtiposolicitud == SOL_REP )
                            {{ $cuenta->approvedHistory->user->personal->full_name }}
                        @endif
                    </td>
                    <td class="text-center">{{$cuenta->updated_at}}</td>
                    <td class="text-center">
                        {{ $cuenta->detalle->deposit->account->typeMoney->simbolo . ' ' . $cuenta->detalle->deposit->total }}
                    </td>
                    <td class="text-center">
                        {{ $cuenta->asignedTo->personal->full_name }}
                    </td>
                    <td class="text-center">
                        @if ( $cuenta->products->count() == 0 )
                            -
                        @else
                            @foreach( $cuenta->products as $product )
                                <span class="label label-info">{{ $product->marca->descripcion }}</span>
                            @endforeach
                        @endif
                    </td>   
                    <td>{{$cuenta->saldo}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>