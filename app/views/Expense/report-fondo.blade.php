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
            <img src="img/logo-report.png" alt="" style="width:170px;margin-left:-5em;">
            <p><strong>REPORTE DE GASTOS DE FONDOS INSTITUCIONALES</strong></p>
        </header>
        <main>
            <section style="text-align:center;margin-top:2.5em;">
                <strong><p style="display:inline">Fecha:</strong>&nbsp;{{$date['lastDay']}}</p>
                <strong><p style="display:inline">Ciudad:</strong>&nbsp;Lima</p>
                <strong><p style="display:inline">Institucion:</strong>&nbsp;{{$fondo->institucion}}</p>
                <strong><p style="display:inline">Código Comercial:</strong>&nbsp;{{$fondo->idfondo}}</p>
                <strong><p style="display:inline">N° de Depósito:</strong>&nbsp;{{$fondo->deposit->num_transferencia}}</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                <strong><p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$fondo->repmed}}</p>

                <strong><p style="display:inline">Cargo:</strong>&nbsp;Representante Med.</p>
                <strong><p style="display:inline">Fecha de Depósito:</strong>&nbsp;{{date_format(date_create($fondo->deposit->created_at), 'd/m/Y' )}}</p>
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
                                <td>{{mb_convert_case($value->idProofType->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
                                <td>{{$value->num_prefijo.'-'.$value->num_serie}}</td>
                                <td>{{mb_convert_case($value->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
                                <td>
                                   ---
                                </td>
                                <td>Dermatología</td>
                                <td>
                                    --
                                </td>
                                <td>{{$value->monto}}</td>
                            </tr>
                        @endforeach
                        <!-- <tr>
                            <td>18/07</td>
                            <td>Factura</td>
                            <td>2192</td>
                            <td>Obsequio de Baby Shower</td>
                            <td>38040</td>
                            <td>Dermatología</td>
                            <td>Sandy Solorzano</td>
                            <td>70.00</td>
                        </tr>
                        <tr>
                            <td>18/07</td>
                            <td>Factura</td>
                            <td>2192</td>
                            <td>Obsequio de Baby Shower</td>
                            <td>38040</td>
                            <td>Dermatología</td>
                            <td>Sandy Solorzano</td>
                            <td>30.00</td>
                        </tr> -->
                        <tfoot>
                            <tr>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white"></td>
                                <td class="border-white other"></td>
                                <td class="border align-left"><strong>Total Reportado</strong></td>
                                <td class="border"><strong><span class="symbol">S/.</span><span class="total-expense">&nbsp;{{$fondo->total}}</span></strong></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border align-left">Total Depositado</td>
                                <td class="border"><strong><span class="symbol">S/.</span><span class="total-expense">&nbsp;{{$fondo->total}}</span></strong></td>
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
                                <td class="border bottom">-</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="other"></td>
                                <td class="border bottom align-left">Saldo a favor del Empleado</td>
                                <td class="border bottom">-</td>
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
            <div>DNI:&nbsp;<span class="dni">44595954</span></div>
            <div>NOMBRE:&nbsp;<span class="dni">{{$fondo->repmed}}</span></div>
        </footer>
    </div>
</body>
</html>