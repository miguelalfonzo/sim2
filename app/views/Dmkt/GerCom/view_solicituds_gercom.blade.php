<table class="table table-striped table-bordered dataTable" id="table_solicitude_gercom" style="width: 100%">
    <thead>
    <tr>
        <th>Solicitude</th>
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

                <a href="{{URL::to('ver-solicitud-gercom').'/'.$solicitude->token}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span></a>

            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>