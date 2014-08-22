<table class="table table-striped table-bordered dataTable" id="table_solicitude_sup" style="width: 100%">
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
        <td>{{$solicitude->typemoney->simbolo.$solicitude->monto}}
        </td>
        <td>
            <span class="label" style='background-color: {{$solicitude->state->color}}'>{{$solicitude->state->nombre}}</span>

        </td>
        <td>{{$solicitude->observacion}}</td>
        <td><a href="{{URL::to('ver-solicitud-sup').'/'.$solicitude->idsolicitud}}"><span class="glyphicon glyphicon-eye-open"></span></a>
            <a href="{{URL::to('editar-solicitud-sup').'/'.$solicitude->idsolicitud}}"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="{{URL::to('eliminar-solicitud-sup')}}"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>