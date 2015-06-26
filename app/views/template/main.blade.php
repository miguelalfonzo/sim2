<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
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
    {{ HTML::style('css/ladda-themeless.min.css') }}
    {{ HTML::style('css/stylos.css') }}
    {{ HTML::style('css/typeahead.css') }}
    {{ HTML::style('css/main.css') }}
    {{ HTML::style('css/datepicker3.css') }}
    {{ HTML::script('js/jquery_2.1.0.min.js') }}
    <!-- idkc : Report CSS Library -->
    {{ HTML::style('css/daterangepicker-bs3.css') }}
    {{ HTML::style('css/bago.report.css') }}
    {{ HTML::style('css/gsdk-base.css') }}
    <!-- idkc : Report CSS Library -->
    
    
</head>
<body>
<header>
    @if (Auth::user()->type == ASIS_GER)
        {{ HTML::link('/registrar-fondo', '', array('id' => 'logo', 'title' => 'Bagó Perú', 'alt' => 'Bagó Perú')) }}
    @else
        {{ HTML::link('/show_user', '', array('id' => 'logo', 'title' => 'Bagó Perú', 'alt' => 'Bagó Perú')) }}
    @endif
    <a id="logout" href="{{URL::to('logout')}}" title="Cerrar Sesión" alt="Cerrar Sesión">
        <bdi>{{strtoupper(Auth::user()->username)}}  | 
            <span class="closed-session">Cerrar sesión</span>
            <span class="off"></span>
        </bdi>
        <img src="{{URL::to('/')}}/img/user.png">
    </a>
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
<div class="container-fluid" style="max-height: 100vh; padding-left: 0; padding-right: 0;">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="/*margin-bottom: 0*/">
            <div class="container-fluid">
                <div class="navbar-header relative">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reportes <span class="caret"></span></a>
                        <ul id="menu-report" class="dropdown-menu" role="menu">
                            <li class="report_menubar_option">
                                <a href="#" rel="new" data-toggle="modal" data-target=".report_new">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span>
                                    <span class="glyphicon-class"> Nuevo Reporte</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        </ul>
                    </li>
                    <ul class="nav navbar-nav pull-left" id="btn_extra" style="display:none;">
                        <li class="report_menubar_option">
                            <a href="#" rel="export">
                                <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
                                <span class="glyphicon-class">  Exportar</span>
                            </a>
                        </li>
                        <li class="report_menubar_option">
                            <a href="#" rel="email">
                                <span class="glyphicon glyphicon-envelope" ></span>
                                <span class="glyphicon-class">  Enviar Correo</span>
                            </a>
                        </li>
                    </ul>
                 </ul>

                 <div id="drp_menubar" class="navbar-form navbar-right btn-default navbar-btn" style="cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: -8px;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    <span></span> <b class="caret"></b>
                </div>
            </div>
          </div>
        </nav>
        <div id="dataTable" class="container-fluid" style="font-size: 10pt; ">
            @yield('solicitude')
        </div>
    </div>


<div id="loading" style="display: none">
    {{ HTML::image('img/spiffygif.gif') }}
</div>
<!-- <section>
     @yield('actividad')
 </section>-->

<script type="text/javascript">
    URL_BASE = '{{ asset('/') }}';
</script>

{{ HTML::script('js/jquery-ui.min.js') }}
{{ HTML::script('js/jquery.blockUI.js') }}
{{ HTML::script('js/jquery.form.js') }}
{{ HTML::script('js/jquery.numeric.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap-datepicker.js') }}
{{ HTML::script('js/locales/bootstrap-datepicker.es.min.js') }}
{{ HTML::script('js/jquery.dataTables.min.js') }}
{{ HTML::script('js/dataTables.bootstrap.js') }}
{{ HTML::script('js/spin.min.js') }}
{{ HTML::script('js/ladda.min.js') }}
{{ HTML::script('js/bootbox.min.js') }}
{{ HTML::script('js/bootstrap-lightbox.js') }}
{{ HTML::script('js/typeahead.js') }}
{{ HTML::script('js/js.js') }}
{{ HTML::script('js/jsdmkt.js') }}
<!-- idkc: Report Library -->
{{ HTML::script('js/locales/bootstrap-tagsinput.min.js') }}
{{ HTML::script('js/moment.js') }}
{{ HTML::script('js/moment.locale.es.js') }}
{{ HTML::script('js/daterangepicker.js') }}
{{ HTML::script('js/bago.reports.js') }}
{{ HTML::script('js/bago.reports.main.js') }}
{{ HTML::script('js/jquery.bootstrap.wizard.js') }}
{{ HTML::script('js/wizard.js') }}
<!-- idkc: Report Library -->
<script>
    </script>
</body>
</html>