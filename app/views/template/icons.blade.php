<td style="text-align: center">
    <div class="div-icons-solicituds">
        @if(Auth::user()->type == REP_MED)
            <a href="{{URL::to('ver-solicitud-rm').'/'.$solicitude->token}}">
                <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
            </a>
            @if($solicitude->estado == DEPOSITADO && $solicitude->asiento == 2 && Auth::user()->id == $solicitude->idresponse)
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
            @if($solicitude->estado == PENDIENTE && $solicitude->derive == 0)
                @if(!$solicitude->blocked == 1)
                    <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}" data-token="{{csrf_token()}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
                    </a>
                @endif
            @endif
        @elseif (Auth::user()->type == SUP)
            <a  href="{{URL::to('ver-solicitud-sup').'/'.$solicitude->token}}">
                <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
            </a>
            @if($solicitude->estado == PENDIENTE && $solicitude->user->type == 'S')
                <a  href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                    <span style="padding: 0 5px; font-size: 1.3em"  class="glyphicon glyphicon-pencil"></span>
                </a>
                <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}"  data-token="{{csrf_token()}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
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
        @endif
    </div>
</td>