<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
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
                <td style="text-align: center">{{$solicitude->typemoney->simbolo.$solicitude->monto}}</td>
                @include('template.states')
                <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
                <td style="text-align: center">
                    <div style="text-align: center">
                        <a href="{{URL::to('ver-solicitud-gerprod').'/'.$solicitude->token}}">
                            <span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-eye-open"></span>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>