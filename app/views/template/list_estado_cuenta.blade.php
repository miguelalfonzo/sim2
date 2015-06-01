<div class="table-responsive">
    <table class="table table-hover table-bordered table-condensed dataTable" id="table_estado_cuenta" style="width: 100%">
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
                        <td class="text-center">{{$cuenta->id}}</td>
                        <td class="text-left">
                            @if (!is_null($cuenta->activity))
                            <span class="label label-info" style="margin-right:1em;background-color:{{$cuenta->activity->color}}">
                                {{$cuenta->activity->nombre}}
                            </span>
                        @endif
                            <label>{{$cuenta->titulo}}</label>
                        </td>
                        <td class="text-center">
                            @if ( $cuenta->createdBy->type == REP_MED)
                                {{$cuenta->createdBy->rm->full_name}}
                            @elseif ( $cuenta->createdBy->type == SUP )
                                {{$cuenta->createdBy->sup->full_name}}
                            @elseif ( $cuenta->createdBy->type == GER_PROD )
                                {{ $cuenta->createdBy->gerProd->full_name }}
                            @elseif ( ! is_null( $cuenta->createdBy->simApp ) )
                                {{ $cuenta->createdBy->person->full_name }}
                            @else
                                No registrado
                            @endif
                        </td>
                        <td class="text-center">{{$cuenta->created_at}}</td>
                        <td class="text-center">
                            @if ( $cuenta->idtiposolicitud == SOL_INST )
                                {{$cuenta->createdBy->person->full_name}}
                            @elseif ( $cuenta->idtiposolicitud == SOL_REP )
                                @if ( is_null( $cuenta->approvedHistory ) )
                                    -
                                @elseif ($cuenta->approvedHistory->user->type == SUP )
                                    {{ $cuenta->approvedHistory->user->sup->full_name }}
                                @elseif ( $cuenta->approvedHistory->user->type == GER_PROD )
                                    {{$cuenta->approvedHistory->user->gerProd->full_name}}
                                @elseif ( $cuenta->approvedHistory->user->type == REP_MED )
                                    {{$cuenta->approvedHistory->user->rm->full_name}}
                                @elseif ( ! is_null( $cuenta->approvedHistory->user->simApp ) )
                                    {{ $cuenta->approvedHistory->user->person->full_name }}
                                @else
                                    No Registrado
                                @endif
                            @endif
                        </td>
                        <td class="text-center">{{$cuenta->updated_at}}</td>
                        <td class="text-center">
                            D
                        </td>
                        <td>
                            @if ( is_null( $cuenta->asignedTo ) )
                                -
                            @elseif ( $cuenta->asignedTo->type == REP_MED )
                                {{ $cuenta->asignedTo->rm->full_name }}
                            @elseif ( $cuenta->asignedTo->type == SUP )
                                {{ $cuenta->asignedTo->sup->full_name }}
                            @elseif ( $cuenta->asignedTo->type == GER_PROD )
                                {{ $cuenta->asignedTo->gerProd->full_name }}
                            @elseif ( ! is_null( $cuenta->asignedTo->simApp ) )
                                {{ $cuenta->asignedTo->person->full_name }}
                            @else
                                No Autorizado
                            @endif
                        </td>
                        <td>
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
</div>