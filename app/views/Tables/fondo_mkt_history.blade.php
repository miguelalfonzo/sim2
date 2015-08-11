@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <h3>Historial de los Fondos</h3>
    </div>
    <div id="fondo_mkt_history">
        <table class="table table-hover table-bordered table-condensed dataTable" id="table_fondo_mkt_history">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Razon</th>
                    <th>Fondo</th>
                    <th>Saldo</th>
                    <th>Saldo Neto</th>
                    <th>Fecha de Movimiento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $FondoMktHistories as $FondoMktHistory )
                    <tr>
                        <td class="text-center">{{$FondoMktHistory->id}}</td>
                        <td class="text-center">
                            @if ( ! is_null( $FondoMktHistory->id_solicitud ) )
                                {{ 'Solicitud #' . $FondoMktHistory->solicitud->id }}
                                @if( $FondoMktHistory->id_fondo_history_reason == 2 )
                                    RETENCION PARA APROBACIÓN
                                @elseif( $FondoMktHistory->id_fondo_history_reason == 3 )
                                    LIBERACION DE RETENCIÓN
                                @elseif( $FondoMktHistory->id_fondo_history_reason == 4 )
                                    DESCUENTO DE SALDO POR DEPOSITO
                                @elseif( $FondoMktHistory->id_fondo_history_reason == 5 )
                                    DEVOLUCION POR DESCUENTO
                                @endif
                            @elseif( is_null( $FondoMktHistory->id_from_fondo ) )
                                AJUSTE
                            @else
                                TRANSFERENCIA
                            @endif
                        </td>
                        <td class="text-center">
                            @if( is_null( $FondoMktHistory->id_from_fondo ) )
                                {{ $FondoMktHistory->toFund->full_name }}
                            @else
                                {{ $FondoMktHistory->fromFund->full_name . ' => ' . $FondoMktHistory->toFund->full_name }}    
                            @endif
                        </td>
                        <td>
                            @if( ! is_null( $FondoMktHistory->id_from_fondo ) )
                                @if( $FondoMktHistory->from_old_saldo == $FondoMktHistory->from_new_saldo )
                                    {{ 'S/.' . $FondoMktHistory->from_new_saldo }}
                                @else
                                    {{ 'S/.' . $FondoMktHistory->from_old_saldo . ' -> S/.' . $FondoMktHistory->from_new_saldo }}
                                @endif                            
                            @endif
                            
                            @if( ! is_null( $FondoMktHistory->id_from_fondo ) && ! is_null( $FondoMktHistory->id_to_fondo ) )
                                &nbsp;|&nbsp;
                            @endif

                            @if ( ! is_null( $FondoMktHistory->id_to_fondo ) )
                                @if( $FondoMktHistory->to_old_saldo == $FondoMktHistory->to_new_saldo )
                                    {{ 'S/.' . $FondoMktHistory->to_new_saldo }}
                                @else
                                    {{ 'S/.' . $FondoMktHistory->to_old_saldo . ' -> S/.' . $FondoMktHistory->to_new_saldo }}
                                @endif                            
                            @endif
                        </td>
                        <td>
                            @if( ! is_null( $FondoMktHistory->id_from_fondo ) )
                                @if( $FondoMktHistory->from_old_saldo_neto == $FondoMktHistory->from_new_saldo_neto )
                                    {{ 'S/.' . $FondoMktHistory->from_new_saldo_neto }}
                                @else
                                    {{ 'S/.' . $FondoMktHistory->from_old_saldo_neto . ' -> S/.' . $FondoMktHistory->from_new_saldo_neto }}
                                @endif                            
                            @endif
                            
                            @if( ! is_null( $FondoMktHistory->id_from_fondo ) && ! is_null( $FondoMktHistory->id_to_fondo ) )
                                &nbsp;|&nbsp;
                            @endif

                            @if ( ! is_null( $FondoMktHistory->id_to_fondo ) )
                                @if( $FondoMktHistory->to_old_saldo_neto == $FondoMktHistory->to_new_saldo_neto )
                                    {{ 'S/.' . $FondoMktHistory->to_new_saldo_neto }}
                                @else
                                    {{ 'S/.' . $FondoMktHistory->to_old_saldo_neto . ' -> S/.' . $FondoMktHistory->to_new_saldo_neto }}
                                @endif                            
                            @endif
                        </td>
                        <td class="text-center">{{$FondoMktHistory->updated_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        $(document).on( 'ready' , function()
        {
            dataTable( 'fondo_mkt_history' , null , 'registros' )
        })
    </script>
@stop