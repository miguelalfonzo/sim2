<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="eSinergy">
		<title>Test de productos</title>

		{{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('css/jquery-ui.min.css') }}
		{{ HTML::style('css/datepicker.css') }}
		{{ HTML::style('css/datepicker3.css') }}
		{{ HTML::style('css/ladda-themeless.min.css') }}
		{{ HTML::style('css/pace-theme-center-circle.css') }}
        {{ HTML::style('css/stylos.css') }}
		{{ HTML::style('css/main.css') }}
        {{ HTML::script('js/jquery_2.1.0.min.js') }}
        {{ HTML::script('js/jquery-ui.min.js') }}
	</head>
	<body>
    <header>
        {{ HTML::link('/', '', array('id' => 'logo', 'title' => 'Bagó Perú', 'alt' => 'Bagó Perú')) }}
        <a id="logout" href="#" title="Cerrar sesión" alt="Cerrar sesión"><bdi>Cerrar sesión</bdi><img src="{{URL::to('/')}}/img/user.png"></a>
    </header>
        <section>
            @yield('content')
        </section>
        <section>
            @yield('solicitude')
        </section>
        <section>
            @yield('actividad')
        </section>




        {{ HTML::script('js/load_client.js') }}
		{{ HTML::script('js/jquery.numeric.js') }}
		{{ HTML::script('js/bootstrap.min.js') }}
		{{ HTML::script('js/bootstrap-datepicker.js') }}
		{{ HTML::script('js/locales/bootstrap-datepicker.es.js') }}
		{{ HTML::script('js/spin.min.js') }}
		{{ HTML::script('js/ladda.min.js') }}
		{{ HTML::script('js/bootbox.min.js') }}
		{{ HTML::script('js/main.js') }}
		{{ HTML::script('js/canela.js') }}
        {{ HTML::script('js/js.js') }}
	</body>
</html>