<table class="table table-hover table-bordered table-condensed dataTable" id="table_inversionactividad" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Inversion</th>
            <th>Actividad</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $inversion_actividad as $investmentActivity )
            <tr row-id="{{$investmentActivity->id}}" type="inversionactividad">
                <td style="text-align:center">{{$investmentActivity->id}}</td>
                <td class="id_inversion" editable="1" style="text-align:center">{{$investmentActivity->investment()->withTrashed()->first()->nombre }}</td>
                <td class="id_actividad" editable="1" style="text-align:center">{{$investmentActivity->activity()->withTrashed()->first()->nombre}}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit" href="#">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>