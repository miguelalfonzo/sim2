<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Visto Ultimo</th>
            @if(Auth::user()->type == TESORERIA)
                <th style="display:none">Solicitado</th>
                <th style="display:none">Retencion</th>
                <th>Deposito</th>
            @else
                <th>Monto</th>
            @endif
            <th>Estado</th>
            <th>Fecha</th>
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
                @include('template.last_user')
                
                @if( Auth::user()->type == TESORERIA )
                    <td style="display:none;" class="total_deposit">
                        {{$solicitude->detalle->typemoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                    </td>
                    <td style="display:none" class="tes-ret">
                    @if ( is_null( $solicitude->detalle->idretencion ) )
                        0
                    @else
                        {{json_decode($solicitude->detalle->detalle)->monto_retencion}}
                    @endif
                    </td>
                    <td class="text-center">
                        @if ( is_null( $solicitude->detalle->idretencion) )
                            {{$solicitude->detalle->typemoney->simbolo.' '.json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                        @else
                            {{$solicitude->detalle->typemoney->simbolo.' '.(json_decode($solicitude->detalle->detalle)->monto_aprobado - json_decode($solicitude->detalle->detalle)->monto_retencion)}}
                        @endif
                    </td>
                @else
                    <td class="text-center total_deposit">
                        @if ($solicitude->idestado == PENDIENTE || $solicitude->idestado == DERIVADO )
                            {{ json_decode($solicitude->detalle->detalle)->monto_solicitado }}
                        @elseif ($solicitude->idestado == ACEPTADO )
                            {{ json_decode($solicitude->detalle->detalle)->monto_aceptado }}
                        @elseif ( in_array( $solicitude->idestado , array( RECHAZADO , CANCELADO) ) )
                            @if ( isset($detalle->monto_aceptado))
                                {{ json_decode($solicitude->detalle->detalle)->monto_aceptado }}
                            @else
                                {{ json_decode($solicitude->detalle->detalle)->monto_solicitado }}
                            @endif
                        @else
                            {{ json_decode($solicitude->detalle->detalle)->monto_aprobado }}
                        @endif
                    </td>
                @endif

                @include('template.states')
                
                <td class="text-center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td class="text-center">
                    @if( $solicitude->typeSolicitude->code == SOLIC )
                        {{$solicitude->detalle->typeReason->nombre}}
                    @else
                        {{$solicitude->typesolicitude->nombre}}
                    @endif
                </td>
                @include('template.icons')
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