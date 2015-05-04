<div class="modal fade" id="documents_Modal" tabindex="-1" role="dialog" aria-labelledby="enable_deposit_ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="enable_deposit_ModalLabel">Edición del Documento</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="idDocumento">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-expense">
                            <label>Sub-Total</label>
                            <input id="subtotal" class="form-control" type="text" disabled>    
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-expense">
                            <label>IGV</label>
                            <input id="igv" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-expense">
                            <label>Impuesto de Servicio</label>
                            <input id="imp-serv" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-expense">
                            <label>Total</label>
                            <input id="total" class="form-control" type="text" disabled>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-expense">
                            <label>Reparo</label>
                            <input id="reparo" class="form-control" type="text" disabled>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-expense">
                            <label>Retención o Detracción</label>
                            <div>
                                <select id="regimen" class="form-control">
                                    <option value=0 selected>NO APLICA</option> 
                                    @foreach( $regimenes as $regimen )
                                        <option value="{{$regimen->id}}">{{$regimen->descripcion}}</option>                          
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="visibility:hidden">
                        <div class="form-expense">
                            <label>Monto de la Retención o Detracción</label>
                            <input id="monto-regimen" type="text" class="form-control">
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="update-document" class="btn btn-success" style="margin-right: 1em;">Actualizar</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>