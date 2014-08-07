@extends('template.main')
@section('solicitude')
<div class="col-md-12" style="margin-bottom: 10px; margin-top: 10px">
<form class="form-inline">


        <!-- Select Basic -->
        <div class="form-group">

            <div class="col-md-4">
                <select id="selectbasic" name="selectbasic" class="form-control">
                    <option value="1">Option one</option>
                    <option value="2">Option two</option>
                </select>
            </div>
        </div>

        <div class="form-group">

            <div class="col-md-4">
                <button id="singlebutton" name="singlebutton" class="btn btn-primary">Nueva Solicitud</button>
            </div>

        </div>

</form>

</div>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Solicitudes</div>

    <!-- Table -->
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Mark</td>
            <td>Otto</td>
            <td>mdo</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>fat</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Larry</td>
            <td>the Bird</td>
            <td>twitter</td>
        </tr>
        </tbody>
    </table>
</div>
@stop
@section('actividad')
<form class="form-horizontal">

        <!-- Select Basic -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="selectbasic">Select Basic</label>
            <div class="col-md-4">
                <select id="selectbasic" name="selectbasic" class="form-control">
                    <option value="1">Option one</option>
                    <option value="2">Option two</option>
                </select>
            </div>
        </div>
</form>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Actividades</div>

    <!-- Table -->
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Mark</td>
            <td>Otto</td>
            <td>mdo</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>fat</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Larry</td>
            <td>the Bird</td>
            <td>twitter</td>
        </tr>
        </tbody>
    </table>
</div>

@stop