<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
    <thead>
    <tr>
        <th>Solicitud</th>
        <th>Presupuesto</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Tipo de Solicitud</th>
        <th>Edicion</th>
    </tr>
    </thead>
    <tbody>
    @foreach($solicituds as $solicitude)
    <tr>
        <td>{{$solicitude->titulo}}</td>
        <td style="text-align: center">
            {{$solicitude->typemoney->simbolo.$solicitude->monto }}
        </td>
        <td style="text-align: center">
            <span class="label" style="background-color: {{$solicitude->state->color}}">{{$solicitude->state->nombre}}</span>

        </td>
        <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
        <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
        <td><div class="div-icons-solicituds" style="text-align: center">

                <a href="{{URL::to('ver-solicitud').'/'.$solicitude->token}}"><span style=" font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span></a>
                @if($solicitude->estado == DEPOSITADO)
                <a  id="token-a" href="#"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-usd"></span></a>
                <form id="form-token" action="{{URL::to('registrar-gasto')}}" method="POST">
                    <input type="hidden" name="token" value="{{$solicitude->token}}">
                </form>
                @endif
                @if($solicitude->estado == PENDIENTE)
                <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}"><span style="font-size: 1.3em; padding: 0 6px" class="glyphicon glyphicon-pencil"></span></a>
                <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-remove"></span></a>
                @endif

        </div>

        </td>
    </tr>
    @endforeach
    </tbody>

</table>