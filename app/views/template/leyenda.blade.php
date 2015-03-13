<div class="container" id="leyenda" style="display: none">
    <table style=  "border-collapse: separate;border-spacing: 5px" >
        <tbody>
            @foreach($states as $state)
                <div class="col-md-2 col-sm-4 col-xs-6">
                    <div class="form-expense">
                        <div class="" style='background-color: {{$state->color}} ; border-radius: 5px; text-align: center ;width: 120px'>
                            <span style="color: #ffffff">{{$state->nombre}}</span>
                        </div>
                        <span>{{$state->descripcion}}</span>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
</div>
