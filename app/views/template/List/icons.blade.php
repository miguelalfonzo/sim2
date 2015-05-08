<td style="text-align: center">
    <!-- <div class="div-icons-solicituds"> -->
    <div class="btn-group btn-group-icon-lg" role="group" >
        <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitude->token)}}">
        <span  class="glyphicon glyphicon-eye-open"></span>
        </a>    
        @if ( $solicitude->idtiposolicitud == SOL_REP )
        @if( Auth::user()->type == REP_MED)
        <a class="btn btn-default timeLine">
        <span  class="glyphicon glyphicon-flag"></span>
        </a>
        @if($solicitude->idestado == REGISTRADO && Auth::user()->id == $solicitude->iduserasigned )
        <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
        <span  class="glyphicon glyphicon-print"></span>
        </a>
        @endif
        @if( $solicitude->idestado == PENDIENTE && $solicitude->created_by == Auth::user()->id )
        @if( $solicitude->status == 1 )
        <a class="btn btn-default" href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
        <span  class="glyphicon glyphicon-pencil"></span>
        </a>
        <a class="btn btn-default cancel-solicitude" data-idsolicitude="{{$solicitude->id}}" data-token="{{csrf_token()}}">
        <span  class="glyphicon glyphicon-remove"></span>
        </a>
        @endif
        @endif
        @elseif ( Auth::user()->type == SUP )
        @if( $solicitude->idestado == PENDIENTE && $solicitude->created_by == Auth::user()->id )
        <a class="btn btn-default" href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}">
        <span   class="glyphicon glyphicon-pencil"></span>
        </a>
        <a href="#" class="btn btn-default cancel-solicitude" data-idsolicitude = "{{$solicitude->id}}"  data-token="{{csrf_token()}}">
        <span  class="glyphicon glyphicon-remove"></span>
        </a>
        @endif
        @elseif ( Auth::user()->type == CONT )
        @if( $solicitude->idestado == REGISTRADO )
        <a class="btn btn-default" href="{{URL::to('generar-asiento-gasto/'.$solicitude->token)}}">
        <span  class="glyphicon glyphicon-book"></span>
        </a>
        @endif   
        @elseif ( Auth::user()->type == TESORERIA )
        @if( $solicitude->idestado == DEPOSITO_HABILITADO )
        <a class="btn btn-default modal_deposit">
        <span  class="glyphicon glyphicon-usd"></span>
        </a>
        @endif
        @elseif ( Auth::user()->type == ASIS_GER )
        @if($solicitude->idestado == REGISTRADO)
        <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}">
        <span  class="glyphicon glyphicon-print"></span>
        </a>
        @endif
        @endif
        @elseif ( $solicitude->idtiposolicitud == SOL_INST )
        @if ( Auth::user()->type == REP_MED )
        <a class="btn btn-default" data-toggle="modal" data-target=".timeLineModal">
        <span  class="glyphicon glyphicon-flag"></span>
        </a>
        @if($solicitude->idestado == REGISTRADO && Auth::user()->id == $solicitude->iduserasigned )   
        <a class="btn btn-default" href="{{URL::to('report-fondo')}}/{{$solicitude->token}}">
        <span class="glyphicon glyphicon-print" ></span>
        </a>
        @endif
        @elseif ( Auth::user()->type == CONT )
        @if( $solicitude->idestado == REGISTRADO )
        <a class="btn btn-default" href="{{URL::to('generar-asiento-gasto/'.$solicitude->token)}}">
        <span  class="glyphicon glyphicon-book"></span>
        </a>
        @endif  
        @elseif ( Auth::user()->type == TESORERIA )
        @if ( $solicitude->idestado == DEPOSITO_HABILITADO )
        <a class="btn btn-default modal_deposit">
        <span  class="glyphicon glyphicon-usd"></span>
        </a>
        @endif
        @endif
        @endif
    </div>
</td>