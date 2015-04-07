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
                    <td class="text-center total_deposit"><!-- {$solicitude->detalle->typeMoney } --> S/. 500</td>
                @endif
                
                
                @include('template.states')
                
                <td class="text-center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td class="text-center"><!-- {$solicitude->typesolicitude->nombre} --> Solicitud</td>
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
    </tbody>
</table>
<!-- <script>
    $('.element').tooltip();
</script> -->