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
            <th>Edicion</th>
            @if (Auth::user()->type == GER_COM)
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($solicituds as $solicitude)
            <tr>
                <td style="text-align:center" class="id_solicitud">{{$solicitude->idsolicitud}}</td>
                @if ( in_array(Auth::user()->type , array( TESORERIA,GER_COM) ))
                    <input type="hidden" id="sol_token" value="{{$solicitude->token}}">  
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
                <td style="text-align:left" class="sol_titulo">
                    @if (!is_null($solicitude->idetiqueta))
                        <span class="label label-info" style="margin-right:1em;background-color:{{$solicitude->etiqueta->color}}">
                            {{$solicitude->etiqueta->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitude->titulo}}</label>
                </td>
                <td style="text-align:center"> 
                    @if(count($solicitude->history) != 0)
                        @if (is_object($solicitude->history[0]->user))    
                            @if ($solicitude->history[0]->user->type == REP_MED)
                                {{ucwords(strtolower($solicitude->history[0]->user->Rm->nombres.' '.$solicitude->history[0]->user->Rm->apellidos))}}
                            @elseif ($solicitude->history[0]->user->type == SUP)
                                {{ucwords(strtolower($solicitude->history[0]->user->Sup->nombres.' '.$solicitude->history[0]->user->Sup->apellidos))}}
                            @elseif ($solicitude->history[0]->user->type == GER_PROD)
                                {{ucwords(strtolower($solicitude->history[0]->user->GerProd->descripcion))}}
                            @elseif ( in_array($solicitude->history[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                                {{ucwords(strtolower($solicitude->history[0]->user->person->nombres.' '.$solicitude->history[0]->user->person->apellidos))}}
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
                    <td  style="text-align:center">
                        @if (!$solicitude->retencion == null)
                            {{$solicitude->typemoney->simbolo.' '.($solicitude->monto - $solicitude->retencion)}}
                        @else
                            {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                        @endif
                    </td>
                @else
                    <td style="text-align:center" class="total_deposit">{{$solicitude->typemoney->simbolo.$solicitude->monto}}</td>
                @endif
                @include('template.states')
                <td style="text-align:center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td style="text-align:center">{{$solicitude->typesolicitude->nombre}}</td>
                @include('template.icons')
                @if (Auth::user()->type == GER_COM)
                    <td style="text-align:center">
                    @if( $solicitude->estado != ACEPTADO)
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
<script>
    $('.element').tooltip();
</script>