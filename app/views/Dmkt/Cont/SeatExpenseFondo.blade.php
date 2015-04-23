@extends('template.main')
@section('solicitude')

<style type="text/css">
.table-responsive
{
    overflow-x: auto;
}
</style>

<div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><strong>Generar Asiento de Gasto - Fondo Institucional</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
            </div>
            <input value="{{csrf_token()}}" name="_token" id="_token" type="hidden">
            <div class="panel-body">
                <section class="row reg-expense" style="margin:0">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-expense">
                            <label>Código del Fondo</label>
                            <input id="idfondo" type="text" class="form-control" value="{{$solicitude->id}}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-expense">
                            <label>Mes Depositado</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <input type="text" value="{{$solicitude->created_at}}" class="form-control" maxlength="10" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-expense">
                            <label>Sisol - Hospital</label>
                            <input type="text" class="form-control" value="{{$solicitude->institucion}}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-expense">
                            <label>Represenante Médico</label>
                            <input type="text" class="form-control" value="{{mb_convert_case($solicitude->created_by, MB_CASE_TITLE, 'UTF-8')}}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-expense">
                            <label>Monto Depositado</label>
                            <div class="input-group">
                                <span class="input-group-addon">S/.</span>
                                <input type="text" class="form-control" value="{{json_decode($solicitude->detalle->detalle)->monto_aprobado}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-expense">
                            <label>Supervisor</label>
                            <input type="text" class="form-control" value="{{$solicitude->asignedTo->id}}" disabled>
                        </div>
                    </div>
                </section>
                </section>
                <hr>
                <div>
                    <ul id="myTab" class="nav nav-tabs nav-justified">
                      <li role="presentation" class="active"><a href="#tab_seats">Asientos</a></li>
                      <li role="presentation"><a href="#tab_documents">Documentos</a></li>
                    </ul>
                </div>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_seats" aria-labelledby="tab_seats-tab">
                        <section class="row seats" style="margin:0">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-expense">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-condensed tb_style">
                                            <thead>
                                                <tr>
                                                    <th>N° Cuenta</th>
                                                    <th>CC</th>
                                                    <th>N° Origen</th>
                                                    <th>Fec. Origen</th>
                                                    <th>IVA</th>
                                                    <th>Cod.  Prov.</th>
                                                    <th>Nombre Del proveedor</th>
                                                    <th>Cod.</th>
                                                    <th>RUC</th>
                                                    <th>Prefijo</th>
                                                    <th>Cbte. Proveedor</th>
                                                    <th>D/C</th>
                                                    <th>Importe</th>
                                                    <th>Leyenda Fj</th>
                                                    <th>Leyenda Variable</th>
                                                    <th>Tipo  Resp.</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($seats as $seatItem)
                                                <tr data-id="{{ $seatItem->tempId }}" class="{{ $seatItem->type == 'IGV' ? 'info' : ($seatItem->type == 'SER' ? 'warning' : ($seatItem->type == 'REP' ? 'danger' : ($seatItem->type == 'CAN' ? 'success' : ''))) }}">
                                                    <td class="cuenta editable" data-cuenta_mkt="{{ $seatItem->cuentaMkt }}">{{ $seatItem->numero_cuenta }}</td>
                                                    <td class="codigo_sunat">{{ $seatItem->codigo_sunat }}</td>
                                                    <td class="numero_origen"></td>
                                                    <td class="fecha_origen">{{ $seatItem->fec_origen }}</td>
                                                    <td class="iva">{{ $seatItem->iva }}</td>
                                                    <td class="cod_prov">{{ $seatItem->cod_prov }}</td>
                                                    <td class="nombre_proveedor">{{ $seatItem->nombre_proveedor }}</td>
                                                    <td class="cod">{{ $seatItem->cod }}</td>
                                                    <td class="ruc">{{ $seatItem->ruc }}</td>
                                                    <td class="prefijo">{{ $seatItem->prefijo }}</td>
                                                    <td class="cbte_proveedor">{{ $seatItem->cbte_proveedor }}</td>
                                                    <td class="dc">{{ $seatItem->dc }}</td>
                                                    <td class="importe">{{ $seatItem->importe }}</td>
                                                    <td class="leyenda">{{ $seatItem->leyenda }}</td>
                                                    <td class="leyenda_variable">{{ $seatItem->leyenda_variable }}</td>
                                                    <td class="tipo_responsable">{{ $seatItem->tipo_responsable }}</td>

                                                    <td><a class="edit-seat" href="#" {{ $seatItem->type != '' ? 'style="display:none;"' : ''  }}><span class="glyphicon glyphicon-pencil"></span></a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_documents" aria-labelledby="tab_documents-tab">
                        <section class="row expense_items" style="margin:0">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-expense">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-condensed tb_style">
                                            <thead>
                                                <tr>
                                                    <th>Comprobante</th>
                                                    <th>N° Documento</th>
                                                    <th>RUC</th>
                                                    <th>Razon Social</th>
                                                    <th>Descripcion</th>
                                                    <th>Fecha</th>
                                                    <th>Sub Total</th>
                                                    <th>IGV</th>
                                                    <th>Imp Serv</th>
                                                    <th>Total</th>
                                                    <th>Cantidad</th>
                                                    <th>Descripcion</th>
                                                    <th>Importe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($expenseItem as $expenseValue)
                                                <tr data-id="{{ $expenseValue->idgasto }}">
                                                    @foreach($typeProof as $val)
                                                        @if($expenseValue->idcomprobante == $val->idcomprobante)
                                                    <td rowspan="{{ $expenseValue->count }}" data-id="{{ $val->idcomprobante }}">{{ $val->descripcion }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->num_prefijo }}-{{ $expenseValue->num_serie }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->ruc }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->razon }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->descripcion }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ date("Y/m/d",strtotime($expenseValue->fecha_movimiento)) }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->sub_tot }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->igv }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->imp_serv }}</td>
                                                    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->monto }}</td>
                                                    @foreach($expenseValue->itemList as $expenseItem)
                                                        <td>{{ $expenseItem->cantidad }}</td>
                                                        <td>{{ $expenseItem->descripcion }}</td>
                                                        <td>{{ $expenseItem->importe }}</td>
                                                        </tr><tr>
                                                    @endforeach
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                
                <section class="row reg-expense align-center" style="margin:1.5em 0">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <a id="saveSeatExpense" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento Solicitud</a>
                        <a id="cancel-seat-cont" href="#" class="btn btn-danger" style="margin:-2em 2em .5em 0">Atras</a>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            GBDMKT = typeof(GBDMKT) === 'undefined' ? {} : GBDMKT;
            GBDMKT.seatsList = {{ json_encode($seats) }};
        @if(isset($error))
            @foreach($error as $errorItem)
                bootbox.alert("{{ $errorItem['error'] }}: {{ $errorItem['msg'] }}<br> ");
            @endforeach
        @endif
            $('#myTab a').click(function (e) {
              e.preventDefault()
              $(this).tab('show')
            })
        });
        
    </script>
@stop