<tbody>
    @foreach( $solicituds as $solicitud )
        <tr>
            <td>{{ $solicitud->id }}</td>
            <td>{{ $solicitud->titulo }} 
                @if( $solicitud->idtiposolicitud != SOL_INST )
                    - {{ $solicitud->clients[ 0 ]->clientType->descripcion }} : {{{ $solicitud->clients[ 0 ]->{ $solicitud->clients[ 0 ]->clientType->relacion }->full_name or '' }}}</td>
                @endif
            <td>{{ $solicitud->detalle->fecha_entrega }}</td>
            <td>
                {{{ $solicitud->clients[ 0 ]->{ $solicitud->clients[ 0 ]->clientType->relacion }->full_name or '-' }}}
            </td>
            <td>
                {{ $solicitud->detalle->typePayment->nombre or 'TRANSFERENCIA' }}
            </td>
            <td>
                {{ $solicitud->personalTo->full_name }}
            </td>
            <td>
                @if( $solicitud->id_inversion == 36 )
                    @if( $solicitud->detalle->id_moneda == 1 )
                        194-1732292-098
                    @elseif( $solicitud->detalle->id_moneda == 2 )
                        194-1809102-167
                    @endif
                @elseif( $solicitud->detalle->id_pago != PAGO_CHEQUE )
                    @if( in_array( $solicitud->assignedTo->type , [ REP_MED , SUP ] , 1 ) )
                        {{ $solicitud->personalTo->getAccount()  }}
                    @endif
                @else
                    RUC: {{ $solicitud->detalle->num_ruc }}         
                @endif
            </td>
            <td>{{ $solicitud->detalle->currency_money }}</td>
        </tr>    
    @endforeach
</tbody>