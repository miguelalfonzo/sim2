
@if($export == EXPORTAR)
<div class="form-group col-xs-3 col-sm-2 col-md-1 pull-right fondo_r" >
       <a id="export-fondo" class="btn btn-sm btn-primary ladda-button" href=""
                                    data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-print"></i> Exportar</a>
</div>
@endif

@if($estado != TERMINADO && $export == EXPORTAR)
<div class="form-group col-xs-3 col-sm-2 col-md-1 pull-right fondo_r" >
       <a id="terminate-fondo" class="btn btn-sm btn-danger" href=""
                                    data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-download"></i> Terminar</a>
</div>
@endif
<div class="form-group col-xs-12 col-sm-3 col-md-3 total-fondo fondo_r" style="">

                          <input id="" name="total" type="text" placeholder=""
                                 value="Total : {{$sum}}"
                                 class="form-control input-md" readonly>


</div>
<div class="table-responsive fondo_r">
 <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
        <th>SISOL - Hospital</th>
        <th>Depositar a</th>
        <th>N° Cuenta Bagó. Bco Credito</th>
        <th>Total a depositar</th>
        <th>Supervisor</th>
        @if($estado != TERMINADO)
        <th>Edicion</th>
        @endif
    </tr>
    </thead>
    <tbody>
    <?php $i=1 ; $total = 0?>
    @foreach($fondos as  $fondo)
    <tr>
        <td>{{$i}}</td>
        <td style="text-align: center">{{$fondo->institucion}}</td>
        <td>{{$fondo->repmed}}</td>
        <td style="text-align: center">
            {{$fondo->cuenta}}
        </td>

        <td style="text-align: center">{{$fondo->total}}</td>
        <td style="text-align: center">{{$fondo->supervisor}}</td>
         @if($estado != TERMINADO)
        <td>
            <div class="div-icons-solicituds">
                <a href="#" class="edit-fondo" data-idfondo="{{$fondo->idfondo}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a  href="#" class ="delete-fondo" data-idfondo="{{$fondo->idfondo}}" data-token="{{csrf_token()}}"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </td>
        @endif
    </tr>
    <?php $total += $fondo->total ; $i++?>
    @endforeach
    </tbody>

</table>
</div>
