<table class="table table-striped table-bordered dataTable" id="table_activity_rm" style="width: 100%">
    <thead>
    <tr>
        <th>Num. Comprobante</th>
        <th>Solicitud</th>
        <th>Monto</th>
        <th>Responsable</th>
        <th>Estado</th>

    </tr>
    </thead>
    <tbody>
    @foreach($activities as $activity)
    <tr>
        <td></td>
        <td>{{$activity->solicitude->titulo}}</td>
        <td>
            {{$activity->solicitude->typemoney->simbolo.$activity->solicitude->monto }}
        </td>
        <td></td>
        <td>@if($activity->estado == 2)
            <span class="label label-warning">Pendiente</span>
            @endif
            @if($activity->estado == 3)
            <span class="label label-danger">Rechazado</span>
            @endif
            @if($activity->estado == 5)
            <a href=""><span class="label label-info">Depositado</span></a>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>

</table>