<div class="table-responsive">
    <table class="table table-hover table-bordered table-condensed dataTable" id="table_fondos_rm" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Mes Depositado</th>
                <th>SiSol - Hospital</th>
                <th>Monto Depositado</th>
                <th>N° Cuenta Bagó. Bco Credito</th>
                <th>Supervisor</th>
                <th>Reg. Gasto</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        @foreach($fondos as  $fondo)
        <tr>
            <td>{{$i}}</td>
            <td class="text-center">{{$fondo->monthYear($fondo->periodo)}}</td>
            <td class="text-center">{{$fondo->institucion}}</td>
            <td class="text-center">{{$fondo->total}}</td>
            <td class="text-center">{{$fondo->cuenta }}</td>
            <td class="text-center">{{$fondo->supervisor}}</td>
            <td>
                <div class="div-icons-solicituds">
                    @if($fondo->registrado == 1)
                       <a target="_blank"  href="{{URL::to('ver-gasto-fondo')}}/{{$fondo->token}}" ><span class="glyphicon glyphicon-eye-open"></span></a>
                       <a target="_blank"  href="{{URL::to('report-fondo')}}/{{$fondo->token}}"><span class="glyphicon glyphicon-print"></span></a>
                    @elseif($fondo->asiento == ASIENTO_FONDO)
                       <a href="{{URL::to('registrar-gasto-fondo')}}/{{$fondo->token}}" class="" data-idfondo="{{$fondo->idfondo}}"><span class="glyphicon glyphicon-usd"></span></a>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++?>
        @endforeach
        </tbody>
    </table>
</div>