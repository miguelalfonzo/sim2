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
        <td><div class="div-icons-solicituds" style="text-align: center">

                <a href="{{URL::to('ver-solicitud').'/'.$solicitude->token}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span></a>
                @if($solicitude->estado == 5)
                <a href="{{URL::to('registrar-gasto').'/'.$solicitude->token}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span></a>
                @endif
                @if($solicitude->estado == 2)
                <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span></a>
                <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span></a>
                @endif

        </div>

        </td>
    </tr>
    @endforeach
    </tbody>

</table>