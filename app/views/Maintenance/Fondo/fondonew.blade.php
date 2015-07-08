@extends('template.main')
@section('solicitude')
<div class="page-header">
  <h3>Mantenimiento de Fondos</h3>
</div>
<div class="container-fluid">
<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="tb_fondos_new" class="table table-striped table-hover table-bordered" >
    <thead>
        @if(isset($fondosCategorias))
        <tr>
            <th></th>
        @foreach($fondosCategorias as $categoria)
            <th colspan="{{ count($categoria->fondos_sub_categorias)}}">{{ $categoria->descripcion }}</th>
     
        @endforeach
        </tr>
        <tr>
            <th></th>
            @foreach($fondosCategorias as $categoria)
                @foreach($categoria->fondos_sub_categorias as $subcategoria)
                    <th>{{ $subcategoria->descripcion }}</th>
                @endforeach
            @endforeach
        </tr>
        @endif
    </thead>
    <tbody>
        {{ ''; $lastProduct = null }}
        @foreach($fondos as $fondo)
            @if($lastProduct != $fondo->marca_id )
            @if($lastProduct != null)</tr>@endif
            <tr>
                <td>{{ $fondo->marca }}</td>
            @endif
        @foreach($fondosCategorias as $categoria)
            @foreach($categoria->fondos_sub_categorias as $subcategoria)
                
                    @if($fondo->subcategoria_id == $subcategoria->id)
                    <td data-product="{{ $fondo->marca_id }}" data-subcategoria="{{ $fondo->subcategoria_id }}" data-categoria="{{ $categoria->id }}">{{ $fondo->saldo }}</td>
                    @endif
            @endforeach
        @endforeach
        
        {{''; $lastProduct = $fondo->marca_id}}
        @endforeach    
            
    </tbody>
</table>
</div>
<script>
$(document).on('ready', function(){
    $("#tb_fondos_new").DataTable(
    {
        // processing     : true,
        // serverside     : true,
        // ajax           : server + 'dt' ,
        // dom: '<f<t>ip<r>>',
        dom: "<'row'<'col-xs-6'><'col-xs-6 pull-right'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        stateSave      : true,
        autoWidth      : true,
        scrollY        : calcDataTableHeight(),
        sScrollX       : "100%",
        bScrollCollapse: true,
        bPaginate    : false,
        // iDisplayLength : -1 ,
        language       :
        {
            search      : 'Buscar',
            zeroRecords : 'No hay resultado',
            infoEmpty   : 'No ha encontrado registros disponibles',
            info        : 'Mostrando _END_ de _TOTAL_' ,
            lengthMenu  : "Mostrando _MENU_ registros por página",
            infoEmpty   : "No ha encontrado informacion disponible",
            infoFiltered: "(filtrado de _MAX_ regitros en total)",
            paginate    : 
            {
                sPrevious : 'Anterior',
                sNext     : 'Siguiente'
            }
        },
        /*columns : [
        { class          : "details-control"} ,
        null , null , null]*/
    });
    $("#fm-grid-view").css('height','100vh');
    $('html, body').animate({scrollTop: $('#tb_fondos_new').offset().top -10 }, 'slow');
});
</script>
@stop