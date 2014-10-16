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
                            <li class="active"><a href="{{isset($user) ? 'register' : '#'}}" data-target-id="home"><i class="fa fa-home fa-fw"></i>Registrar</a></li>
                            <li><a href="" data-target-id="widgets"><i class="fa fa-list-alt fa-fw"></i>Listar</a></li>

                        </ul>
                    </div>
                    <div class="col-md-10  admin-content" id="home">
                        <form class="form-horizontal" id="form-register-user" method="post">
                            <fieldset>

                                <!-- Form Name -->
                                <input id="idUser" name="iduser" value="{{ isset($user) ? $user->id : null }}" type="hidden">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Nombres</label>

                                    <div id="first_name" class="col-md-4">
                                        @if(isset($user))
                                            @if($user->type == 'R')
                                            <input id="textinput" name="first_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->rm->nombres}}">
                                            @elseif($user->type == 'S')
                                            <input id="textinput" name="first_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->sup->nombres}}">

                                            @elseif($user->type == 'P')
                                            <input id="textinput" name="first_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->gerprod->descripcion}}">
                                            @else
                                            <input id="textinput" name="first_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->person->nombres}}">
                                            @endif
                                        @else
                                        <input id="textinput" name="first_name" type="text" placeholder=""
                                               class="form-control input-md">
                                        @endif
                                        <span class="help-block error-incomplete"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label  class="col-md-4 control-label" for="textinput">Apellidos</label>
                                    <div id="last_name" class="col-md-4">
                                        @if(isset($user))
                                            @if($user->type == 'R')
                                            <input id="textinput" name="last_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->rm->apellidos}}">
                                            @elseif($user->type == 'S')
                                            <input id="textinput" name="last_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->sup->apellidos}}">

                                            @elseif($user->type == 'P')
                                            <input id="textinput" name="last_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->gerprod->descripcion}}">
                                            @else
                                            <input id="textinput" name="last_name" type="text" placeholder=""
                                                   class="form-control input-md" value="{{$user->person->apellidos}}">
                                            @endif
                                        @else

                                        <input id="textinput" name="last_name" type="text" placeholder=""
                                               class="form-control input-md">
                                        @endif
                                        <span class="help-block error-incomplete"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="textinput">Usuario</label>

                                    <div id="username" class="col-md-4" style="position: relative">
                                        @if(isset($user))
                                        <input id="user-no" name="username" type="text" placeholder=""
                                               class="form-control input-md" readonly value="{{isset($user) ? $user->username : ''}}">
                                        @else
                                        <input id="" name="username" type="text" placeholder=""
                                               class="form-control input-md" value="">
                                        @endif
                                        <span class="help-block error-incomplete"></span>
                                        <span style="position: absolute; top:0.5em; right: 2em; display: none"><img src="{{URL::to('img/ajax-loader2.gif')}}"></span>
                                        <span style="position: absolute; top:0.7em; right: 2em; display: none; color: forestgreen" class="glyphicon glyphicon-ok" ></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-md-4 control-label" for="textinput">Email</label>

                                    <div id="email" class="col-md-4">
                                        <input id="textinput" name="email" type="text" placeholder=""
                                               class="form-control input-md" value="{{isset($user) ? $user->email : ''}}">
                                        <span class="help-block error-incomplete"></span>
                                    </div>
                                </div>

                                <!-- Password input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="passwordinput">Password</label>

                                    <div id="password"  class="col-md-4">
                                        <input name="password" type="password" placeholder=""
                                               class="form-control input-md">
                                        <span class="help-block error-incomplete"></span>
                                    </div>
                                </div>

                                <!-- Select Basic -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="selectbasic">Tipo de Usuario</label>

                                    <div class="col-md-4">
                                        <select id="selectbasic" name="type" class="form-control">
                                            @foreach($types as $type)
                                                @if(isset($user) && $user->type == $type->codigo)
                                                    <option selected value="{{$type->codigo}}">{{$type->descripcion}}</option>
                                                @else
                                                    <option value="{{$type->codigo}}">{{$type->descripcion}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="singlebutton"></label>

                                    <div class="col-md-4">
                                        <a id="register_user" name="singlebutton" class="btn btn-primary">{{isset($user)? 'Guardar' : 'Registrar' }}</a>
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
                                <th>Activado</th>
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
                                <td style="text-align: center">{{$user->rm->apellidos}}</td>
                                @elseif($user->type == 'S')
                                <td style="text-align: center">{{$user->Sup->nombres}}</td>
                                <td style="text-align: center">{{$user->Sup->apellidos}}</td>
                                @elseif($user->type == 'P')
                                <td style="text-align: center">{{$user->gerprod->descripcion}}</td>
                                <td style="text-align: center"></td>
                                @else
                                <td style="text-align: center">{{$user->person->nombres}}</td>
                                <td style="text-align: center">{{$user->person->apellidos}}</td>
                                @endif
                                <td>@if($user->active == 1)SI @else NO @endif</td>
                                <td>
                                    <div class="div-icons-solicituds">

                                    <a href="{{URL::to('editar').'/'.$user->id}}"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a id="" href="" class="active-user" data-iduser = '{{$user->id}}'><span class="glyphicon glyphicon-ok" style="color: forestgreen"></span></a>
                                        <a id="" href="" class="look-user" data-iduser = '{{$user->id}}' ><span class="glyphicon glyphicon-remove" style="color: darkred"></span></a>
                                    </div>
                                </td>

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