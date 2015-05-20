<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
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
                @if($solicitud->idestado == ACEPTADO || $solicitud->idestado == APROBADO || $solicitud->idestado == DEPOSITADO || $solicitud->idestado == REGISTRADO || $solicitud->idestado == ENTREGADO || $solicitud->idestado == GENERADO || $solicitud->idestado == GASTO_HABILITADO || $solicitud->idestado == DEPOSITO_HABILITADO)
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->idestado}}" data-accept="$solicitud->acceptHist->user_from">
                @elseif($solicitud->idestado == RECHAZADO)
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->idestado}}" data-rejected="{{$solicitud->rejectedHist->user_from}}">
                @else
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->idestado}}">
                @endif
                @if ( in_array( Auth::user()->type , array( TESORERIA , GER_COM ) ))
                    <input type="hidden" id="sol_token" class="i-tokens" value="{{$solicitud->token}}">
                    @if( !is_null($solicitud->response ) )
                        @if( $solicitud->response->type == REP_MED )
                            <input type="hidden" value="{{$solicitud->response->rm->full_name}}" class="benef">
                        @elseif( $solicitud->response->type == ASIS_GER )
                            <input type="hidden" value="{{$solicitud->response->person->full_name}}" class="benef">
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
                        {{$solicitud->detalle->fondo->typemoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aprobado }}
                    </td>
                    <td style="display:none" class="tes-ret">
                        @if ( is_null( $solicitud->detalle->idretencion ) )
                            0
                        @else
                            {{$solicitud->detalle->typeRetention->account->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_retencion}}
                        @endif
                    </td>
                    <td class="text-center deposit">
                        @if ( $solicitud->idtiposolicitud == SOL_REP )
                            @if ( is_null( $solicitud->detalle->iddeposito ) )
                                @if ( is_null( $solicitud->detalle->idretencion) )
                                    {{$solicitud->detalle->typemoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aprobado }}
                                @else
                                    @if ( $solicitud->detalle->typeRetention->account->idtipomoneda == $solicitud->detalle->idmoneda )
                                        {{$solicitud->detalle->typemoney->simbolo.' '.(json_decode($solicitud->detalle->detalle)->monto_aprobado - json_decode($solicitud->detalle->detalle)->monto_retencion)}}
                                    @else
                                        @if ( $solicitud->detalle->idmoneda == SOLES )
                                            {{$solicitud->detalle->typemoney->simbolo.' '.( json_decode( $solicitud->detalle->detalle )->monto_aprobado - ( json_decode( $solicitud->detalle->detalle )->monto_retencion*$tc->compra ) ) }}    
                                        @elseif ( $solicitud->detalle->idmoneda == DOLARES )
                                            {{$solicitud->detalle->typemoney->simbolo.' '.( json_decode( $solicitud->detalle->detalle )->monto_aprobado - ( json_decode( $solicitud->detalle->detalle )->monto_retencion/$tc->venta ) ) }}   
                                        @endif
                                    @endif
                                @endif
                            @else
                                {{$solicitud->detalle->deposit->account->typeMoney->simbolo.' '.$solicitud->detalle->deposit->total}}
                            @endif
                        @elseif ( $solicitud->idtiposolicitud == SOL_INST )
                            {{$solicitud->detalle->fondo->typemoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aprobado }}
                        @endif  
                    </td>
                @else
                    <td class="text-center total_deposit">
                        @if ( $solicitud->idtiposolicitud == SOL_REP )
                            @if ($solicitud->id_estado == PENDIENTE || $solicitud->id_estado == DERIVADO )
                                {{ $solicitud->detalle->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_solicitado }}
                            @elseif ($solicitud->id_estado == ACEPTADO )
                                {{ $solicitud->detalle->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aceptado }}
                            @elseif ( in_array( $solicitud->id_estado , array( RECHAZADO , CANCELADO) ) )
                                @if ( isset($detalle->monto_aceptado))
                                    {{ $solicitud->detalle->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aceptado }}
                                @else
                                    {{ $solicitud->detalle->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_solicitado }}
                                @endif
                            @else
                                {{ $solicitud->detalle->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aprobado}}
                            @endif
                        @elseif ( $solicitud->idtiposolicitud == SOL_INST )
                            {{$solicitud->detalle->fondo->typeMoney->simbolo.''.json_decode($solicitud->detalle->detalle)->monto_aprobado}}
                        @endif
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
                    @if( $solicitud->idestado != ACEPTADO)
                        <input type="checkbox" name="mass-aprov" disabled/>
                    @else
                        <input type="checkbox" name="mass-aprov"/>
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