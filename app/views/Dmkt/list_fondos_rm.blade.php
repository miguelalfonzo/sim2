<div class="table-responsive">
 <table class="table table-hover table-bordered table-condensed dataTable" id="table_fondos_rm" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
        <th>SiSol - Hospitall</th>
        <th>Depositar a</th>
        <th>N° Cuenta Bagó. Bco Credito</th>
        <th>Total a depositar</th>
        <th>Supervisor</th>
        <th>Reg. Gasto</th>
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
            {{$fondo->cuenta }}
        </td>

        <td style="text-align: center">{{$fondo->total}}</td>
        <td style="text-align: center">{{$fondo->supervisor}}</td>
        <td>
            <div class="div-icons-solicituds">
                <a href="#" class="edit-fondo" data-idfondo="{{$fondo->idfondo}}"><span class="glyphicon glyphicon-usd"></span></a>

            </div>
        </td>
    </tr>
    <?php $total += $fondo->total ; $i++?>
    @endforeach
    </tbody>

</table>
</div>
