<table class="table table-hover table-bordered table-condensed dataTable" id="table_fondo_mkt_history">
    <thead>
        <tr>
            <th>#</th>
            <th>Fuente</th>
            <th>Movimiento</th>
            <th>Razon</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $FondoMktHistories as $FondoMktHistory )
            <tr>
                <td class="text-center">{{$FondoMktHistory->id}}</td>
                <td class="text-center">
                    @if ( is_null( $FondoMktHistory->id_solicitud ) )
                        {{ $FondoMktHistory->solicitud->titulo }}
                    @elseif( is_null( $FondoMktHistory->id_from_fondo ) )
                        AJUSTE
                    @else
                        TRANSFERENCIA
                    @endif
                </td>
                <td class="text-center">
                    @if ( is_null( $FondoMktHistory->id_solicitud ) )
                        {{ $FondoMktHistory->toFund->descripcion . ' ' . $FondoMktHistory->to_old_saldo . ' -> ' . $FondoMktHistory->to_new_saldo . ' | ' . 
                        $FondoMktHistory->to_old_saldo_neto . ' -> ' . $FondoMktHistory->to_new_saldo_neto }}
                    @elseif( is_null( $FondoMktHistory->id_from_fondo ) )
                        {{ $FondoMktHistory->toFund->descripcion . ' ' . $FondoMktHistory->to_old_saldo . ' -> ' . $FondoMktHistory->to_new_saldo }}
                    @else
                        {{ $FondoMktHistory->fromFund->descripcion . ' ' . $FondoMktHistory->from_old_saldo . ' -> ' . $FondoMktHistory->from_new_saldo . ' => ' . 
                        $FondoMktHistory->toFund->descripcion . ' ' . $FondoMktHistory->to_old_saldo . ' -> ' . $FondoMktHistory->to_new_saldo }}    
                    @endif
                </td>
                <td class="text-center">
                    {{ $FondoMktHistory->id_fondo_history_reason }}
                </td>
                <td class="text-center">{{$FondoMktHistory->updated_at}}</td>
            </tr>
        @endforeach
    </tbody>
</table>