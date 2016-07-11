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
                </table>
            </section>
            <footer style="bottom:0">
                <p class="firma">V°B° Contabilidad</p>
                <div style="width:120px;text-align:center" ><span class="dni">{{ Auth::user()->personal->full_name }}</span></div>
            </footer>
        </div>
    </body>
</html>