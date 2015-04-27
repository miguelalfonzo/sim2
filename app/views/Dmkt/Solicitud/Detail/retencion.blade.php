<!-- RETENCION -->
@if( Auth::user()->type == CONT && $solicitud->idestado == APROBADO )
<div class="col-sm-12 col-md-12">
    <label class="col-sm-12 col-md-12 control-label" for="textinput">Retenciones</label>
    <div class="col-sm-3 col-md-3">
        <select name="retencion" class="form-control">
        @foreach($typeRetention as $retention)
            <option value="{{$retention->id}}">
                {{$retention->descripcion.' '.$retention->account->typeMoney->simbolo}}
            </option>
        @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3 col-md-3">
        <input name="monto_retencion" type="text" class="form-control input-md ret">
    </div>
</div> 
@endif