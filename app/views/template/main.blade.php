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
    {{ HTML::style('css/dataTables.bootstrap.css') }}
    {{ HTML::style('css/bootstrap-lightbox.css')}}
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/jquery-ui.min.css') }}
    {{ HTML::style('css/datepicker.css') }}
    {{ HTML::style('css/ladda-themeless.min.css') }}
    {{ HTML::style('css/stylos.css') }}
    {{ HTML::style('css/typeahead.css') }}
    {{ HTML::style('css/main.css') }}
    {{ HTML::script('js/jquery_2.1.0.min.js') }}
    {{ HTML::script('js/jquery-ui.min.js') }}
    {{ HTML::script('js/jquery.dataTables.min.js') }}
    {{ HTML::script('js/dataTables.bootstrap.js') }}
</head>
<body>
<header>
    {{ HTML::link('/show_rm', '', array('id' => 'logo', 'title' => 'Bagó Perú', 'alt' => 'Bagó Perú')) }}
    <a id="logout" href="{{URL::to('logout')}}" title="Cear sesión" alt="Cerrar) sesión">
       <bdi>{{strtoupper(Auth::user()->username)}}  | <span class="closed-session">Cerrar sesión</span><span class="off"></span></bdi>
        <img src="{{URL::to('/')}}/img/user.png"></a>


    @if(Auth::user()!= null)
        @if(Auth::user()->type == 'R')
        <input id="typeUser" type="hidden" value="R">
        @endif
        @if(Auth::user()->type == 'S')
        <input id="typeUser" type="hidden" value="S">
        @endif
        @if(Auth::user()->type == 'G')
        <input id="typeUser" type="hidden" value="G">
        @endif
        @if(Auth::user()->type == 'C')
        <input id="typeUser" type="hidden" value="C">
        @endif
        @if(Auth::user()->type == 'P')
        <input id="typeUser" type="hidden" value="P">
        @endif
        @if(Auth::user()->type == 'T')
        <input id="typeUser" type="hidden" value="T">
        @endif
        @if(Auth::user()->type == 'AG')
                <input id="typeUser" type="hidden" value="AG">
        @endif
    @endif

</header>
<section>
    @yield('content')
</section>
<section>
    @yield('solicitude')
</section>

<div id="loading" style="display: none">
    <img src="{{URL::to('/')}}/img/spiffygif.gif">
</div>
<!-- <section>
     @yield('actividad')
 </section>-->
{{ HTML::script('js/jquery.blockUI.js') }}
{{ HTML::script('js/jquery.numeric.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap-datepicker.js') }}
{{ HTML::script('js/locales/bootstrap-datepicker.es.js') }}
{{ HTML::script('js/spin.min.js') }}
{{ HTML::script('js/ladda.min.js') }}
{{ HTML::script('js/bootbox.min.js') }}
{{ HTML::script('js/js.js') }}
{{ HTML::script('js/jsdmkt.js') }}
{{ HTML::script('js/bootstrap-lightbox.js') }}
{{ HTML::script('js/typeahead.js') }}
</body>
</html>