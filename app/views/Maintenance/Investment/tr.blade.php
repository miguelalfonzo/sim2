<tr type="Tipo_Inversion">
    <td disabled></td>
    <td class="nombre text-center" save=1>
        <input type="text" style="width:100%">
    </td>
    <td class="id_fondo_contable text-center" save=1>
        @include( 'Maintenance.Cell.fondo_contable' )
    </td>
    <td class="id_tipo_instancia_aprobacion text-center" save=1>
        @include( 'Maintenance.Cell.tipo_instancia_aprobacion' )
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