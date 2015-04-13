<div class="modal fade" id="enable_deposit_Modal" tabindex="-1" role="dialog" aria-labelledby="enable_deposit_ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="enable_deposit_ModalLabel">Registro del Depósito</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-expense">
                            <label>Solicitud</label>
                            <div class="input-group">
                                <div id="id-solicitude" class="input-group-addon" value=""></div>
                                <input id="sol-titulo" class="form-control" type="text" disabled>
                                <input name="token" type="hidden">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-expense">
                            <label>Beneficiario</label>
                            <input id="beneficiario" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-expense">
                            <label>Monto Solicitado</label>
                            <input id="tes-mon-sol" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-expense">
                            <label>Retencion</label>
                            <input id="tes-mon-ret" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-expense">
                            <label>Monto a Depositar</label>
                            <input id="total-deposit" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-expense">
                            <label>Bancos</label>
                            <div>
                                <select id="bank_account" name="bank_account" class="form-control">
                                    @foreach ( $banks as $bank )
                                        <option value="{{$bank->id}}">
                                            {{$bank->typeMoney->simbolo.'-'.$bank->nombre}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-expense">
                            <label for="op-number">Número de Operación, Transacción, Cheque</label>
                            <input id="op-number" type="text" class="form-control">
                            <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="" href="#" class="btn btn-success register-deposit" style="margin-right: 1em;">Confirmar Operación</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>