<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listado de Solicitudes a Depositar</title>
    {{ HTML::style('css/report.css') }}
</head>
<body style="background: url('img/logo-marcadeagua.png') center fixed no-repeat; filter: blur(20px);">
    <div class="background">
        <header style="margin-top:-2em;">
            <img src="img/logo-report.png" style="width:170px;margin-left:-5em;">
            <h1 style="text-align:center"><strong>Solicitudes a Depositar</strong><h1>
        </header>
        <main>
            <section style="text-align:center;margin-top:2.5em;height:auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titulo</th>
                            <th>Fecha a Depositar</th>
                            <th>Tipo de Deposito</th>
                            <th>Depositar A</th>
                            <th>N° Cuenta</th>
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
                                    @if ( $solicitud->asignedTo->type == REP_MED && ! is_null( $solicitud->asignedTo->rm->bagoVisitador ) && ! is_null( $solicitud->asignedTo->rm->bagoVisitador->cuenta ) )
                                        {{ $solicitud->asignedTo->rm->bagoVisitador->cuenta->cuenta }}
                                    @else
                                        Ingrese el N° Cuenta :                 
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