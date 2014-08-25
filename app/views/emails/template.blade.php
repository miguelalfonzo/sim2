<h1>Nueva Solicitud</h1>
<h3>{{$name}}</h3>
<h3>@if($money == 1)
    {{'S/.'.$monto}}
    @else
    {{'$'. $monto}}
    @endif
</h3>
<h3>{{$description}}</h3>
