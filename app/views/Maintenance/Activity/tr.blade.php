<tr type="Tipo_Actividad">
    <td disabled></td>
    <td class="nombre text-center" save=1>
        <input type="text" style="width:100%">
    </td>
    <td class="tipo_cliente text-center" save=1>
        @include('Maintenance.Activity.tipocliente')
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