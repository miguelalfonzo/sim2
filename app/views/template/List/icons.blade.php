<td style="text-align: center">
    <div class="div-icons-solicituds">
        @if ( Auth::user()->type == ASIS_GER)
            @if($solicitude->idestado == GASTO_HABILITADO)
                <a id="token-reg-expense" data-url="{{$solicitude->token}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                </a>
            @endif
            @if($solicitude->idestado == REGISTRADO)
                <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
                </a>
                <a  href="{{URL::to('ver-gasto'.'/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                </a>
            @endif
        @endif
        @if ( $solicitude->idtiposolicitud == SOL_REP )
            <a href="{{URL::to('ver-solicitud/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
            </a>       
            @if( Auth::user()->type == REP_MED)
                @if($solicitude->estado == GASTO_HABILITADO)
                    <a id="token-reg-expense" data-url="{{$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
                @if($solicitude->idestado == REGISTRADO && Auth::user()->id == $solicitude->idresponse)
                    <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
                    </a>
                    <a  href="{{URL::to('ver-gasto'.'/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
                @if( $solicitude->idestado == PENDIENTE && $solicitude->created_by == Auth::user()->id )
                    @if( $solicitude->status == 1 )
                        <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                            <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a class="cancel-solicitude" data-idsolicitude="{{$solicitude->id}}" data-token="{{csrf_token()}}">
                            <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
                        </a>
                    @endif
                @endif
            @elseif ( Auth::user()->type == SUP )
                @if( $solicitude->idestado == PENDIENTE && $solicitude->created_by == Auth::user()->id )
                    <a  href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em"  class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->id}}"  data-token="{{csrf_token()}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == CONT )
                @if($solicitude->idestado == DEPOSITADO)
                    <!-- <a id="token-solicitude" data-url="{{$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-book"></span>
                    </a> -->
                @elseif($solicitude->idestado == REGISTRADO)
                    <a id="token-reg-expense" data-url="{{$solicitude->token}}" data-cont="1">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                    <a id="token-expense" data-url="{{$solicitude->token}}" href="#">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-book"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == TESORERIA )
                @if( $solicitude->idestado == DEPOSITO_HABILITADO )
                    <a class="modal_deposit">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
            @endif
        @elseif ( $solicitude->idtiposolicitud == SOL_INST )
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>    
                @if($solicitude->idestado == REGISTRADO )   
                    <a href="{{URL::to('report-fondo')}}/{{$solicitude->token}}">
                        <span class="glyphicon glyphicon-print" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                @elseif($solicitude->idestado == GASTO_HABILITADO )
                    <a href="{{URL::to('registrar-gasto-fondo')}}/{{$solicitude->token}}" data-idfondo="{{$solicitude->idfondo}}">
                        <span class="glyphicon glyphicon-usd" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                @endif
            @if (Auth::user()->type == SUP)
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>    
            @elseif (Auth::user()->type == CONT)
                    <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                        <span class="glyphicon glyphicon-eye-open" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                    @if ($solicitude->estado == DEPOSITADO)
                    <a href="{{URL::to('generar-asiento-fondo/'.$solicitude->token)}}">
                        <span class="glyphicon glyphicon-book" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                    @endif
              
            @elseif ( Auth::user()->type == TESORERIA)
                <a href="{{URL::to('show-fondo/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                </a>
          
            @endif
        @endif
    </div>
</td>