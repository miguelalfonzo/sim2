<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte</title>
    {{ HTML::style('css/report.css') }}
</head>
<body style="background: url('img/logo-marcadeagua.png') center fixed no-repeat; filter: blur(20px);">
    <div class="background">
        <header style="margin-top:-2em;">
            <img src="img/logo-report.png" style="width:170px;margin-left:-5em;">
            <h1><p><strong>REPORTE DE GASTOS PUBLICIDAD Y PROMOCIÓN SEGÚN PRESUPUESTO</strong></p><h1>
        </header>
        <main>
            <section style="text-align:center;margin-top:2.5em;">
                <strong><p style="display:inline">Fecha:</strong>&nbsp;{{$date['toDay']}}</p>
                @if ( $solicitude->acceptHist->updatedBy->type == SUP )
                    <strong><p style="display:inline">Autorizado por:</strong>&nbsp;{{$solicitude->acceptHist->updatedBy->sup->full_name}}</p>
                    <strong><p style="display:inline">Cargo:</strong>&nbsp;Supervisor</p>
                @elseif ( $solicitude->acceptHist->updatedBy->type == GER_PROD )
                    <strong><p style="display:inline">Autorizado por:</strong>&nbsp;{{$solicitude->acceptHist->updatedBy->gerProd->full_name}}</p>
                    <strong><p style="display:inline">Cargo:</strong>&nbsp;G. Producto</p>
                @endif    
                <strong><p style="display:inline">Fondo:</strong>&nbsp;{{mb_convert_case($solicitude->detalle->fondo->nombre,MB_CASE_TITLE,'UTF-8')}}</p>
                <strong><p style="display:inline">Código Comercial:</strong>&nbsp;{{$solicitude->id}}</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                @if ( $solicitude->createdBy->type == REP_MED )
                    <strong>
                        <p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$solicitude->createdBy->rm->full_name}}</p>
                    <strong><p style="display:inline">Cargo:</strong>&nbsp;Representante Medico</p>
                @else ( $solicitude->createdBy->type == SUP )
                    <strong>
                        <p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$solicitude->createdBy->sup->full_name}}</p>    
                    <strong>
                        <p style="display:inline">Cargo:</strong>&nbsp;Supervisor</p>
                @endif
                <strong><p style="display:inline">Ciudad:</strong>&nbsp;Lima</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                <strong><p style="display:inline">Fecha de Depósito:</strong>&nbsp;{{$solicitude->detalle->deposit->updated_at}}</p>
                <strong><p style="display:inline">N° de Depósito:</strong>&nbsp;{{$solicitude->detalle->deposit->num_transferencia}}</p>
                @if ( isset($detalle->tcc) && isset($detalle->tcv) )
                    <strong><p style="display:inline">T. Cambio:</strong>&nbsp;C: {{$detalle->tcc}} V: {{$detalle->tcv}}</p>
                @endif
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
                        @foreach($expenses as $value)
                            <tr>
                                
                                <td>{{date('d/m/Y',strtotime($value->fecha_movimiento))}}</td>
                                <td>{{mb_convert_case($value->proof->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
                                <td>{{$value->num_prefijo.'-'.$value->num_serie}}</td>
                                <td>{{mb_convert_case($value->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
                                <td>{{$cmps}}</td>
                                <td>Dermatología</td>
                                <td>
                                    {{$clientes}}
                                </td>
                                <td>S/.{{ $value->monto }}</td>
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
                                <td class="border align-left">
                                    <strong>Total Reportado</strong>
                                </td>
                                <td class="border">
                                    <strong>
                                        <span class="symbol">S/.</span>
                                        <span class="total-expense">{{$total}}</span>
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
                                            @if ( $solicitude->detalle->deposit->account->idtipomoneda == DOLARES )
                                                {{ round ( $solicitude->detalle->deposit->total * $detalle->tcv , 2 , PHP_ROUND_HALF_DOWN ) }}
                                            @else
                                                {{ $solicitude->detalle->deposit->total }}    
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
                                <td class="border bottom">S/.{{$balance['bussiness']}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border bottom align-left">Saldo a favor del Empleado</td>
                                <td class="border bottom">S/.{{$balance['employed']}}</td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </section>
        </main>
        <footer>
            <p class="firma">FIRMA DEL EMPLEADO</p>
            <p class="firma">V°B° SUPERVISOR</p>
            <p class="firma">V°B° GERENTE COMERCIAL</p>
            <div>DNI:&nbsp;<span class="dni">{{$dni}}</span></div>
            <div>NOMBRE:&nbsp;<span class="dni">{{$created_by}}</span></div>
        </footer>
    </div>
</body>
</html>