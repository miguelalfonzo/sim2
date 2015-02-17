<div class="table-responsive fondo_r" style="margin-top: 50px">
    <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos-tesoreria" style="width: 100%">
        <thead>
        <tr>
            <th>#</th>
            <th>SiSol - Hospitall</th>
            <th>Depositar a</th>
            <th>N° Cuenta Bagó. Bco Credito</th>
            <th>Total a depositar</th>
            <th>Supervisor</th>
            <th>Reg. Depósito</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        @foreach($fondos as  $fondo)
        <tr>
            <td>{{$i}}</td>
            <td style="text-align: center">{{$fondo->institucion}}</td>
            <td>{{$fondo->repmed}}</td>
            <td style="text-align: center">{{$fondo->cuenta}}</td>
            <td style="text-align: center">{{$fondo->total}}</td>
            <td style="text-align: center">{{$fondo->supervisor}}</td>
            <td>
                <div class="div-icons-solicituds">
                    @if($fondo->depositado == 0)
                        <a href="#" class="deposit-fondo" data-idfondo="{{$fondo->idfondo}}" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-usd"></span></a>
                    @else
                        <span><strong>Depositado</strong></span>
                    @endif
                </div>
            </td>
        </tr>
        <?php $i++?>
        @endforeach
        </tbody>
    </table>
    @if($estado == PDTE_DEPOSITO)
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Registro del Depósito</h4>
                </div>
                <div class="modal-body">
                    <input id="idfondo" type="hidden" value="">
                    <label for="op-number">Número de Operación, Transacción, Cheque:</label>
                    <input id="op-number" type="text" class="form-control">
                    <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p>
                </div>
                <div class="modal-footer">
                    <a id="register-deposit-fondo" href="#" class="btn btn-success " data-deposit="fondo" style="margin-right: 1em;">Confirmar Operación</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!--
<script>

</script>
-->
