<table class="table table-hover table-bordered table-condensed dataTable" id="table_fondoCuenta" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Fondo</th>
            <th>Cuenta</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $fondos as $fondo )
            <tr row-id="{{$fondo->id}}" type="fondoCuenta">
                <td style="text-align:center">{{$fondo->id}}</td>
                <td class="nombre" style="text-align:center">{{$fondo->nombre}}</td>
                <td class="num_cuenta" editable=3 style="text-align:center">{{$fondo->num_cuenta}}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>