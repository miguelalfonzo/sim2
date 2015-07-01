<td style="text-align: center">
    <!-- <div class="div-icons-solicituds"> -->
    <div class="btn-group btn-group-icon-lg" role="group" >
        <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
            <span  class="glyphicon glyphicon-eye-open"></span>
        </a>    
        <a class="btn btn-default timeLine" data-id="{{$solicitud->id}}">
            <span  class="glyphicon glyphicon-flag"></span>
        </a>        
        @if ( $solicitud->idtiposolicitud == SOL_REP )
            @if( Auth::user()->type == REP_MED)
                <!-- <a class="btn btn-default timeLine">
                    <span  class="glyphicon glyphicon-flag"></span>
                </a> -->        
                @if($solicitud->id_estado == REGISTRADO && Auth::user()->id == $solicitud->iduserasigned )
                    <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon-print"></span>
                    </a>
                @endif
                @if( $solicitud->id_estado == PENDIENTE && $solicitud->created_by == Auth::user()->id )
                    @if( $solicitud->status == 1 )
                        <a class="btn btn-default" href="{{URL::to('editar-solicitud').'/'.$solicitud->token}}">
                            <span  class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a class="btn btn-default cancel-solicitude" data-idsolicitude="{{$solicitud->id}}" data-token="{{csrf_token()}}">
                            <span  class="glyphicon glyphicon-remove"></span>
                        </a>
                    @endif
                @endif
            @elseif ( Auth::user()->type == SUP )
                @if( $solicitud->id_estado == PENDIENTE && $solicitud->created_by == Auth::user()->id )
                    <a class="btn btn-default" href="{{URL::to('editar-solicitud').'/'.$solicitud->token}}">
                        <span   class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="#" class="btn btn-default cancel-solicitude" data-idsolicitude = "{{$solicitud->id}}"  data-token="{{csrf_token()}}">
                        <span  class="glyphicon glyphicon-remove"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == GER_PROD )
                @if( $solicitud->id_estado == PENDIENTE && $solicitud->created_by == Auth::user()->id )
                    <a class="btn btn-default" href="{{URL::to('editar-solicitud').'/'.$solicitud->token}}">
                        <span   class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a href="#" class="btn btn-default cancel-solicitude" data-idsolicitude = "{{$solicitud->id}}"  data-token="{{csrf_token()}}">
                        <span  class="glyphicon glyphicon-remove"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == CONT )
                @if( $solicitud->id_estado == REGISTRADO )
                    <a class="btn btn-default" href="{{URL::to('generar-asiento-gasto/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon-book"></span>
                    </a>
                @endif   
            @elseif ( Auth::user()->type == TESORERIA )
                @if( $solicitud->id_estado == DEPOSITO_HABILITADO )
                    <a class="btn btn-default modal_deposit">
                        <span  class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == ASIS_GER )
                @if($solicitud->id_estado == REGISTRADO)
                    <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon-print"></span>
                    </a>
                @endif
            @endif
        @elseif ( $solicitud->idtiposolicitud == SOL_INST )
            @if ( Auth::user()->type == REP_MED )
                <!-- <a class="btn btn-default timeLine">
                    <span  class="glyphicon glyphicon-flag"></span>
                </a> -->
                @if($solicitud->id_estado == REGISTRADO && Auth::user()->id == $solicitud->iduserasigned )   
                    <a class="btn btn-default" href="{{URL::to('report-fondo')}}/{{$solicitud->token}}">
                        <span class="glyphicon glyphicon-print" ></span>
                    </a>
                @endif
            @elseif ( Auth::user()->type == CONT )
                @if( $solicitud->id_estado == REGISTRADO )
                    <a class="btn btn-default" href="{{URL::to('generar-asiento-gasto/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon-book"></span>
                    </a>
                @endif  
            @elseif ( Auth::user()->type == TESORERIA )
                @if ( $solicitud->id_estado == DEPOSITO_HABILITADO )
                    <a class="btn btn-default modal_deposit">
                        <span  class="glyphicon glyphicon-usd"></span>
                    </a>
                @endif
            @endif
        @endif
    </div>
</td>