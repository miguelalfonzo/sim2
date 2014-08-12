@extends('template.main')
@section('solicitude')
<div class="col-md-11" style="margin-bottom: 10px; margin-top: 10px">
<div class="form-inline">


        <!-- Select Basic -->
        <div class="form-group">

            <div class="col-md-4">
                <select id="selectbasic" name="selectbasic" class="form-control">
                    <option value="1">Pendiente</option>
                    <option value="2">Aceptado</option>
                    <option value="2">Rechazado</option>
                    <option value="2">Cancelado</option>
                    <option value="2">Entregado</option>

                </select>
            </div>
        </div>

        <div class="form-group">

            <div class="col-md-4">
                <a href="{{URL::to('nueva-solicitud')}}" id="singlebutton" name="singlebutton" class="btn btn-primary">Nueva Solicitud</a>
            </div>

        </div>

</div>

</div>




<div class="col-md-12" style="">
    <!-- Default panel contents -->
    <div class="panel panel-default">
    <div class="panel-heading"> <h3 class="panel-title">Solicitudes</h3></div>

    <div class="panel-body">
    <!-- Table -->
    <table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Presupuesto</th>
            <th>Estado</th>
            <th>Observaciones</th>
            <th>Edicion</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Torta de cumpleaños para el  Dr. Mejia</td>
            <td>50</td>
            <td><span class="label label-warning">Pendiente</span></td>
            <td></td>
            <td><a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                <a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-remove"></span></a>

            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Congreso para Clinica de Stan</td>
            <td>1000</td>
            <td><span class="label label-success">Aceptado</span></td>
            <td></td>
            <td><a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                <a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Frazadas para verano</td>
            <td>50</td>
            <td><span class="label label-danger">Rechazado</span></td>
            <td>No se nesecitan en verano</td>
            <td><a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                <a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="{{URL::to('ver-solicitud')}}"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
        </tr>
        </tbody>
    </table>
    </div>
    </div>
</div>
<script type="application/javascript" class="init">
    $('#table_solicitude').dataTable();

</script>
@stop
@section('actividad')
<div class="col-md-12" style="margin-bottom: 10px; margin-top: 10px">
<form class="form-inline">

        <!-- Select Basic -->
        <div class="form-group">
            <!--  <label class="col-md-6 control-label" for="selectbasic">Select Basic</label>-->
             <div class="col-md-4">
                 <select id="selectbasic" name="selectbasic" class="form-control">
                     <option value="1">Option one</option>
                     <option value="2">Option two</option>
                 </select>
             </div>
         </div>
 </form>
 </div>
 <div class="col-md-12">
     <!-- Default panel contents -->
    <div class="panel panel-default">

    <div class="panel-heading">Actividades</div>

    <div class="panel-body">
    <table class="table table-striped table-bordered dataTable" id="table_activity" style="">
        <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Mark</td>
            <td>Otto</td>
            <td>mdo</td>
            <td><a href="registrar-gasto">reg_gasto</a></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>fat</td>
            <td><a href="registrar-gasto">reg_gasto</a></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Larry</td>
            <td>the Bird</td>
            <td>twitter</td>
            <td><a href="registrar-gasto">reg_gasto</a></td>
        </tr>
        </tbody>
    </table>
        </div>
</div></div>
<script type="application/javascript" class="init">

    $('#table_activity').dataTable( );
</script>
@stop
