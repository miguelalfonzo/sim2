<table class="table table-hover table-bordered table-condensed dataTable" id="table_accounts_mark_rel" style="width: 100%">
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
                <td style="text-align: center">{{$iAccount->id}}</td>
                <td class="idfondo" style="text-align:center">{{$iAccount->accountFondo->fondo->nombre}}</td>
                <td class="idcuentafondo" editable=3 style="text-align:center">{{$iAccount->accountFondo->num_cuenta}}</td>
                <td class="fcuenta-nombre" style="text-align:center">{{$iAccount->accountFondo->bagoAccount->ctanombrecta}}</td>
                <td class="fcuenta-type" style="text-align:center">{{$iAccount->accountFondo->bagoAccount->ctatipocta}}</td>
                <td class="idcuentagasto" editable=3 style="text-align:center">{{$iAccount->accountExpense->num_cuenta}}</td>
                <td class="ecuenta-nombre" style="text-align:center">{{$iAccount->accountExpense->bagoAccount->ctanombrecta}}</td>
                <td class="ecuenta-type" style="text-align:center">{{$iAccount->accountExpense->bagoAccount->ctatipocta}}</td>
                <td class="idmarca" editable=3 style="text-align:center">{{$iAccount->mark->codigo}}</td>
                <td class="iddocumento" editable=1 style="text-align:center">{{$iAccount->document->codigo}}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit" href="#">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <!-- <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a> -->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>