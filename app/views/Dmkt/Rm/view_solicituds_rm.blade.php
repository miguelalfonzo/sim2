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
        <td>
            {{$solicitude->typemoney->simbolo.$solicitude->monto }}
        </td>
        <td>
            <span class="label" style="background-color: {{$solicitude->state->color}}">{{$solicitude->state->nombre}}</span>

        </td>
        <td></td>
        <td><a href="{{URL::to('ver-solicitud').'/'.$solicitude->idsolicitud}}"><span class="glyphicon glyphicon-eye-open"></span></a>
            <a href="{{URL::to('editar-solicitud').'/'.$solicitude->idsolicitud}}"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="{{URL::to('eliminar-solicitud')}}"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>