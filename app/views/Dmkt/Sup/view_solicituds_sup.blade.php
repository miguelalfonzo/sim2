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
        <td style="text-align: center">{{$solicitude->typemoney->simbolo.$solicitude->monto}}
        </td>
        <td style="text-align: center">
            <span class="label" style='background-color: {{$solicitude->state->color}}'>{{$solicitude->state->nombre}}</span>

        </td>
        <td style="text-align: center">{{isset($solicitude->observacion)? $solicitude->observacion : '---'}}</td>
        <td><div style="text-align: center">

            <a target="_blank" href="{{URL::to('ver-solicitud-sup').'/'.$solicitude->token}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span></a>
           @if($solicitude->estado == 2)
            <a target="_blank" href="{{URL::to('editar-solicitud-sup').'/'.$solicitude->token}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span></a>
            <a href="{{URL::to('eliminar-solicitud-sup')}}"><span style="padding: 0 5px; font-size: 1.3em"  class="glyphicon glyphicon-remove"></span></a>
           @endif
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>