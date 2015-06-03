<table class="table table-hover table-bordered table-condensed dataTable" id="table_estado-fondos">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Fondo</th>
            <th>Destinatario</th>
            <th>Fecha</th>
            <th>Aprobado por</th>
            <th>Saldo Inicial</th>
            <th>Saldo Final</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $fondoHistories as $fondoHistory )
            <tr>
                <td class="text-center">{{$fondoHistory->id}}</td>
                <td class="text-center">{{$fondoHistory->solicitud->titulo}}</td>
                <td class="text-center">{{$fondoHistory->fondo->nombre}}</td>
                <td class="text-center">
                    @if ( $fondoHistory->solicitud->asignedTo->type == REP_MED )
                        {{ $fondoHistory->solicitud->asignedTo->rm->full_name}}
                    @elseif ( $fondoHistory->solicitud->asignedTo->type == SUP )
                        {{ $fondoHistory->solicitud->asignedTo->sup->full_name}}
                    @elseif ( $fondoHistory->solicitud->asignedTo->type == GER_PROD )
                        {{ $fondoHistory->solicitud->asignedTo->gerProd->full_name}}
                    @elseif ( ! is_null( $fondoHistory->solicitud->asignedTo->simApp ) )
                        {{ $fondoHistory->solicitud->asignedTo->person->full_name}}
                    @else
                        {{ $fondoHistory->solicitud->asignedTo->type }}
                    @endif
                </td>
                <td class="text-center">{{$fondoHistory->updated_at}}
                <td class="text-center">
                    @if ( $fondoHistory->solicitud->idtiposolicitud == SOL_REP )
                        @if ( $fondoHistory->solicitud->approvedHistory->user->type == REP_MED ) 
                            {{ $fondoHistory->solicitud->approvedHistory->user->rm->full_name }}
                        @elseif ( $fondoHistory->solicitud->approvedHistory->user->type == SUP ) 
                            {{ $fondoHistory->solicitud->approvedHistory->user->sup->full_name }}
                        @elseif ( $fondoHistory->solicitud->approvedHistory->user->type == GER_PROD ) 
                            {{ $fondoHistory->solicitud->approvedHistory->user->gerProd->full_name }}
                        @elseif ( !is_null( $fondoHistory->solicitud->approvedHistory->user->simApp ) ) 
                            {{ $fondoHistory->solicitud->approvedHistory->user->person->full_name }}
                        @else
                            {{ $fondoHistory->solicitud->approvedHistory->user->type }}
                        @endif
                    @elseif ( $fondoHistory->solicitud->idtiposolicitud == SOL_INST )
                        {{$fondoHistory->solicitud->toDepositHistory->user->person->full_name}}
                    @endif
                </td>
                <td class="text-center">{{$fondoHistory->saldo_inicial}}</td>
                <td class="text-center">{{$fondoHistory->saldo_final}}</td>
            </tr>
        @endforeach
    </tbody>
</table>