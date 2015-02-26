<div class="table-responsive fondo_d" style="margin-top: 5px">
 <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos-contabilidad" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Documento</th>
        <th>Cuenta SUNAT</th>
        <th>Tipo</th>
        <th>IGV</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($docs as $doc)
    <tr>
        <td style="text-align: center">{{$doc->idcomprobante}}</td>
        <td style="text-align: center">{{$doc->descripcion}}</td>
        <td style="text-align: center">{{$doc->cta_sunat}}</td>
        <td style="text-align: center">{{$doc->marca}}</td>
        @if ( $doc->idcomprobante == 1 )
            <td style="text-align: center">Si</td>
        @else
            <td style="text-align: center">No</td>
        @endif
        <td style="text-align: center">{{$doc->estado}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
</div>
