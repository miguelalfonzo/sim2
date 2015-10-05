<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="table_solicitudes" class="table table-striped table-hover table-bordered" >
    <thead>
        <tr>
            <th></th>
            <th>#</th>
            <th>Solicitud</th>
            <th>
                @if( Auth::user()->type == TESORERIA )
                    Responsable
                @else
                    Solicitado por
                @endif
            </th>
            <th>
                @if( in_array( Auth::user()->type , array( TESORERIA , CONT ) )) 
                    Fecha de Depósito
                @else
                    Fecha de Solicitud
                @endif
            </th>
            <th>Aprobado por</th>
            <th>Fecha de Aprobación</th>
            <th>
                @if( Auth::user()->type == TESORERIA )
                    Deposito
                @else
                    Monto
                @endif
            </th>
            <th>Estado</th>
            <th>Tipo</th>
            <th class="col-xs-2 col-sm-2">Edicion</th>
            @if ( in_array( Auth::user()->type , array( GER_COM , CONT ) ) )
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach( $solicituds as $solicitud )
            <tr>
                <td class="text-center open-details" data-id="{{$solicitud->id}}">
                    <a class="btn btn-default">
                        <span class="glyphicon glyphicon-eye-open"></span>
                    </a>
                </td>
                @if($solicitud->state->id_estado == R_NO_AUTORIZADO )
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}" data-rejected="{{$solicitud->rejectedHist->user_from}}">
                @elseif( ! is_null( $solicitud->state ) && $solicitud->id_estado != TODOS )
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}" data-accept="$solicitud->acceptHist->user_from">
                @else
                    <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}">
                @endif
                @if ( in_array( Auth::user()->type , array( TESORERIA , GER_COM, CONT ) ) )
                    <input type="hidden" id="sol_token" class="i-tokens" value="{{$solicitud->token}}">
                    @if( ! is_null( $solicitud->id_user_assign ) )
                        <input type="hidden" value="{{ $solicitud->asignedTo->personal->full_name }}" class="benef">
                    @endif
                @endif
                <td class="text-center id_solicitud detail-control">{{$solicitud->id}}</td>
                <td class="text-left sol_titulo">
                    @if (! is_null( $solicitud->id_actividad ) )
                        <span class="label" style="margin-right:1em;background-color:{{$solicitud->activityTrash->color}}">
                            {{$solicitud->activityTrash->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitud->titulo}}</label>
                </td>

                <td class="text-center">
                    @if( Auth::user()->type == TESORERIA )
                        {{ $solicitud->asignedTo->personal->full_name }}    
                    @else
                        {{ $solicitud->createdBy->personal->full_name }}
                    @endif
                </td>
                 @if( in_array( Auth::user()->type , array( TESORERIA, CONT ) ))
                    
                    <?php $now =  Carbon\Carbon::now();
                     $fecha_entrega = Carbon\Carbon::createFromFormat( 'd/m/Y' , $solicitud->detalle->fecha_entrega );
                     $fecha_deposito = Carbon\Carbon::createFromFormat( 'd/m/Y' , $solicitud->detalle->fecha_entrega )->format( 'Y-m-d' ); ?>

                    @if($now >  $fecha_entrega)
                    <td class="text-center alert-danger">
                     <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>  
                     {{ $fecha_deposito}}
                     </td>  
                    @elseif ($now->diffInDays($fecha_entrega) <= 5 ) 
                     <td class="text-center alert-warning">
                        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
                        <strong>{{ $fecha_deposito}}</strong>
                    </td>  
                    @else
                     <td class="text-center">
                    {{ $fecha_deposito}}
                    </td>  
                    @endif
                @else
                <td class="text-center">
                    {{ $solicitud->created_at }}
                    </td>
                @endif
                <td class="text-center">
                    @if ( $solicitud->id_estado != PENDIENTE )
                        @if( $solicitud->lastHistory->count() != 0 )
                            @if (is_object($solicitud->lastHistory->user ) )
                                {{ $solicitud->lastHistory->user->personal->getFullName() }}
                            @else
                                -
                            @endif
                        @endif
                    @else
                        -
                    @endif
                </td>
                @include('template.List.lastdate')
                @if( Auth::user()->type == TESORERIA )
                    <input type="hidden" class="total_deposit" value="{{ $solicitud->detalle->monto_actual }}">
                    <td class="text-center deposit">
                        @if ( is_null( $solicitud->detalle->id_deposito ) )
                            {{ $solicitud->detalle->typemoney->simbolo .' '. $solicitud->detalle->monto_actual }}    
                        @else
                            {{ $solicitud->detalle->deposit->account->typeMoney->simbolo . ' ' . $solicitud->detalle->deposit->total }}
                        @endif
                    </td>
                @else
                    <td class="text-center total_deposit">
                        {{ $solicitud->detalle->typeMoney->simbolo . ' ' . $solicitud->detalle->monto_actual }}
                    </td>
                @endif

                @include('template.List.states')
                
                <td class="text-center">{{ $solicitud->typeSolicitude->nombre }}</td>
                
                @include('template.List.icons')
                
                @if ( in_array( Auth::user()->type , array( GER_COM , CONT ) ) )
                    <td class="text-center">
                        @if( Auth::user()->type === GER_COM )
                            @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) ) && 
                                $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario === Auth::user()->type && 
                                in_array( Auth::user()->id , $solicitud->managerEdit( $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario )->lists( 'id_gerprod' ) ) )
                                <input type="checkbox" name="mass-aprov">
                            @else
                                <input type="checkbox" name="mass-aprov" disabled>
                            @endif
                        @elseif( Auth::user()->type == CONT )
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