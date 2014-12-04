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
        <td style="text-align: center">{{$solicitude->idsolicitud}}</td>
        <td>{{$solicitude->titulo}}</td>
        <td style="text-align: center">
            {{$solicitude->typemoney->simbolo.$solicitude->monto }}
        </td>
        <td style="text-align: center">
            <span class="label" style="background-color: {{$solicitude->state->color}}">{{$solicitude->state->nombre}}</span>
            @if($solicitude->retencion != null && $solicitude->asiento == 2)
             <span class="label" style="background-color: {{$solicitude->state->color}}">A</span>
            @elseif($solicitude->retencion != null && $solicitude->estado == APROBADO)
             <span class="label" style="background-color: {{$solicitude->state->color}}">D</span>
            @endif


        </td>
        <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
        <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
        <td>
            <div class="div-icons-solicituds">
                <a href="{{URL::to('ver-solicitud-cont').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                @if($solicitude->estado == DEPOSITADO && $solicitude->asiento == 1)
                    <a id="token-solicitude" data-url="{{$solicitude->token}}"href="#"><span class="glyphicon glyphicon-book"></span></a>
                @endif
                @if($solicitude->estado == REGISTRADO)
                    <a id="token-reg-expense" data-url="{{$solicitude->token}}" data-cont="1"><span class="glyphicon glyphicon-usd"></span></a>
                    <a id="token-expense" data-url="{{$solicitude->token}}" href="#"><span class="glyphicon glyphicon-book"></span></a>
                @endif
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>