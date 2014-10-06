@extends('template.main')
@section('solicitude')

<div class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><strong>Registro de Usuarios</strong></h3><strong class="user">Admin</strong>
        </div>
        <div class="panel-body">
            <div class="col-md-12">

                    <div class="col-md-2">
                        <ul class="nav nav-pills nav-stacked admin-menu">
                            <li class="active"><a href="#" data-target-id="home"><i class="fa fa-home fa-fw"></i>Registrar</a></li>
                            <li><a href="" data-target-id="widgets"><i class="fa fa-list-alt fa-fw"></i>Listar</a></li>
                            <li><a href="" data-target-id="pages"><i class="fa fa-file-o fa-fw"></i>Pages</a></li>

                        </ul>
                    </div>
                    <div class="col-md-10  admin-content" id="home">
                        <form class="form-horizontal" id="form-register-user" method="post">
                            <fieldset>

                                <!-- Form Name -->

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Nombres</label>

                                    <div class="col-md-4">
                                        <input id="textinput" name="first_name" type="text" placeholder=""
                                               class="form-control input-md">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Apellidos</label>

                                    <div class="col-md-4">
                                        <input id="textinput" name="last_name" type="text" placeholder=""
                                               class="form-control input-md">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Usuario</label>

                                    <div class="col-md-4">
                                        <input id="textinput" name="username" type="text" placeholder=""
                                               class="form-control input-md">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Email</label>

                                    <div class="col-md-4">
                                        <input id="textinput" name="email" type="text" placeholder=""
                                               class="form-control input-md">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <!-- Password input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="passwordinput">Password</label>

                                    <div class="col-md-4">
                                        <input id="passwordinput" name="password" type="password" placeholder=""
                                               class="form-control input-md">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="selectbasic">Tipo de Usuario</label>

                                    <div class="col-md-4">
                                        <select id="selectbasic" name="type" class="form-control">
                                            @foreach($types as $type)
                                            <option value="{{$type->codigo}}">{{$type->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="singlebutton"></label>

                                    <div class="col-md-4">
                                        <a id="register_user" name="singlebutton" class="btn btn-primary">Registrar</a>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                    <div class="col-md-10  admin-content" id="widgets">
                        <table class="table table-striped table-bordered dataTable" id="table-users" style="width: 100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Edicion</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td style="text-align: center">{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td style="text-align: center">
                                {{$user->type}}
                                </td>
                                @if($user->type == 'R')
                                <td style="text-align: center">{{$user->rm->nombres}}</td>
                                <td style="text-align: center"></td>
                                @elseif($user->type == 'S')
                                <td style="text-align: center">{{$user->Sup->nombres}}</td>
                                <td style="text-align: center"></td>
                                @elseif($user->type == 'P')
                                <td style="text-align: center">{{$user->gerprod->descripcion}}</td>
                                <td style="text-align: center"></td>
                                @else
                                <td style="text-align: center">{{$user->person->nombres}}</td>
                                <td style="text-align: center">{{$user->person->apellidos}}</td>
                                @endif

                                <td style="text-align: center"></td>

                            </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="col-md-10 well admin-content" id="pages">
                        Pages
                    </div>


            </div>

        </div>
    </div>
</div>
@stop