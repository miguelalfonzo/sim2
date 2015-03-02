<div class="table-responsive fondo_d" style="margin-top: 5px">
    <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos-contabilidad" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Documento</th>
                <th>Cuenta SUNAT</th>
                <th>Tipo</th>
                <th>IGV</th>
                <th>Edicion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($docs as $doc)
                <tr>
                    <td id="pk" class="col-md-1" style="text-align: center">{{$doc->idcomprobante}}</td>
                    <td id="desc" class="col-md-3" style="text-align: center">{{$doc->descripcion}}</td>
                    <td id="sunat" class="col-md-3" style="text-align: center">{{$doc->cta_sunat}}</td>
                    <td id="marca" class="col-md-2" style="text-align: center">{{$doc->marca}}</td>
                    @if ( $doc->idcomprobante == 1 )
                        <td id="igv" class="col-md-1" style="text-align: center">Si</td>
                    @else
                        <td id="igv" class="col-md-1" style="text-align: center">No</td>
                    @endif
                    <td id="icons" class="col-md-1" style="text-align: center">
                        <a class="elementEdit" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <!-- <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a> -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <input class="btn btn-primary" id="add-doc" type="button" value="Agregar otro Documento">
    </div>
</div>