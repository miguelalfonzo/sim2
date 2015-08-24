<td style="text-align: center">
    <div class="btn-group btn-group-icon-md" role="group" >  
        <a class="btn btn-default timeLine" data-id="{{ $solicitud->id }}">
            <span class="glyphicon glyphicon-time"></span>
        </a>
        
        @if ( in_array( $solicitud->histories()->orderBy( 'updated_at' , 'DESC' )->first()->user_to , array( Auth::user()->type , Auth::user()->tempType() ) ) 
        && $solicitud->state->id_estado != R_NO_AUTORIZADO && $solicitud->id_estado != GENERADO )
            @if( $solicitud->idtiposolicitud == SOL_REP &&  in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) ) )
                @if( array_intersect( $solicitud->gerente()->lists( 'id_gerprod' ) , array( Auth::user()->id , Auth::user()->tempId() ) ) )
                    <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon-edit"></span>
                    </a>
                @endif
            @else
                @if ( $solicitud->id_estado == DEPOSITADO && Auth::user()->type == CONT )
                    <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                        <span class="glyphicon glyphicon-book"></span>
                    </a>              
                @elseif ( $solicitud->id_estado == REGISTRADO && Auth::user()->type == CONT )
                    <a class="btn btn-default" href="{{URL::to('generar-asiento-gasto/'.$solicitud->token)}}">
                        <span class="glyphicon glyphicon-book"></span>
                    </a>
                @elseif( $solicitud->id_estado != ENTREGADO && $solicitud->id_estado != DEPOSITO_HABILITADO )
                    <a class="btn btn-default" href="{{ URL::to( 'ver-solicitud/' . $solicitud->token ) }}">
                        <span class="glyphicon glyphicon-edit"></span>
                    </a>
                @endif         
            @endif    
        @endif
        
        @if( in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD ) ) )
            @if( $solicitud->id_estado == PENDIENTE && $solicitud->created_by == Auth::user()->id && $solicitud->status == 1 )
                <a class="btn btn-default" href="{{ URL::to( 'editar-solicitud' ) . '/' . $solicitud->token }}">
                    <span  class="glyphicon glyphicon-pencil"></span>
                </a>
                <a class="btn btn-default cancel-solicitude" data-idsolicitude="{{ $solicitud->id }}" data-token="{{csrf_token()}}">
                    <span  class="glyphicon glyphicon-remove"></span>
                </a>
            @endif
        @elseif ( Auth::user()->type == TESORERIA )
            @if( $solicitud->id_estado == DEPOSITO_HABILITADO )
                <a class="btn btn-default modal_deposit">
                    <span  class="glyphicon glyphicon-usd"></span>
                </a>
            @elseif( $solicitud->id_estado == DEPOSITADO )
                <a class="btn btn-default modal_extorno">
                    <span  class="glyphicon glyphicon-pencil"></span>
                </a>
            @endif
        @endif
        
        @if( $solicitud->id_estado == ENTREGADO )
            @if( Auth::user()->type == CONT && $solicitud->devolutions()->whereIn( 'id_estado_devolucion' , array( DEVOLUCION_POR_REALIZAR , DEVOLUCION_POR_VALIDAR ) )->get()->count() == 0 )
                <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon-edit"></span>
                </a>
            @elseif( Auth::user()->type == TESORERIA && $solicitud->devolutions()->where( 'id_estado_devolucion' , DEVOLUCION_POR_VALIDAR )->get()->count() !== 0 )
                <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon-edit"></span>
                </a>
            @elseif( Auth::user()->id == $solicitud->id_user_assign && $solicitud->devolutions()->where( 'id_estado_devolucion' , DEVOLUCION_POR_REALIZAR )->get()->count() !== 0 )
                <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon-edit"></span>
                </a>
            @endif
        @endif

         @if( $solicitud->id_estado == ENTREGADO && Auth::user()->id == $solicitud->id_user_assign )
            <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitud->token)}}">
                <span  class="glyphicon glyphicon-print"></span>
            </a>
        @endif
    </div>
</td>