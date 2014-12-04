<div class="form-group col-xs-12 col-sm-3 col-md-3 total-fondo" style="">

                          <input id="" name="total" type="text" placeholder=""
                                 value="Total : {{$sum}}"
                                 class="form-control input-md" readonly>


</div>
<div class="table-responsive">
 <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
        <th>SiSol - Hospitall</th>
        <th>Depositar a</th>
        <th>N° Cuenta Bagó. Bco Credito</th>
        <th>Total a depositar</th>
        <th>Supervisor</th>
        <th>Edicion</th>
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
        <td>
            <div class="div-icons-solicituds">
                <a href="#" class="edit-fondo" data-idfondo="{{$fondo->idfondo}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a  href="#" class ="delete-fondo" data-idfondo="{{$fondo->idfondo}}" data-token="{{csrf_token()}}"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </td>
    </tr>
    <?php $total += $fondo->total ; $i++?>
    @endforeach
    </tbody>

</table>
</div>
