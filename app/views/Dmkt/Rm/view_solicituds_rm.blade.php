<table class="table table-striped table-bordered dataTable" id="table_solicitude_rm" style="width: 100%">
    <thead>
    <tr>
        <th>Solicitud</th>
        <th>Monto Solicitado</th>
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
        <td>
            <div class="div-icons-solicituds">
                <a href="{{URL::to('ver-solicitud-rm').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                @if($solicitude->estado == DEPOSITADO && $solicitude->asiento == 1)
                <a id="token-a" href="#"><span class="glyphicon glyphicon-usd"></span></a>
                <form id="form-token" action="{{URL::to('registrar-gasto')}}" method="POST">
                    <input type="hidden" name="token" value="{{$solicitude->token}}">
                </form>
                @endif
                @if($solicitude->estado == REGISTRADO)
                <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}"><span class="glyphicon glyphicon-print"></span></a>
                <a  href="{{URL::to('ver-gasto'.'/'.$solicitude->token)}}"><span class="glyphicon glyphicon-usd"></span></a>
                @endif
                @if($solicitude->estado == PENDIENTE && $solicitude->derive == 0)
                <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}"><span class="glyphicon glyphicon-remove"></span></a>
                @endif

            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>