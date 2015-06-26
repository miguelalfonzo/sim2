<table class="table table-hover table-bordered table-condensed dataTable" id="table_inversion" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $inversion as $investment )
            <tr row-id="{{$investment->id}}" type="inversion">
                <td style="text-align:center">{{$investment->id}}</td>
                <td class="nombre" editable="4" style="text-align:center">{{$investment->nombre}}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit" href="#">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>