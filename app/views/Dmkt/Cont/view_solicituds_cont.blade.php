<table class="table table-striped table-bordered dataTable" id="table_solicitude_cont" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
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
        <td style="text-align: center">{{$solcitude->idsolicitud}}</td>
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
                <a href="{{URL::to('ver-solicitud-cont').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                @if($solicitude->estado == DEPOSITADO && $solicitude->asiento == 1)
                    <a id="token-a" href="#"><span class="glyphicon glyphicon-book"></span></a>
                    <form id="form-token" action="{{URL::to('generar-asiento-solicitud')}}" method="POST">
                        <input type="hidden" name="token" value="{{$solicitude->token}}">
                    </form>
                @endif
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>