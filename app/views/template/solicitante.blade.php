@if($solicitude->createdBy->type == REP_MED)
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group date">
                <span class="input-group-addon">Representante</span>
                <input id="textinput" name="titulo" type="text" value="{{$solicitude->createdBy->rm->nombres.' '.$solicitude->createdBy->rm->apellidos}}" disabled
                   class="form-control input-md">
            </div>
        </div>
    </div>
@elseif($solicitude->createdBy->type == SUP)
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group date">
                <span class="input-group-addon">Supervisor</span>
                <input id="textinput" name="titulo" type="text" value="{{$solicitude->createdBy->Sup->nombres.' '.$solicitude->createdBy->Sup->apellidos}}" disabled
                   class="form-control input-md">
            </div>
        </div>
    </div>
@else
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group date">
                <span class="input-group-addon">Rol No Definido</span>
                <input id="textinput" name="titulo" type="text" value="{{$solicitude->createdBy->email}}" disabled
                   class="form-control input-md">
            </div>
        </div>
    </div>
@endif