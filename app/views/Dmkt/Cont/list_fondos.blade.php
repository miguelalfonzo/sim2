<div class="table-responsive fondo_r" style="margin-top: 50px">
 <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos-contabilidad" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
        <th>SiSol - Hospitall</th>
        <th>Depositar a</th>
        <th>N° Cuenta Bagó. Bco Credito</th>
        <th>Total a depositar</th>
        <th>Supervisor</th>
        <th>Reg. Asiento</th>
    </tr>
    </thead>
    <tbody>
    <?php $i=1 ; $total = 0?>
    @foreach($fondos as  $fondo)
    <tr>
        <td>{{$i}}</td>
        <td style="text-align: center">{{$fondo->institucion}}</td>
        <td>{{$fondo->repmed}}</td>
        <td style="text-align: center">{{$fondo->cuenta }}</td>
        <td style="text-align: center">{{$fondo->total}}</td>
        <td style="text-align: center">{{$fondo->supervisor}}</td>
        <td>
            <div class="div-icons-solicituds">
                @if($fondo->registrado != FONDO_REGISTRADO)
                    @if($fondo->asiento == ASIENTO_FONDO_PENDIENTE)
                        <a href="{{url('/')}}/generar-asiento-fondo/{{$fondo->token}}"><span class="glyphicon glyphicon-book"></span></a>
                    @else
                        <span><strong>Asiento Generado</strong></span>
                    @endif
                @else
                    @if($fondo->asiento == ASIENTO_FONDO)
                        <a href="{{URL::to('generar-asiento-fondo-gasto/'.$fondo->token)}}">
                            <span class="glyphicon glyphicon-book"></span>
                        </a>
                    @else
                        <span><strong>Asiento Generado</strong></span>
                    @endif
                @endif
            </div>
        </td>
    </tr>
    <?php $total += $fondo->total ; $i++?>
    @endforeach
    </tbody>
</table>
</div>
