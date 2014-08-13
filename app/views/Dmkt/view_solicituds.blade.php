<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
    <thead>
    <tr>


        <th>Solicitud</th>
        <th>Presupuesto</th>
        <th>Estado</th>
        <th>Observaciones</th>
        <th>Edicion</th>
    </tr>
    </thead>
    <tbody>
    @foreach($solicituds as $solicitude)
    <tr>
        <td>{{$solicitude->titulo}}</td>
        <td>{{$solicitude->presupuesto}}</td>
        <td>@if($solicitude->estado_idestado == 1)
            <span class="label label-warning">Pendiente</span>
            @endif
            @if($solicitude->estado_idestado == 3)
            <span class="label label-danger">Rechazado</span>
            @endif
        </td>
        <td></td>
        <td><a href="registrar-gasto">reg_gasto</a></td>
    </tr>
    @endforeach
    </tbody>

</table>