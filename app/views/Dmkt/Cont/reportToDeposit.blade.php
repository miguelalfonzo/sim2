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
            <h1 style="text-align:center"><strong>Solicitudes a Depositar</strong><h1>
        </header>
        <main>
            <section style="text-align:center;height:auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Solicitud</th>
                            <th>Fecha a Depositar</th>
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
                                <td>{{ $solicitud->titulo }}</td>
                                <td>{{ $solicitud->detalle->fecha_entrega }} </td>
                                <td>
                                    @if( $solicitud->idtiposolicitud == SOL_REP )
                                        {{ $solicitud->detalle->typePayment->nombre }}
                                    @else
                                        TRANSFERENCIA
                                    @endif
                                </td>
                                <td>
                                    @if ( $solicitud->asignedTo->type == REP_MED )
                                        {{ $solicitud->asignedTo->rm->full_name }}
                                    @elseif ( $solicitud->asignedTo->type == SUP )
                                        {{ $solicitud->asignedTo->sup->full_name }}
                                    @elseif ( $solicitud->asignedTo->type == GER_PROD )
                                        {{ $solicitud->asignedTo->gerProd->full_name }}
                                    @elseif ( ! is_null( $solicitud->asignedTo->simApp ) )
                                        {{ $solicitud->asignedTo->person->full_name }}
                                    @else
                                        Sin Rol o No Autorizado
                                    @endif
                                </td>
                                <td>
                                    @if ( $solicitud->detalle->id_pago != PAGO_CHEQUE )
                                        @if ( $solicitud->asignedTo->type == REP_MED && ! is_null( $solicitud->asignedTo->rm->bagoVisitador ) && ! is_null( $solicitud->asignedTo->rm->bagoVisitador->cuenta ) )
                                            N° Cta: {{ $solicitud->asignedTo->rm->bagoVisitador->cuenta->cuenta }}
                                        @else
                                            N° Cta:
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
        </main>
        <footer>
            <p class="firma">V°B° Contabilidad</p>
            <div style="width:120px;text-align:center" ><span class="dni">{{Auth::user()->person->full_name}}</span></div>
        </footer>
    </div>
</body>
</html>