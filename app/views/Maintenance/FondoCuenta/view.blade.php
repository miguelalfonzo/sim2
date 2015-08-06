@extends('template.main')
@section('solicitude')
<div class="page-header">
  <h3>Mantenimiento de Fondo-Cuenta</h3>
</div>
<div id="fondoCuenta">
    @include( 'Maintenance.FondoCuenta.table' )
</div>
<div>
   <input class="btn btn-primary maintenance-add" type="button" case="fondoCuenta" value="Agregar">
</div>
<script>
    $(document).on('ready', function(){
        dataTable('fondoCuenta', null, 'registros')
    })
</script>
@stop