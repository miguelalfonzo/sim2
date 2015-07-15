<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte</title>
    {{ HTML::style('css/report.css') }}
</head>
<body style="background: url( {{ asset( 'img/logo-marcadeagua.png' ) }} ) no-repeat center fixed">
    <div>
        <header style="margin-top:-2em;">
            <img src="{{ URL::to( 'img/logo-report.png' ) }}" style="width:170px;padding-top:30px">
            <h1 style="text-align:center"><strong>REPORTE DE GASTOS DE FONDOS INSTITUCIONALES</strong></h1>
        </header>
        <main>
            <section style="text-align:center;margin-top:2.5em;">
                <strong><p style="display:inline">Fecha:</strong>&nbsp;{{$date['toDay']}}</p>
                <strong><p style="display:inline">Ciudad:</strong>&nbsp;Lima</p>
                <strong><p style="display:inline">Institucion:</strong>&nbsp;{{$fondo->titulo}}</p>
                <strong><p style="display:inline">Código Comercial:</strong>&nbsp;{{$fondo->id}}</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                <strong><p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$fondo->asignedTo->rm->full_name}}</p>
                <strong><p style="display:inline">Cargo:</strong>&nbsp;Representante Med.</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                <strong><p style="display:inline">Fecha de Deposito:</strong>&nbsp;{{$fondo->detalle->deposit->updated_at}}</p>
                <strong><p style="display:inline">N° de Depósito:</strong>&nbsp;{{$fondo->detalle->deposit->num_transferencia}}</p>    
            </section>    
            <section style="margin-top:2em;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>N° de Comprobante</th>
                            <th>Descripción</th>
                            <th>C.M.P.</th>
                            <th>Especialidad</th>
                            <th>Nombre del Médico o Institución</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expense as $value)
                            <tr>

                                <td>{{date('d/m/Y',strtotime($value->fecha_movimiento))}}</td>
                                <td>{{mb_convert_case($value->proof->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
                                <td>{{$value->num_prefijo.'-'.$value->num_serie}}</td>
                                <td>{{mb_convert_case($value->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
                                <td>
                                   ---
                                </td>
                                <td>Dermatología</td>
                                <td>
                                    --
                                </td>
                                <td>S/.{{$value->monto}}</td>
                            </tr>
                        @endforeach
                        <tfoot>
                            <tr>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white other"></td>
                                <td class="border align-left"><strong>Total Reportado</strong></td>
                                <td class="border">
                                    <strong>
                                        <span class="symbol">{{$fondo->detalle->typeMoney->simbolo}}</span>
                                        <span class="total-expense">&nbsp;{{$fondo->expenses->sum('monto')}}</span>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border align-left">Total Depositado</td>
                                <td class="border">
                                    <strong>
                                        <span class="symbol">S/.</span>
                                        <span class="total-expense">
                                            @if ( $fondo->detalle->deposit->account->idtipomoneda == DOLARES )
                                                {{ round( $fondo->detalle->deposit->total * $detalle->tcv , 2 , PHP_ROUND_HALF_DOWN ) }}
                                            @elseif ( $fondo->detalle->deposit->account->idtipomoneda == SOLES )
                                                {{ $fondo->detalle->deposit->total }}
                                            @endif 
                                            </span>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border bottom align-left">Aplicación Parcial del depósito</td>
                                <td class="border bottom">-</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border bottom align-left">Saldo a favor Compañía</td>
                                <td class="border bottom">{{$fondo->detalle->typeMoney->simbolo.' '.$balance['bussiness']}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border bottom align-left">Saldo a favor del Empleado</td>
                                <td class="border bottom">{{$fondo->detalle->typeMoney->simbolo.' '.$balance['employed']}}</td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </section>
            <section style="padding-top:70px">
                <p class="firma">FIRMA DEL EMPLEADO</p>
                <p class="firma">V°B° SUPERVISOR</p>
                <p class="firma">V°B° GERENTE COMERCIAL</p>
                <br>
                <p>DNI:{{$dni}}</p>
                </p></p>
                <br>
                <p>NOMBRE:{{$fondo->asignedTo->rm->full_name}}</p>
                </p></p>
            </section>
        </main>
    </div>
</body>
</html>