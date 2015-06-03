<table class="table table-striped table-bordered dataTable" id="table_solicitudes">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Solicitado por</th>
            <th>Fecha de Solicitud</th>
            <th>Aprobado por</th>
            <th>Fecha de Aprobación</th>
            @if(Auth::user()->type == TESORERIA)
                <th style="display:none">Solicitado</th>
                <th style="display:none">Retencion</th>
                <th>Deposito</th>
            @else
                <th>Monto</th>
            @endif
            <th>Estado</th>
            <th>Tipo</th>
            <th style="width:auto">Edicion</th>
            @if (Auth::user()->type == GER_COM)
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach( $solicituds as $solicitud )
            <tr>
                @if($solicitud->id_estado == ACEPTADO || $solicitud->id_estado == APROBADO || $solicitud->id_estado == DEPOSITADO || $solicitud->id_estado == REGISTRADO || $solicitud->id_estado == ENTREGADO || $solicitud->id_estado == GENERADO || $solicitud->id_estado == GASTO_HABILITADO || $solicitud->id_estado == DEPOSITO_HABILITADO)
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}" data-accept="$solicitud->acceptHist->user_from">
                @elseif($solicitud->id_estado == RECHAZADO)
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}" data-rejected="{{$solicitud->rejectedHist->user_from}}">
                @else
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}">
                @endif
                @if ( in_array( Auth::user()->type , array( TESORERIA , GER_COM ) ))
                    <input type="hidden" id="sol_token" class="i-tokens" value="{{$solicitud->token}}">
                    @if( ! is_null( $solicitud->id_user_assign ) )
                        @if( $solicitud->assign->type == REP_MED )
                            <input type="hidden" value="{{$solicitud->assign->rm->full_name}}" class="benef">
                        @elseif( $solicitud->assign->type == SUP )
                            <input type="hidden" value="{{$solicitud->assign->sup->full_name}}" class="benef">
                        @elseif ( $solicitud->assign->type == GER_COM )
                            <input type="hidden" value="{{ $solicitud->assign->gerProd->full_name}}" class="benef">
                        @elseif ( !is_null( $solicitud->assign->simApp ) )
                            <input type="hidden" value="{{ $solicitud->assign->person->full_name}}" class="benef">
                        @else
                            <input type="hidden" value="Usuario no autorizado" class="benef">
                        @endif
                    @endif
                @endif
                <td class="text-center id_solicitud">{{$solicitud->id}}</td>
                <td class="text-left sol_titulo">
                    @if (! is_null( $solicitud->id_actividad ) )
                        <span class="label" style="margin-right:1em;background-color:{{$solicitud->activity->color}}">
                            {{$solicitud->activity->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitud->titulo}}</label>
                </td>
                @include('template.List.first_user')
                <td class="text-center">{{$solicitud->created_at}}</td>
                @include('template.List.last_user')
                @include('template.List.lastdate')
                @if( Auth::user()->type == TESORERIA )
                    <td style="display:none;" class="total_deposit">
                        {{ $solicitud->detalle->monto_actual }}
                    </td>
                    <td style="display:none" class="tes-ret">
                        @if ( is_null( $solicitud->detalle->idretencion ) )
                            0
                        @else
                            {{$solicitud->detalle->typeRetention->account->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_retencion }}
                        @endif
                    </td>
                    <td class="text-center deposit">
                        @if ( is_null( $solicitud->detalle->id_deposito ) )
                            {{ $solicitud->detalle->typemoney->simbolo .' '. $solicitud->detalle->monto_actual }}    
                        @else
                            {{ $solicitud->detalle->deposit->account->typeMoney->simbolo . ' ' . $solicitud->detalle->deposit->total }}
                        @endif
                    </td>
                @else
                    <td class="text-center total_deposit">
                        {{ $solicitud->detalle->typeMoney->simbolo . ' ' . $solicitud->detalle->monto_actual }}
                    </td>
                @endif

                @include('template.List.states')
                
                <td class="text-center">
                    @if ( $solicitud->idtiposolicitud == SOL_REP )
                        @if ( is_null( $solicitud->detalle->reason ) )
                            -
                        @else    
                            {{ $solicitud->detalle->reason->nombre }}
                        @endif
                    @elseif ( $solicitud->idtiposolicitud == SOL_INST ) 
                        {{ $solicitud->typesolicitude->nombre }}
                    @endif
                </td>
                @include('template.List.icons')
                @if ( Auth::user()->type == GER_COM )
                    <td class="text-center">
                        @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) )
                        && isset( $solicitud->id_inversion ) && $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario === Auth::user()->type
                        && in_array( Auth::user()->id , $solicitud->managerEdit( $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario )->lists( 'id_gerprod' ) ) )
                            <input type="checkbox" name="mass-aprov">
                        @else
                            <input type="checkbox" name="mass-aprov" disabled>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@if( Auth::user()->type == REP_MED)
    @include('template.Modals.timeLine')
@endif