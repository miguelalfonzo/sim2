@extends('template.main')
@section('solicitude')
<div class="page-header">
  <h3>Mantenimiento de Fondos</h3>
</div>
<table class="table table-hover table-bordered table-condensed dataTable" id="table_fondo" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Moneda</th>
            <th>Saldo</th>
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $fondos as $fondo )
            <tr row-id="{{$fondo->id}}" type="fondo">
                <td style="text-align:center">{{$fondo->id}}</td>
                <td class="nombre" style="text-align:center">{{$fondo->nombre}}</td>
                <td class="idusertype" editable=1 style="text-align:center">{{$fondo->userType->descripcion}}</td>
                <td class="id_moneda" editable=1 style="text-align:center">{{ $fondo->typeMoney->simbolo }}</td>
                <td class="saldo" editable=3 style="text-align:center">{{$fondo->saldo}}</td>
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <!-- <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a> -->
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
   <input class="btn btn-primary maintenance-add" type="button" case="fondo" value="Agregar">
</div>
<script>
    $(document).on('ready', function(){
        dataTable('table_fondo', null, 'registros')
    })
</script>
@stop