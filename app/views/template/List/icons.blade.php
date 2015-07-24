<td style="text-align: center">
    <!-- <div class="div-icons-solicituds"> -->
    <div class="btn-group btn-group-icon-md" role="group" >  
        <a class="btn btn-default timeLine" data-id="{{$solicitud->id}}">
            <span  class="glyphicon glyphicon-time"></span>
        </a>
        @if ( in_array( $solicitud->histories()->orderBy( 'updated_at' , 'DESC' )->first()->user_to , array( Auth::user()->type , Auth::user()->tempType() ) ) 
        && $solicitud->state->id_estado != R_NO_AUTORIZADO && $solicitud->id_estado != GENERADO )
            @if( $solicitud->idtiposolicitud == SOL_REP &&  in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) ) )
                @if( array_intersect( $solicitud->gerente()->lists( 'id_gerprod' ) , array( Auth::user()->id , Auth::user()->tempId() ) ) )
                    <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon glyphicon-edit"></span>
                    </a>
                @endif
            @else
                <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon glyphicon-edit"></span>
                </a>
            @endif    
        @endif
        @if ( $solicitud->idtiposolicitud == SOL_REP )
             @if( ( $solicitud->id_estado == REGISTRADO || $solicitud->id_estado == DEVOLUCION ) && Auth::user()->id == $solicitud->id_user_assign )
                <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon-print"></span>
                </a>
            @endif
            @if( Auth::user()->type == REP_MED)
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
            @endif
        @elseif ( $solicitud->idtiposolicitud == SOL_INST )
            @if( ( $solicitud->id_estado == REGISTRADO || $solicitud->id_estado == DEVOLUCION ) && Auth::user()->id == $solicitud->id_user_assign )   
                <a class="btn btn-default" href="{{URL::to('report-fondo')}}/{{$solicitud->token}}">
                    <span class="glyphicon glyphicon-print" ></span>
                </a>
            @endif
            @if ( Auth::user()->type == CONT )
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