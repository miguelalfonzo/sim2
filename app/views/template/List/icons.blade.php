<td style="text-align: center">
    <div class="div-icons-solicituds">
        <a href="{{URL::to('ver-solicitud/'.$solicitude->token)}}">
            <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
        </a>    
        @if ( $solicitude->idtiposolicitud == SOL_REP )
            @if( Auth::user()->type == REP_MED)
                <a data-toggle="modal" data-target=".timeLineModal">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-flag"></span>
                </a>
                @if($solicitude->idestado == REGISTRADO && Auth::user()->id == $solicitude->iduserasigned )
                    <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
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
                <a href="{{URL::to('a'.'/'.$solicitude->token)}}">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
                </a>
            @elseif ( Auth::user()->type == SUP )
                @if( $solicitude->idestado == PENDIENTE && $solicitude->created_by == Auth::user()->id )
                    <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
                        <span style="padding: 0 5px; font-size: 1.3em"  class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->id}}"  data-token="{{csrf_token()}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == CONT )
                @if( $solicitude->idestado == REGISTRADO )
                    <a href="{{URL::to('generar-asiento-gasto/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-book"></span>
                    </a>
                @endif   
            @elseif ( Auth::user()->type == TESORERIA )
                @if( $solicitude->idestado == DEPOSITO_HABILITADO )
                    <a class="modal_deposit">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == ASIS_GER )
                @if($solicitude->idestado == REGISTRADO)
                    <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-print"></span>
                    </a>
                @endif
            @endif
        
        @elseif ( $solicitude->idtiposolicitud == SOL_INST )
            @if ( Auth::user()->type == REP_MED )
                <a data-toggle="modal" data-target=".timeLineModal">
                    <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-flag"></span>
                </a>
                @if($solicitude->idestado == REGISTRADO && Auth::user()->id == $solicitude->iduserasigned )   
                    <a href="{{URL::to('report-fondo')}}/{{$solicitude->token}}">
                        <span class="glyphicon glyphicon-print" style="padding: 0 5px; font-size: 1.3em"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == CONT )
                 @if( $solicitude->idestado == REGISTRADO )
                    <a href="{{URL::to('generar-asiento-gasto/'.$solicitude->token)}}">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-book"></span>
                    </a>
                @endif  
            @elseif ( Auth::user()->type == TESORERIA )
                @if ( $solicitude->idestado == DEPOSITO_HABILITADO )
                    <a class="modal_deposit">
                        <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
            @endif
        @endif
    </div>
</td>