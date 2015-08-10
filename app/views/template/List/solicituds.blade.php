<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="table_solicitudes" class="table table-striped table-hover table-bordered" >
    <thead>
        <tr>
            <th></th>
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
            <th class="col-xs-2 col-sm-2">Edicion</th>
            @if (Auth::user()->type == GER_COM)
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach( $solicituds as $solicitud )
            <tr>
                <td class="text-center open-details" data-id="{{$solicitud->id}}">
                    <a class="btn btn-default">
                        <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                </td>
                @if($solicitud->state->id_estado == R_NO_AUTORIZADO )
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}" data-rejected="{{$solicitud->rejectedHist->user_from}}">
                @elseif( ! is_null( $solicitud->state ) && $solicitud->id_estado != TODOS )
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}" data-accept="$solicitud->acceptHist->user_from">
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
                <td class="text-center id_solicitud detail-control">{{$solicitud->id}}</td>
                <td class="text-left sol_titulo">
                    @if (! is_null( $solicitud->id_actividad ) )
                        <span class="label" style="margin-right:1em;background-color:{{$solicitud->activity->color}}">
                            {{$solicitud->activityTrash->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitud->titulo}}</label>
                </td>
                <td class="text-center">
                    {{ $solicitud->createdBy->personal->getFullName() }}
                </td>
                <td class="text-center">{{$solicitud->created_at}}</td>
                <td class="text-center">
                    @if ( $solicitud->id_estado != PENDIENTE )
                        @if( $solicitud->lastHistory->count() != 0 )
                            @if (is_object($solicitud->lastHistory->user ) )
                                {{ $solicitud->lastHistory->user->personal->getFullName() }}
                            @else
                                -
                            @endif
                        @endif
                    @else
                        -
                    @endif
                </td>
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
                        @if ( is_null( $solicitud->typeSolicitude ) )
                            -
                        @else    
                            {{ $solicitud->typeSolicitude->nombre }}
                        @endif
                    @elseif ( $solicitud->idtiposolicitud == SOL_INST ) 
                        {{ $solicitud->typesolicitude->nombre }}
                    @endif
                </td>
                @include('template.List.icons')
                @if ( Auth::user()->type == GER_COM )
                    <td class="text-center">
                        @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) )
                        && $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario === Auth::user()->type
                        && in_array( Auth::user()->id , $solicitud->managerEdit( $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario )->lists( 'id_gerprod' ) ) )
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