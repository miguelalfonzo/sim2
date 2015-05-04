<table class="table table-hover table-bordered table-condensed dataTable" id="table_documents">
    <thead>
        <tr>
            <th>#</th>
            <th>RUC</th>
            <th>Razón Social</th>
            <th>N°</th>
            <th>Fecha Mov.</th>
            <th>Descripcion</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($proofs as $proof )
            <tr row-id="{{$proof->id}}">
                <td class="pk" style="text-align:center">{{$proof->id}}</td>
                <td class="ruc" style="text-align:center">{{$proof->ruc}}</td>
                <td class="razon" style="text-align:center">{{$proof->razon}}</td>
                <td class="number" style="text-align:center">{{$proof->num_prefijo.'-'.$proof->num_serie}}</td>
                <td class="fecha_movimiento" style="text-align:center">{{$proof->fecha_movimiento}}</td>
                <td class="descripcion" style="text-align:center">{{$proof->descripcion}}</td>
                <td style="text-align:center">
                    <div>
                        <a href="#" class="modal-document"><span style="padding: 0 5px; font-size: 1.3em" class="glyphicon glyphicon-pencil"></span></a>    
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
