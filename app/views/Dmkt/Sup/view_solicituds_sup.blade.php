<table class="table table-striped table-bordered dataTable" id="table_solicitude_sup" style="width: 100%">
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
        <td style="text-align: center">{{$solicitude->typemoney->simbolo.$solicitude->monto}}
        </td>
        <td style="text-align: center">
            <span class="label element" data-toggle="tooltip" data-placement="left" title="{{$solicitude->state->descripcion}}" style='background-color: {{$solicitude->state->color}}'>
            {{$solicitude->state->nombre}}
            </span>
           @if($solicitude->state->idestado == PENDIENTE && $solicitude->derived == 1)
            <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->color}}'>D</span>
           @endif
        </td>
        <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
        <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
        <td>
            <div class="div-icons-solicituds">
            <a  href="{{URL::to('ver-solicitud-sup').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
            @if($solicitude->estado == PENDIENTE && $solicitude->user->type == 'S')
                <a  href="{{URL::to('editar-solicitud').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="#" class="cancel-solicitude-sup" data-idsolicitude = "{{$solicitude->idsolicitud}}"><span class="glyphicon glyphicon-remove"></span></a>
            @endif
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>

</table>
<script>
    $('.element').tooltip()
</script>