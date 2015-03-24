<div class="container" id="leyenda" style="display: none">
    @foreach($states as $state)
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="form-expense">
                <div style='background-color:{{$state->color}}; border-radius:5px; text-align:center ; width:120px'>
                    <span style="color: #ffffff">{{$state->nombre}}</span>
                </div>
                <span>{{$state->descripcion}}</span>
            </div>
        </div>
    @endforeach
</div>
