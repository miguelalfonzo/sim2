<tr type="cuentas-marca">
    <td style="text-align:center" disabled></td>
    <td class="idfondo" style="text-align:center" disabled></td>
    <td class="num_cuenta_fondo" save=1 style="text-align:center">
        <input type="text" style="width:100%" maxlength=7>
    </td>
    <td class="fcuenta-nombre" disabled style="text-align:center"></td>
    <td class="fcuenta-type" disabled style="text-align:center"></td>
    <td class="num_cuenta_gasto" save=1 style="text-align:center">
        <input type="text" style="width:100%" maxlength=7>
    </td>
    <td class="ecuenta-nombre" style="text-align:center"></td>
    <td class="ecuenta-type" style="text-align:center"></td>
    <td class="marca_codigo" save=1 style="text-align:center">
        <input type="text" style="width:100%" maxlength=6>
    </td>
    <td class="iddocumento" save=1 style="text-align:center">
        @include('Maintenance.Cuentasmarca.documento')
    </td>
    <td style="text-align:center">
        <a class="maintenance-save" href="#">
            <span class="glyphicon glyphicon-floppy-disk"></span>
        </a>
        <a class="maintenance-cancel" href="#">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </td>
</tr>