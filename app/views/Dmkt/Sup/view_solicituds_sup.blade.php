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
        <td><div style="text-align: center">
            <a href="{{URL::to('ver-solicitud-sup').'/'.$solicitude->idsolicitud}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span></a>
            <a href="{{URL::to('editar-solicitud-sup').'/'.$solicitude->idsolicitud}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span></a>
            <a href="{{URL::to('eliminar-solicitud-sup')}}"><span style="padding: 0 5px; font-size: 1.3em"  class="glyphicon glyphicon-remove"></span></a>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>