<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Aprobado por</th>
            <th>Actualizado el</th>
            @if(Auth::user()->type == TESORERIA)
                <th style="display:none">Solicitado</th>
                <th style="display:none">Retencion</th>
                <th>Deposito</th>
            @else
                <th>Monto</th>
            @endif
            <th>Estado</th>
            <th>Fecha de Creación</th>
            <th>Tipo de Solicitud</th>
            <th style="width:auto">Edicion</th>
            @if (Auth::user()->type == GER_COM)
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($solicituds as $solicitude)
            <tr>
                @if ( in_array(Auth::user()->type , array( TESORERIA,GER_COM) ))
                    <input type="hidden" id="sol_token" class="i-tokens" value="{{$solicitude->token}}">  
                    @if(!is_null($solicitude->response))
                        @if($solicitude->response->type == REP_MED)
                            <input type="hidden" value="{{$solicitude->response->Rm->nombres.' '.$solicitude->response->Rm->apellidos}}" class="benef">
                        @elseif($solicitude->response->type == ASIS_GER)
                            <input type="hidden" value="{{$solicitude->response->person->nombres.' '.$solicitude->response->person->apellidos}}" class="benef">
                        @else
                            <input type="hidden" value="Usuario no autorizado" class="benef">
                        @endif
                    @endif
                @endif
                <td class="text-center id_solicitud">{{$solicitude->id}}</td>
                <td class="text-center sol_titulo">
                    @if (!is_null($solicitude->idetiqueta))
                        <span class="label" style="margin-right:1em;background-color:{{$solicitude->etiqueta->color}}">
                            {{$solicitude->etiqueta->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitude->titulo}}</label>
                </td>
                @include('template.List.last_user')
                @include('template.List.lastdate')
                @if( Auth::user()->type == TESORERIA )
                    <td style="display:none;" class="total_deposit">
                        @if ( $solicitude->idtiposolicitud == SOL_REP )
                            {{$solicitude->detalle->typemoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                        @elseif ( $solicitude->idtiposolicitud == SOL_INST )
                            {{$solicitude->detalle->fondo->typemoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                        @endif
                    </td>
                    <td style="display:none" class="tes-ret">
                    @if ( is_null( $solicitude->detalle->idretencion ) )
                        0
                    @else
                        {{$solicitude->detalle->typeRetention->account->typeMoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_retencion}}
                    @endif
                    </td>
                    <td class="text-center deposit">
                        @if ( $solicitude->idtiposolicitud == SOL_REP )
                            @if ( is_null( $solicitude->detalle->idretencion) )
                                {{$solicitude->detalle->typemoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                            @else
                                @if ( $solicitude->detalle->typeRetention->account->idtipomoneda == $solicitude->detalle->idmoneda )
                                    {{$solicitude->detalle->typemoney->simbolo.' '.(json_decode($solicitude->detalle->detalle)->monto_aprobado - json_decode($solicitude->detalle->detalle)->monto_retencion)}}
                                @else
                                    @if ( $solicitude->detalle->idmoneda == SOLES )
                                        {{$solicitude->detalle->typemoney->simbolo.' '.( json_decode( $solicitude->detalle->detalle )->monto_aprobado - ( json_decode( $solicitude->detalle->detalle )->monto_retencion*$tc->compra ) ) }}    
                                    @elseif ( $solicitude->detalle->idmoneda == DOLARES )
                                        {{$solicitude->detalle->typemoney->simbolo.' '.( json_decode( $solicitude->detalle->detalle )->monto_aprobado - ( json_decode( $solicitude->detalle->detalle )->monto_retencion/$tc->venta ) ) }}   
                                    @endif
                                @endif
                            @endif
                        @elseif ( $solicitude->idtiposolicitud == SOL_INST )
                            {{$solicitude->detalle->fondo->typemoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                        @endif  
                    </td>
                @else
                    <td class="text-center total_deposit">
                        @if ( $solicitude->idtiposolicitud == SOL_REP )
                            @if ($solicitude->idestado == PENDIENTE || $solicitude->idestado == DERIVADO )
                                {{ $solicitude->detalle->typeMoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_solicitado }}
                            @elseif ($solicitude->idestado == ACEPTADO )
                                {{ $solicitude->detalle->typeMoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aceptado }}
                            @elseif ( in_array( $solicitude->idestado , array( RECHAZADO , CANCELADO) ) )
                                @if ( isset($detalle->monto_aceptado))
                                    {{ $solicitude->detalle->typeMoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aceptado }}
                                @else
                                    {{ $solicitude->detalle->typeMoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_solicitado }}
                                @endif
                            @else
                                {{ $solicitude->detalle->typeMoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado}}
                            @endif
                        @elseif ( $solicitude->idtiposolicitud == SOL_INST )
                            {{$solicitude->detalle->fondo->typeMoney->simbolo.''.json_decode($solicitude->detalle->detalle)->monto_aprobado}}
                        @endif
                    </td>
                @endif

                @include('template.List.states')
                
                <td class="text-center">{{$solicitude->created_at}}</td> <!--date_format(date_create($solicitude->created_at), 'd/m/Y' ) -->
                <td class="text-center">
                    @if( $solicitude->typeSolicitude->code == SOLIC )
                        {{$solicitude->detalle->typeReason->nombre}}
                    @else
                        {{$solicitude->typesolicitude->nombre}}
                    @endif
                </td>
                @include('template.List.icons')
                @if ( Auth::user()->type == GER_COM )
                    <td class="text-center">
                    @if( $solicitude->idestado != ACEPTADO)
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
<!-- <script>
    $('.element').tooltip();
</script> -->