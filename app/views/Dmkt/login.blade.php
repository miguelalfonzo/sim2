<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="eSinergy">
    <title>Descargos Marketing y Registro Gastos</title>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/stylos.css') }}
    {{ HTML::script('js/jquery_2.1.0.min.js') }}
</head>
<body style="background:url(img/logo-marcadeagua.png);">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default" style="margin-top:45%;">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-lock"></span>Acceso al Sistema</div>
                <div class="panel-body">
                    {{ Form::open(array('url'=>'login','class'=>'form-horizontal')) }}

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">
                                Usuario</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="username" id="inputEmail3"  required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">
                                Clave</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" id="inputPassword3"  required>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"/>
                                        Recordar Clave
                                    </label>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group last" style="padding: 10px;" class="col-sm-3 control-label">
                            <div class="btn-group btn-group-justified">
                                <button type="submit" class="btn btn-success btn-lg"  style="width: 33.33%; float: left !important; font-size: 12pt;">Ingresar</button>
                                <button type="reset" class="btn btn-default btn-lg"  style="width: 33.33%; float: left !important; font-size: 12pt;">Limpiar</button>
                                <a href="http://intra.bagoperu.com.pe/produccion/intranet.php" class="btn btn-danger btn-lg"  style="width: 33.33%; float: left !important; font-size: 12pt;">Regresar</a>
                            </div>
                        </div>
                   {{ Form::close() }}
                </div>
                <!-- <div class="panel-footer">
                    Not Registred? <a href="http://www.jquery2dotnet.com">Register here</a>
                </div> -->
            </div>
        </div>
    </div>
</div>
{{ HTML::script('js/bootstrap.min.js') }}
</body>
</html>
