<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Listado de Solicitudes a Depositar</title>
        {{ HTML::style('css/report.css') }}
    </head>
    <body style="background: url('img/logo-marcadeagua.png') no-repeat center fixed">
        <div class="background">
            <header>
                <img src="img/logo-report.png" style="width:170px">
                <h1 style="text-align:center"><strong>Solicitudes a Depositar</strong></h1>
            </header>
            <section style="text-align:center;height:auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Solicitud</th>
                            <th>Fecha a Depositar</th>
                            <th>Cliente</th>
                            <th>Tipo de Pago</th>
                            <th>Responsable</th>
                            <th>N° Cuenta / RUC</th>
                            <th>Monto a Depositar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $solicituds as $solicitud )
                            <tr>
                                
                                <td>{{ $solicitud->id }}</td>
                                <td>{{ $solicitud->titulo }} 
                                    @if( $solicitud->idtiposolicitud != SOL_INST )
                                        - {{ $solicitud->clients[ 0 ]->clientType->descripcion }} : {{{ $solicitud->clients[ 0 ]->{ $solicitud->clients[ 0 ]->clientType->relacion }->full_name or '' }}}</td>
                                    @endif
                                <td>{{ $solicitud->detalle->fecha_entrega }} </td>
                                <td>
                                    @if ( isset( $solicitud->clients[ 0 ] ) && ! is_null( $solicitud->clients[ 0 ] ) )
                                        {{ $solicitud->clients[ 0 ]->{ $solicitud->clients[ 0 ]->clientType->relacion }->full_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ( in_array( $solicitud->idtiposolicitud , array( SOL_REP , REEMBOLSO ) ) )
                                        {{ $solicitud->detalle->typePayment->nombre }}
                                    @else
                                        Transferencia
                                    @endif
                                </td>
                                <td>
                                    @if ( is_null ( $solicitud->asignedTo->personal ) )
                                        Sin Rol o No Autorizado
                                    @else
                                        {{ $solicitud->asignedTo->personal->full_name }}
                                    @endif
                                </td>
                                <td>
                                    @if ( $solicitud->detalle->id_pago != PAGO_CHEQUE )
                                        @if ( $solicitud->asignedTo->type == REP_MED && ! is_null( $solicitud->asignedTo->personal->bagoVisitador ) && ! is_null( $solicitud->asignedTo->personal->bagoVisitador->cuenta ) )
                                            {{ $solicitud->asignedTo->personal->bagoVisitador->cuenta->cuenta }}
                                        @endif
                                    @else
                                        RUC: {{ $solicitud->detalle->num_ruc }}         
                                    @endif
                                </td>
                                <td>{{ $solicitud->detalle->typeMoney->simbolo . ' ' . $solicitud->detalle->monto_actual }}</td>
                            </tr>    
                        @endforeach
                    </tbody>
                </table>
            </section>
            <footer>
                <p class="firma">V°B° Contabilidad</p>
                <div style="width:120px;text-align:center" ><span class="dni">{{ Auth::user()->personal->full_name }}</span></div>
            </footer>
        </div>
    </body>
</html>