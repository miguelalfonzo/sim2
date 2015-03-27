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
                <th>Monto Solicitado</th>
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
                <td class="text-center" class="id_solicitud">{{$solicitude->idsolicitud}}</td>
                <td class="text-center" class="sol_titulo">
                    @if (!is_null($solicitude->idetiqueta))
                        <span class="label" style="margin-right:1em;background-color:{{$solicitude->etiqueta->color}}">
                            {{$solicitude->etiqueta->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitude->titulo}}</label>
                </td>
                <td class="text-center">
                    @if(count($solicitude->histories) != 0)
                        @if (is_object($solicitude->histories[0]->user))    
                            @if ($solicitude->histories[0]->user->type == REP_MED)
                                {{ucwords(strtolower($solicitude->histories[0]->user->Rm->nombres.' '.$solicitude->histories[0]->user->Rm->apellidos))}}
                            @elseif ($solicitude->histories[0]->user->type == SUP)
                                {{ucwords(strtolower($solicitude->histories[0]->user->Sup->nombres.' '.$solicitude->histories[0]->user->Sup->apellidos))}}
                            @elseif ($solicitude->histories[0]->user->type == GER_PROD)
                                {{ucwords(strtolower($solicitude->histories[0]->user->GerProd->descripcion))}}
                            @elseif ( in_array($solicitude->histories[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                                {{ucwords(strtolower($solicitude->histories[0]->user->person->nombres.' '.$solicitude->histories[0]->user->person->apellidos))}}
                            @else
                                Usuario no autorizado
                            @endif
                        @else
                            -
                        @endif
                    @endif
                </td>
                @if(Auth::user()->type == TESORERIA)
                    <td style="display:none;" class="total_deposit">
                        {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                    </td>
                    <td style="display:none" class="tes-ret">
                    @if ($solicitude->retencion == null)
                        0
                    @else
                        {{$solicitude->retencion}}
                    @endif
                    </td>
                    <td class="text-center">
                        @if (!$solicitude->retencion == null)
                            {{$solicitude->typemoney->simbolo.' '.($solicitude->monto - $solicitude->retencion)}}
                        @else
                            {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                        @endif
                    </td>
                @else
                    <td class="text-center total_deposit">{{$solicitude->typemoney->simbolo.$solicitude->monto}}</td>
                @endif
                @include('template.states')
                <td class="text-center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td class="text-center">{{$solicitude->typesolicitude->nombre}}</td>
                @include('template.icons')
                @if ( Auth::user()->type == GER_COM )
                    <td class="text-center">
                    @if( $solicitude->estado != ACEPTADO)
                        <input type="checkbox" name="mass-aprov" disabled/>
                    @else
                        <input type="checkbox" name="mass-aprov"/>
                    @endif
                    </td>
                @endif
            </tr>
        @endforeach
        @if (isset($fondos))
            @foreach($fondos as  $fondo)
                <tr>
                    <td>{{$fondo->idfondo}}</td>
                    <td class="text-center">
                        <label>{{$fondo->institucion}}</label>
                    </td>
                    <td class="text-center">
                        @if(count($fondo->histories) != 0)
                            @if (is_object($fondo->histories[0]->user))    
                                @if ($fondo->histories[0]->user->type == REP_MED)
                                    {{ucwords(strtolower($fondo->histories[0]->user->Rm->nombres.' '.$fondo->histories[0]->user->Rm->apellidos))}}
                                @elseif ($fondo->histories[0]->user->type == SUP)
                                    {{ucwords(strtolower($fondo->histories[0]->user->Sup->nombres.' '.$fondo->histories[0]->user->Sup->apellidos))}}
                                @elseif ($fondo->histories[0]->user->type == GER_PROD)
                                    {{ucwords(strtolower($fondo->histories[0]->user->GerProd->descripcion))}}
                                @elseif ( in_array($fondo->histories[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                                    {{ucwords(strtolower($fondo->histories[0]->user->person->nombres.' '.$fondo->histories[0]->user->person->apellidos))}}
                                @else
                                    Usuario no autorizado
                                @endif
                            @else
                                -
                            @endif
                        @endif
                    </td>
                    <td class="text-center">S/.{{$fondo->total}}</td>
                    <td class="text-center">
                        <span class="label" style="background-color:{{$fondo->state->rangeState->color}}">
                            {{$fondo->state->rangeState->nombre}}
                        </span>
                    </td>
                    <td class="text-center">{{$fondo->monthYear($fondo->periodo)}}</td>
                    <td class="text-center">INSTITUCIONAL</td>
                    <td>
                        <div class="div-icons-solicituds">
                            <a href="{{URL::to('ver-gasto-fondo')}}/{{$fondo->token}}">
                                <span class="glyphicon glyphicon-eye-open" style="padding: 0 5px; font-size: 1.3em"></span>
                            </a>
                            @if($fondo->registrado == 1)   
                                <a href="{{URL::to('report-fondo')}}/{{$fondo->token}}"><span class="glyphicon glyphicon-print"></span></a>
                            @elseif($fondo->asiento == ASIENTO_FONDO)
                                <a href="{{URL::to('registrar-gasto-fondo')}}/{{$fondo->token}}" class="" data-idfondo="{{$fondo->idfondo}}">
                                    <span class="glyphicon glyphicon-usd"></span>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<script>
    $('.element').tooltip();
</script>