<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte</title>
    {{ HTML::style('css/report.css') }}
</head>
<body>
    <div class="background">
        <header style="margin-top:-2em;">
            <img src="img/logo-report.png" alt="" style="width:170px;margin-left:-5em;">
            <p><strong>REPORTE DE GASTOS PUBLICIDAD Y PROMOCIÓN SEGÚN PRESUPUESTO</strong></p>
        </header>
        <main>
            <section style="text-align:center;margin-top:2.5em;">
                <strong><p style="display:inline">Fecha:</strong>&nbsp;{{$date['toDay']}}</p>
                <strong><p style="display:inline">Autorizado por:</strong>&nbsp;{{$name}}</p>
                <strong><p style="display:inline">Fondo:</strong>&nbsp;{{$solicitude->subtype->nombre}}</p>
                <strong><p style="display:inline">Código Comercial:</strong>&nbsp;{{$solicitude->idsolicitud}}</p>
            </section>
            <section style="text-align:center;margin-top:2em;">
                <strong><p style="display:inline">Colaborador Bagó:</strong>&nbsp;{{$name}}</p>
                <strong><p style="display:inline">Ciudad:</strong>&nbsp;Lima</p>
                <strong><p style="display:inline">Cargo:</strong>&nbsp;{{$charge}}</p>
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
                        <tr>
                            <th>18/07</th>
                            <th>F</th>
                            <th>2192</th>
                            <th>Obsequio de Baby Shower</th>
                            <th>38040</th>
                            <th>Dermatología</th>
                            <th>Sandy Solorzano</th>
                            <th>71.00</th>
                        </tr>
                    </tbody>
                </table>
            </section>
        </main>
        <footer>
        </footer>
    </div>
</body>
</html>