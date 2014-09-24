<table class="table table-striped table-bordered dataTable" id="table_solicitude_tes" style="width: 100%">
    <thead>
    <tr>
        <th>Solicitud</th>
        <th>Monto a Depositar</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Tipo de Solicitud</th>
        <th>Edicion</th>
    </tr>
    </thead>
    <tbody>
    @foreach($solicituds as $solicitude)
    <tr>
        <td>{{$solicitude->titulo}}</td>
        <td style="text-align: center">
            {{$solicitude->typemoney->simbolo.$solicitude->monto }}
        </td>
        <td style="text-align: center">
            <span class="label" style="background-color: {{$solicitude->state->color}}">{{$solicitude->state->nombre}}</span>
        </td>
        <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
        <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
        <td>
            <div class="div-icons-solicituds">
                <a href="{{URL::to('ver-solicitud-tes').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                <a href="#" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-usd"></span></a>
                <input type="hidden" id="token-hidden">
                <!-- Modal -->
                <div class="modal fade" id="myModal" data-token="{{$solicitude->token}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title" id="myModalLabel">Registro del Depósito</h4>
                            </div>
                            <div class="modal-body">
                                <label for="op-number">Número de Operación, Transacción, Cheque:</label>
                                <input id="op-number" type="text" class="form-control">
                                <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p>
                            </div>
                            <div class="modal-footer">
                                <a href="#" id="register-deposit" href="#" class="btn btn-success" style="margin-right: 1em;">Confirmar Operación</a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>