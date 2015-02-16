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
                @if($fondo->asiento == 0)
                <a class="deposit-fondo" data-idfondo="{{$fondo->idfondo}}" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-book"></span></a>
                @else
                <span><strong>Asiento</strong></span>
                @endif
            </div>
        </td>
    </tr>
    <?php $total += $fondo->total ; $i++?>
    @endforeach
    </tbody>

</table>
</div>
<!--
<script>
var data          = {};
$('#register-deposit-fondo').on('click' ,function(e){
            e.preventDefault();
            console.log('register');

            $("#op_number").val('');
            $("#message-op-number").text('');
            var op_number  = $("#op-number").val();
            var type_deposit = $(this).attr('data-deposit');
            if(type_deposit ==='fondo'){
                url = 'deposit-fondo'
                data.idfondo = $('#idfondo').val();
                data.op_number = op_number;
                data._token = $("input[name=_token]").val();
            }else if(type_deposit === 'solicitude'){
                url = 'deposit-solicitude';
                data.op_number = op_number;
                data.token     = $("#token").val();
                data._token    = $("input[name=_token]").val();
            }
            $("#op_number").val('');
            $("#message-op-number").text('');


            if(!op_number)
            {
                $("#message-op-number").text("Ingrese el número de Operación");
            }
            else
            {
                $.post(server + url, data)
                .done(function (data){
                    if(parseInt(data,10) === 1)
                    {
                        $('#myModal').modal('hide');
                        bootbox.alert("<p class='green'>Se registro el asiento contable correctamente.</p>", function(){
                            if(type_deposit === 'fondo'){
                                $.ajax({
                                    url: server + 'list-fondos-tesoreria/'+ dateactual,
                                    type: 'GET',
                                    dataType: 'html'

                                }).done(function (data) {
                                    $('#table_solicitude_fondos-tesoreria_wrapper').remove();
                                    $('.table_solicitude_fondos-tesoreria').append(data);
                                    $('#table_solicitude_fondos-tesoreria').dataTable({
                                            "order": [
                                                [ 3, "desc" ] //order date
                                            ],
                                            "bLengthChange": false,
                                            'iDisplayLength': 7,
                                            "oLanguage": {
                                                "sSearch": "Buscar: ",
                                                "sZeroRecords": "No hay fondos",
                                                "sInfoEmpty": "No hay fondos",
                                                "sInfo": 'Mostrando _END_ de _TOTAL_',
                                                "oPaginate": {
                                                    "sPrevious": "Anterior",
                                                    "sNext" : "Siguiente"
                                                }
                                            }
                                        }
                                    );
                                });
                            }else{
                                window.location.href = server+'show_tes';
                            }

                        });
                    }
                    else
                    {
                        $("#message-op-number").text("No se ha podido registrar el depósito.");
                    }
                });
            }
})
</script>

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
  </div>
-->