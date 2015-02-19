<table class="table table-striped table-bordered dataTable" id="table_solicitude_rm" style="width: 100%">
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

        </td>
        <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
        <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
        <td>
            <div class="div-icons-solicituds">
                <a href="{{URL::to('ver-solicitud-rm').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                @if($solicitude->estado == DEPOSITADO && $solicitude->asiento == 2 && Auth::user()->id == $solicitude->idresponse)
                    <a id="token-reg-expense" data-url="{{$solicitude->token}}"><span class="glyphicon glyphicon-usd"></span></a>
                @endif
                @if($solicitude->estado == REGISTRADO && Auth::user()->id == $solicitude->idresponse)
                    <a target="_blank" href="{{URL::to('a'.'/'.$solicitude->token)}}"><span class="glyphicon glyphicon-print"></span></a>
                    <a  href="{{URL::to('ver-gasto'.'/'.$solicitude->token)}}"><span class="glyphicon glyphicon-usd"></span></a>
                @endif
                @if($solicitude->estado == PENDIENTE && $solicitude->derive == 0)
                   @if(!$solicitude->blocked == 1)
                        <a href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$solicitude->idsolicitud}}" data-token="{{csrf_token()}}" disabled><span class="glyphicon glyphicon-remove"></span></a>
                   @endif
                @endif

            </div>
        </td>
    </tr>
    @endforeach
    @if (isset($rSolicitudes))
        @foreach($rSolicitudes as $rSolicitude)
        <tr>
            <td style="text-align: center">{{$rSolicitude->idsolicitud}}</td>
            <td>{{$rSolicitude->titulo}}</td>
            <td style="text-align: center">
                {{$rSolicitude->typemoney->simbolo.$rSolicitude->monto }}
            </td>
            <td style="text-align: center">
                <span class="label" style="background-color: {{$rSolicitude->state->color}}">{{$rSolicitude->state->nombre}}</span>

            </td>
            <td style="text-align: center">{{ date_format(date_create($rSolicitude->created_at), 'd/m/Y' )}}</td>
            <td style="text-align: center">{{$rSolicitude->typesolicitude->nombre}}</td>
            <td>
                <div class="div-icons-solicituds">
                    <a href="{{URL::to('ver-solicitud-rm').'/'.$rSolicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                    @if($rSolicitude->estado == DEPOSITADO && $rSolicitude->asiento == 2 && Auth::user()->id == $rSolicitude->idresponse)
                        <a id="token-reg-expense" data-url="{{$rSolicitude->token}}"><span class="glyphicon glyphicon-usd"></span></a>
                    @endif
                    @if($rSolicitude->estado == REGISTRADO && Auth::user()->id == $rSolicitude->idresponse)
                        <a target="_blank" href="{{URL::to('a'.'/'.$rSolicitude->token)}}"><span class="glyphicon glyphicon-print"></span></a>
                        <a  href="{{URL::to('ver-gasto'.'/'.$rSolicitude->token)}}"><span class="glyphicon glyphicon-usd"></span></a>
                    @endif
                    @if($rSolicitude->estado == PENDIENTE && $rSolicitude->derive == 0)
                       @if(!$rSolicitude->blocked == 1)
                            <a href="{{URL::to('editar-solicitud').'/'.$rSolicitude->token}}"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="#" class="cancel-solicitude" data-idsolicitude = "{{$rSolicitude->idsolicitud}}" data-token="{{csrf_token()}}" disabled><span class="glyphicon glyphicon-remove"></span></a>
                       @endif
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    @endif
    </tbody>

</table>