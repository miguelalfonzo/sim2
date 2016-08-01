<td style="text-align: center">
    <div class="btn-group btn-group-icon-md" role="group" >  
        <a class="btn btn-default open-details" data-id="{{$solicitud->id}}">
            <span class="glyphicon glyphicon-eye-open"></span>
        </a>
        <a class="btn btn-default timeLine" data-id="{{ $solicitud->id }}">
            <span class="glyphicon glyphicon-time"></span>
        </a>
        @if ( in_array( $solicitud->histories()->orderBy( 'updated_at' , 'DESC' )->first()->user_to , array( $user->type , $user->tempType() ) ) 
        && $solicitud->state->id_estado != R_NO_AUTORIZADO && $solicitud->id_estado != GENERADO )
            @if( in_array( $solicitud->idtiposolicitud , array( SOL_REP , REEMBOLSO ) ) &&  in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) ) )
                @if( array_intersect( $solicitud->gerente()->lists( 'id_gerprod' ) , array( $user->id , $user->tempId() ) ) )
                    <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                        <span  class="glyphicon glyphicon-edit"></span>
                    </a>
                @endif
            @else
                @if ( $solicitud->id_estado == DEPOSITADO && $user->type == CONT )
                    <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                        <span class="glyphicon glyphicon-book"></span>
                    </a>              
                @elseif ( $solicitud->id_estado == REGISTRADO && $user->type == CONT )
                    <a class="btn btn-default" href="{{URL::to('generar-asiento-gasto/'.$solicitud->token)}}">
                        <span class="glyphicon glyphicon-book"></span>
                    </a>
                @elseif( $solicitud->id_estado != 30 && $solicitud->id_estado != ENTREGADO && $solicitud->id_estado != DEPOSITO_HABILITADO && $user->type !== ASIS_GER ) 
                    <a class="btn btn-default" href="{{ URL::to( 'ver-solicitud/' . $solicitud->token ) }}">
                        <span class="glyphicon glyphicon-edit"></span>
                    </a>
                @endif         
            @endif    
        @endif
        @if( in_array( $user->type , array( REP_MED , SUP , GER_PROD , ASIS_GER ) ) )
            @if( $solicitud->id_estado == PENDIENTE && $solicitud->created_by == $user->id && $solicitud->status == 1 )
                <a class="btn btn-default" href="{{ URL::to( 'editar-solicitud' ) . '/' . $solicitud->token }}">
                    <span  class="glyphicon glyphicon-pencil"></span>
                </a>
                <a class="btn btn-default cancel-solicitude" data-idsolicitude="{{ $solicitud->id }}" data-token="{{csrf_token()}}">
                    <span  class="glyphicon glyphicon-remove"></span>
                </a>
            @endif
        @elseif ( $user->type == TESORERIA )
            @if( $solicitud->id_estado == DEPOSITO_HABILITADO )
                <a class="btn btn-default modal_deposit">
                    <span  class="glyphicon glyphicon-usd"></span>
                </a>
            @elseif( $solicitud->id_estado == DEPOSITADO )
                <a class="btn btn-default modal_extorno">
                    <span  class="glyphicon glyphicon-pencil"></span>
                </a>
            @endif
        @elseif ( $user->type == CONT )
            @if( $solicitud->idtiposolicitud != REEMBOLSO && in_array( $solicitud->id_estado , array( DEPOSITADO , GASTO_HABILITADO ) ) )
                <a class="btn btn-default modal_liquidacion">
                    <span class="glyphicon glyphicon-inbox"></span>
                </a>
            @elseif( $solicitud->id_estado == APROBADO || ( $solicitud->idtiposolicitud == SOL_REP && $solicitud->id_estado == DEPOSITO_HABILITADO ) || ( $solicitud->idtiposolicitud == REEMBOLSO && $solicitud->id_estado == GASTO_HABILITADO ) )
                <a class="btn btn-default cancel-solicitude" data-idsolicitude="{{ $solicitud->id }}" data-token="{{csrf_token()}}">
                    <span  class="glyphicon glyphicon-remove"></span>
                </a>
            @endif
        @endif
                
        @if( $solicitud->id_estado == ENTREGADO )
            @if( $user->type == TESORERIA && $solicitud->devolutions()->where( 'id_estado_devolucion' , DEVOLUCION_POR_VALIDAR )->get()->count() !== 0 )
                <a class="btn btn-default get-devolution-info" data-type="confirm-inmediate-devolution">
                    <span  class="glyphicon glyphicon-transfer"></span>
                </a>
            @elseif( ( $user->type == CONT || $user->id == $solicitud->id_user_assign ) && $solicitud->devolutions()->whereIn( 'id_estado_devolucion' , array( DEVOLUCION_POR_REALIZAR , DEVOLUCION_POR_VALIDAR ) )->get()->count() == 0 )
                <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon-edit"></span>
                </a>
            @elseif( $user->id == $solicitud->id_user_assign && $solicitud->devolutions()->where( 'id_estado_devolucion' , DEVOLUCION_POR_REALIZAR )->get()->count() !== 0 )
                <a class="btn btn-default" href="{{URL::to('ver-solicitud/'.$solicitud->token)}}">
                    <span  class="glyphicon glyphicon-edit"></span>
                </a>
            @endif
        @endif
                
        @if ( ! is_null( $solicitud->toDeliveredHistory ) && ( $user->type === CONT || $user->id === $solicitud->id_user_assign ) )
            <a class="btn btn-default" target="_blank" href="{{URL::to('a'.'/'.$solicitud->token)}}">
                <span  class="glyphicon glyphicon-print"></span>
            </a>
        @endif
    </div>
</td>