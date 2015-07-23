<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte</title>
    {{ HTML::style('css/report.css') }}
</head>
<body style="background: url( {{ asset( 'img/logo-marcadeagua.png' ) }} ) no-repeat center fixed">
    <div>
        <header style="margin-top:-2em;">
            <img src="{{ URL::to( 'img/logo-report.png' ) }}" style="width:170px;padding-top:30px">
            <h1 style="text-align:center"><strong>REPORTE DE GASTOS PUBLICIDAD Y PROMOCIÓN SEGÚN PRESUPUESTO</strong></h1>
        </header>
        <main>
            <section style="text-align:center;margin-top:2.5em;">
                <strong><p style="display:inline">Fecha:</strong>&nbsp;{{$date['toDay']}}</p>
                @if ( $solicitud->approvedHistory->updatedBy->type == SUP )
                    <strong><p style="display:inline">Autorizado por:</strong>&nbsp;{{$solicitud->approvedHistory->updatedBy->sup->full_name}}</p>
                    <strong><p style="display:inline">Cargo:</strong>&nbsp;Supervisor</p>
                @elseif ( $solicitud->approvedHistory->updatedBy->type == GER_PROD )
                    <strong><p style="display:inline">Autorizado por:</strong>&nbsp;{{$solicitud->approvedHistory->updatedBy->gerProd->full_name}}</p>
                    <strong><p style="display:inline">Cargo:</strong>&nbsp;G. Producto</p>
                @endif    
                <strong><p style="display:inline">Fondo:</strong>&nbsp;{{mb_convert_case($solicitud->products[0]->thisSubFondo->subCategoria->accountFondo->nombre,MB_CASE_TITLE,'UTF-8')}}</p>
                <strong><p style="display:inline">Código Comercial:</strong>&nbsp;{{$solicitud->id}}</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                @if ( $solicitud->createdBy->type == REP_MED )
                    <strong>
                        <p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$solicitud->createdBy->rm->full_name}}</p>
                    <strong><p style="display:inline">Cargo:</strong>&nbsp;Representante Medico</p>
                @else ( $solicitud->createdBy->type == SUP )
                    <strong>
                        <p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$solicitud->createdBy->sup->full_name}}</p>    
                    <strong>
                        <p style="display:inline">Cargo:</strong>&nbsp;Supervisor</p>
                @endif
                <strong><p style="display:inline">Ciudad:</strong>&nbsp;Lima</p>
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
                                            @if ( is_null( $solicitud->detalle->deposit ) )
                                                -
                                            @else
                                                @if ( $solicitud->detalle->deposit->account->idtipomoneda == DOLARES )
                                                    {{ round ( $solicitud->detalle->deposit->total * $detalle->tcv , 2 , PHP_ROUND_HALF_DOWN ) }}
                                                @else
                                                    {{ $solicitud->detalle->deposit->total }}    
                                                @endif
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
            @foreach( $solicitud->toAcceptedApprovedHistories as $acceptedApprovedHistory )
                @if( ! is_null( $solicitud->userApprovalPolicy( $acceptedApprovedHistory->user_from ) ) && ! ( is_null( $solicitud->userApprovalPolicy( $acceptedApprovedHistory->user_from )->desde ) 
                && is_null( $solicitud->userApprovalPolicy( $acceptedApprovedHistory->user_from )->hasta ) ) )
                    <p class="firma">V°B° {{ $acceptedApprovedHistory->fromUserType->descripcion }}</p>
                @endif
            @endforeach
            <div>DNI:&nbsp;<span class="dni">{{$dni}}</span></div>
            <div>NOMBRE:&nbsp;<span class="dni">{{$created_by}}</span></div>
        </footer>
    </div>
</body>
</html>