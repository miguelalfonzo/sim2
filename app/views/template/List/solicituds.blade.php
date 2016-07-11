<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="table_solicitudes" class="table table-striped table-hover table-bordered" >
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>
                @if( $user->type == TESORERIA )
                    Responsable
                @else
                    Solicitado por
                @endif
            </th>
            <th>
                @if( in_array( $user->type , array( TESORERIA , CONT ) ) ) 
                    Fecha de Depósito
                @else
                    Fecha de Solicitud
                @endif
            </th>
             @if( $user->type != TESORERIA )   
                <th>Aprobado por</th>                
                <th>Fecha de Aprobación</th>
            @endif
            <th>
                @if( $user->type == TESORERIA )
                    Deposito
                @else
                    Monto
                @endif
            </th>
            <th>Estado</th>
            <th>Tipo</th>
            <th class="col-xs-2 col-sm-2">Edicion</th>
            @if ( in_array( $user->type , array( GER_COM , CONT ) ) )
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <?php $now =  Carbon\Carbon::now(); ?>
        @foreach( $solicituds as $solicitud )
            <tr>
                @if ( in_array( $user->type , array( TESORERIA , GER_COM, CONT ) ) )
                    <input type="hidden" id="sol_token" class="i-tokens" value="{{$solicitud->token}}">
                    @if( ! is_null( $solicitud->id_user_assign ) )
                        <input type="hidden" value="{{ $solicitud->personalTo->full_name }}" class="benef">
                    @endif
                @endif
                <td class="text-center id_solicitud detail-control">{{$solicitud->id}}</td>
                <td class="text-left sol_titulo">
                    @if ( ! is_null( $solicitud->id_actividad ) )
                        <span class="label" style="margin-right:1em;background-color:{{$solicitud->activityTrash->color}}">
                            {{$solicitud->activityTrash->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitud->titulo}}</label>
                </td>
                <td class="text-center">
                    @if( $user->type == TESORERIA )
                        {{ $solicitud->personalTo->full_name }}    
                    @else
                        {{ $solicitud->createdPersonal->full_name }}
                    @endif
                </td>
                @if( in_array( $user->type , array( TESORERIA, CONT ) ))
                    @if ( $solicitud->idtiposolicitud == SOL_INST )
                        <?php $fecha_entrega = Carbon\Carbon::createFromFormat( 'Ym' , $solicitud->detalle->fecha_entrega );
                        $fecha_deposito = Carbon\Carbon::createFromFormat( 'Ym' , $solicitud->detalle->fecha_entrega )->firstOfMonth()->format( 'Y-m-d' ); ?>
                    @else
                        <?php $fecha_entrega = Carbon\Carbon::createFromFormat( 'd/m/Y' , $solicitud->detalle->fecha_entrega );
                        $fecha_deposito = Carbon\Carbon::createFromFormat( 'd/m/Y' , $solicitud->detalle->fecha_entrega )->format( 'Y-m-d' ); ?>
                    @endif
                    @if( $now >  $fecha_entrega )
                        <td class="text-center alert-danger">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>  
                            {{ $fecha_deposito}}
                         </td>  
                    @elseif ($now->diffInDays($fecha_entrega) <= 1 ) 
                         <td class="text-center alert-warning">
                            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                            <strong>{{ $fecha_deposito}}</strong>
                        </td>  
                    @else
                         <td class="text-center">
                            {{ $fecha_deposito }}
                        </td>  
                    @endif
                @else
                <td class="text-center">
                    {{ $solicitud->created_at }}
                    </td>
                @endif
                @if( $user->type !== TESORERIA )
                    <td class="text-center">
                        @if ( $solicitud->id_estado != PENDIENTE )
                            {{ $solicitud->lastHistory->createdPersonal->full_name }}
                        @else
                            -
                        @endif
                    </td>
                    @include('template.List.lastdate')
                @endif
                @if( $user->type == TESORERIA )
                    <input type="hidden" class="total_deposit" value="{{ $solicitud->detalle->monto_actual }}">
                    <td class="text-center deposit">
                        @if ( is_null( $solicitud->detalle->id_deposito ) )
                            {{ $solicitud->detalle->currency_money }}    
                        @else
                            {{ $solicitud->detalle->deposit->money_amount }}
                        @endif
                    </td>
                @else
                    <td class="text-center total_deposit">
                        {{ $solicitud->detalle->currency_money }}
                    </td>
                @endif
                @include('template.List.states')
                <td class="text-center">{{ $solicitud->typeSolicitude->nombre }}</td>
                @include('template.List.icons')
                @if ( in_array( $user->type , array( GER_COM , CONT ) ) )
                    <td class="text-center">
                        @if( $user->type === GER_COM )
                            @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) ) && 
                                $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario === $user->type && 
                                in_array( $user->id , $solicitud->managerEdit( $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario )->lists( 'id_gerprod' ) ) )
                                <input type="checkbox" name="mass-aprov">
                            @else
                                <input type="checkbox" name="mass-aprov" disabled>
                            @endif
                        @elseif( $user->type == CONT )
                            @if( $solicitud->id_estado == APROBADO )
                                <input type="checkbox" name="mass-aprov">
                            @else
                                <input type="checkbox" name="mass-aprov" disabled>    
                            @endif
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>