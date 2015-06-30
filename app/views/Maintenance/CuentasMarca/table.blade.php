<table class="table table-hover table-bordered table-condensed dataTable" id="table_cuentas-marca" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Concepto del Reporte</th>
            <th>Cuenta</th>
            <th>Nombre</th>
            <th>CTATIPOCTA</th>
            <th>Cuenta</th>
            <th>Nombre</th>
            <th>CTATIPOCTA</th>
            <th>Marca</th>
            <th>Doc</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $iAccounts as $iAccount )
            <tr row-id="{{$iAccount->id}}" type="cuentas-marca">
                <td style="text-align:center">{{$iAccount->id}}</td>
                <td class="idfondo" style="text-align:center">
                    @if ( is_null( $iAccount->fondo ) )
                        No hay Fondo con esa cuenta
                    @else
                        {{$iAccount->fondo->nombre}}
                    @endif
                </td>
                <td class="num_cuenta_fondo" editable=3 style="text-align:center">{{$iAccount->num_cuenta_fondo}}</td>
                <td style="text-align:center">{{$iAccount->bagoAccountFondo->ctanombrecta}}</td>
                <td style="text-align:center">{{$iAccount->bagoAccountFondo->ctatipocta}}</td>
                <td class="num_cuenta_gasto" editable=3 style="text-align:center">{{$iAccount->num_cuenta_gasto}}</td>
                <td style="text-align:center">{{$iAccount->bagoAccountExpense->ctanombrecta}}</td>
                <td style="text-align:center">{{$iAccount->bagoAccountExpense->ctatipocta}}</td>
                <td class="marca_codigo" editable=3 style="text-align:center">{{$iAccount->marca_codigo}}</td>
                <td class="iddocumento" editable=1 style="text-align:center">{{$iAccount->document->codigo}}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit" href="#">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>