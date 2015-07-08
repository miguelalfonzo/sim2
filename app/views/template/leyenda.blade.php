<dl class="dl-horizontal">
    @foreach($states as $state)
        <dt><span class="label" style="background-color:{{$state->color}};">{{$state->nombre}}</span></dt>
        <dd>{{$state->descripcion}}</dd>
    @endforeach
</dl>