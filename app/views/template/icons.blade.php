<td style="text-align: center">
    <div class="div-icons-solicituds">
        @if(Auth::user()->type == REP_MED)
            @if (isset($solicitude->idsolicitud))
                <a href="{{URL::to('ver-solicitud-rm').'/'.$solicitude->token}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>
                @if($solicitude->estado == GASTO_HABILITADO)
                    <a id="token-reg-expense" data-url="{{$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
                @if($solicitude->estado == REGISTRADO && Auth::user()->id == $solicitude->idresponse)
                    <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
                    </a>
                    <a  href="{{URL::to('ver-gasto'.'/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
                @if($solicitude->estado == PENDIENTE && $solicitude->derived == 0 && $solicitude->user->type = REP_MED)
                    @if(!$solicitude->blocked == 1)
                        <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                            <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}" data-token="{{csrf_token()}}">
                            <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
                        </a>
                    @endif
                @endif
            @elseif ( isset($solicitude->idfondo) )
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>    
                @if($solicitude->registrado == 1)   
                    <a href="{{URL::to('report-fondo')}}/{{$solicitude->token}}">
                        <span class="glyphicon glyphicon-print" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                @elseif($solicitude->asiento == ASIENTO_FONDO)
                    <a href="{{URL::to('registrar-gasto-fondo')}}/{{$solicitude->token}}" data-idfondo="{{$solicitude->idfondo}}">
                        <span class="glyphicon glyphicon-usd" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                @endif
            @endif
        @elseif (Auth::user()->type == SUP)
            @if (isset($solicitude->idsolicitud))
                <a  href="{{URL::to('ver-solicitud-sup').'/'.$solicitude->token}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>
                @if($solicitude->estado == PENDIENTE && $solicitude->derived == 0 && $solicitude->user->type == SUP)
                    <a  href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em"  class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}"  data-token="{{csrf_token()}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
                    </a>
                @endif
            @elseif ( isset($solicitude->idfondo) )
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>    
            @endif
        @elseif (Auth::user()->type == GER_PROD)
            <a href="{{URL::to('ver-solicitud-gerprod').'/'.$solicitude->token}}">
                <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
            </a>
        @elseif (Auth::user()->type == GER_COM)
            <a href="{{URL::to('ver-solicitud-gercom').'/'.$solicitude->token}}">
                <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
            </a>
        @elseif (Auth::user()->type == CONT)
            @if(isset($solicitude->idsolicitud))
                <a href="{{URL::to('ver-solicitud-cont/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>
                @if($solicitude->estado == DEPOSITADO)
                    <a id="token-solicitude" data-url="{{$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-book"></span>
                    </a>
                @endif
                @if($solicitude->estado == REGISTRADO)
                    <a id="token-reg-expense" data-url="{{$solicitude->token}}" data-cont="1">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                    <a id="token-expense" data-url="{{$solicitude->token}}" href="#">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-book"></span>
                    </a>
                @endif
            @elseif (isset($solicitude->idfondo))
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span class="glyphicon glyphicon-eye-open" style="padding: 0 5px; font-size: 1.3em"></span>
                </a>
                @if ($solicitude->estado == DEPOSITADO)
                    <a href="{{URL::to('generar-asiento-fondo/'.$solicitude->token)}}">
                        <span class="glyphicon glyphicon-book" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                @endif
            @endif
            
        @elseif ( Auth::user()->type == TESORERIA)
            @if( $solicitude->estado == DEPOSITO_HABILITADO )
                <a class="modal_deposit">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                </a>
            @endif
            @if (isset($solicitude->idsolicitud))
                <a href="{{URL::to('ver-solicitud-tes').'/'.$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                    </a>
            @elseif (isset($solicitude->idfondo))
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>
            @endif
        @elseif ( Auth::user()->type == ASIS_GER)
            <a href="{{URL::to('ver-solicitud-ager').'/'.$solicitude->token}}">
                <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
            </a>
            @if($solicitude->estado == GASTO_HABILITADO)
                <a id="token-reg-expense" data-url="{{$solicitude->token}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                </a>
            @endif
            @if($solicitude->estado == REGISTRADO)
                <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
                </a>
                <a  href="{{URL::to('ver-gasto'.'/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                </a>
            @endiF
        @endif
    </div>
</td>