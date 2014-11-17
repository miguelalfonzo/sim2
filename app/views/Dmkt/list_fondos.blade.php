 <table class="table table-striped table-bordered dataTable" id="table_solicitude_fondos" style="width: 100%">
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
    <?php $i=1?>
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
                <a href="#" class="edit-fondo" data-idfondo="{{$fondo->idfondo}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a  href="#" class ="delete-fondo" data-idfondo="{{$fondo->idfondo}}"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
        </td>
    </tr>
    <?php $i++?>
    @endforeach
    </tbody>

</table>