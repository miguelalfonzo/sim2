<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="author" content="Laboratorito Bagó | Perú">
    <title>Sistema de Inversiones Marketing</title>
    
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-lightbox.css')}}
    {{ HTML::style('css/bootstrap-theme.min.css') }} 
    {{ HTML::style('css/jquery-ui.min.css') }}
    {{ HTML::style('css/ladda-themeless.min.css') }}
    {{ HTML::style('css/stylos.css') }}
    {{ HTML::style('css/typeahead.css') }}
    {{ HTML::style('css/main.css') }}
    {{ HTML::style('css/datepicker3.css') }}
    <!-- idkc : Report CSS Library -->
    {{ HTML::style('css/dataTables.bootstrap.css') }}
    {{ HTML::style('css/daterangepicker-bs3.css') }}
    {{ HTML::style('css/bago.report.css') }}
    {{ HTML::style('css/gsdk-base.css') }}
    <!-- TIME LINE -->
    {{ HTML::style('css/timeline.css') }}
    <!-- idkc : Report CSS Library -->
    {{ HTML::script('js/jquery_2.1.0.min.js') }}

</head>
<body>
<div id="alert-console" class="container-fluid" style="z-index: 99999999; margin-top: 10px;"></div>
<header>
    @if (Auth::user()->type == ASIS_GER)
        {{ HTML::link('/registrar-fondo', '', array('id' => 'logo', 'title' => 'Bagó Perú', 'alt' => 'Bagó Perú')) }}
    @else
        {{ HTML::link('/show_user', '', array('id' => 'logo', 'title' => 'Bagó Perú', 'alt' => 'Bagó Perú')) }}
    @endif
    <a id="logout" href="{{URL::to('logout')}}" title="Cerrar Sesión" alt="Cerrar Sesión">
        <bdi style="font-size: 11pt;">{{ Auth::user()->getFirstName() }}  | 
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
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="/*margin-bottom: 0*/; z-index: 10;">
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Solicitud <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if ( in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD ) ) )
                            <li><a href="{{URL::to('nueva-solicitud')}}">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span>
                                    <span class="glyphicon-class"> Nuevo</span></a></li>
                            <li role="separator" class="divider"></li>
                            @endif
                            <li><a href="{{ URL::to('show_user') }}">Listado de Solicitudes</a></li>
                            @if ( Auth::user()->type == ASIS_GER )
                            <li><a href="{{ URL::to('solicitude/institution') }}">Solicitudes Institucionales</a></li>
                            @endif
                        </ul>
                    </li>
                    <li><a href="{{ URL::to('solicitude/statement')}}">Movimientos</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reportes <span class="caret"></span></a>
                        <ul id="menu-report" class="dropdown-menu" role="menu">
                            <li class="report_menubar_option new">
                                <a href="#" rel="new" data-toggle="modal" data-target=".report_new">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true" ></span>
                                    <span class="glyphicon-class"> Nuevo Reporte</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                        </ul>
                    </li>
                    @if ( in_array(Auth::user()->type, array(SUP, GER_PROD, GER_PROM, GER_COM, CONT)) )
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Configuración <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            @if ( in_array(Auth::user()->type, array(SUP, GER_PROD, GER_PROM, GER_COM)) )
                            <li><a data-toggle="modal" data-target="#modal-temporal-user">Derivación de Usuario</a>
                            </li>
                            @endif
                            @if ( in_array(Auth::user()->type, array(CONT, GER_COM, TESORERIA)) )
                            <li><a href="{{ URL::to('maintenance/fondos') }}">Mantenimiento de Fondos</a></li>
                            @endif
                            @if ( in_array(Auth::user()->type, array(CONT, GER_COM)) )
                            <li><a href="{{ URL::to('maintenance/dailyseatrelation') }}">Mantenimiento de Cuentas - Marcas</a></li>
                            <li><a href="{{ URL::to('maintenance/fondoaccount') }}">Mantenimiento de Cuentas de Fondos</a></li>
                            <li><a href="{{ URL::to('maintenance/inversion') }}">Mantenimiento de Inversion</a></li>
                            <li><a href="{{ URL::to('maintenance/activity') }}">Mantenimiento de Actividades</a></li>
                            <li><a href="{{ URL::to('maintenance/investmentactivity') }}">Mantenimiento de Inversion-Actividad</a></li>
                            <li><a href="{{ URL::to('maintenance/parameters') }}">Mantenimiento de Parametros</a></li>

                            @endif
                            @if ( in_array(Auth::user()->type, array(CONT)) )
                            <li><a href="{{ URL::to('maintenance/finddocument') }}">Mantenimiento de Documentos</a></li>
                            <li><a href="{{ URL::to('maintenance/documenttype') }}">Mantenimiento de Tipo de Documentos</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif                    
                    <li class="report_menubar_option btn_extra" style="display:none;">
                        <a href="#" rel="export">
                            <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
                            <span class="glyphicon-class">  Exportar</span>
                        </a>
                    </li>
                    <li class="report_menubar_option btn_extra2" style="display:none;">
                        <a href="#" rel="email">
                            <span class="glyphicon glyphicon-envelope" ></span>
                            <span class="glyphicon-class">  Enviar Correo</span>
                        </a>
                    </li>
                    
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
            <br>
        </div>
<!--         <footer>
          <div class="container-fluid">
            <hr>
            <p class="text-center" style="margin-top: -10px;"><small>Laboratorios Bagó del Perú | &copy; 2015<small></p>
          </div>
        </footer> -->
    </div>


<div id="loading" style="display: none">
    {{ HTML::image('img/spiffygif.gif') }}
</div>

<!-- <section>
     @yield('actividad')
 </section>-->

@include('template.Modals.temporal_user')

<script type="text/javascript">
    URL_BASE = '{{ asset('/') }}';
    $(document).ready( function() 
    {
        @if ( isset( $alert[ 'msg' ] ) && $alert[ 'msg' ] != '' )
            $('#alert-console').prepend( 
                $('<div class="alert alert-{{ $alert[ 'type' ] }}" role="alert">' + 
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '<strong>Warning!</strong> {{ $alert[ 'msg' ] }}' +
                '</div>') );
        @endif
        window.setTimeout(function() {
            $(".alert").fadeTo(1500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 5000);
    });
</script>

{{ HTML::script('js/jquery.blockUI.js') }}
{{ HTML::script('js/jquery.numeric.js') }}
{{ HTML::script('js/jquery-ui.min.js') }}
{{ HTML::script('js/jquery.form.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/bootstrap-datepicker.js') }}
{{ HTML::script('js/locales/bootstrap-datepicker.es.js') }}
{{ HTML::script('js/bootbox.min.js') }}
{{ HTML::script('js/jquery.dataTables.min.js') }}
{{ HTML::script('js/dataTables.bootstrap.js') }}
{{ HTML::script('js/spin.min.js') }}
{{ HTML::script('js/ladda.min.js') }}
{{ HTML::script('js/bootstrap-lightbox.js') }}
{{ HTML::script('js/typeahead.js') }}
{{ HTML::script('js/js.js') }}
{{ HTML::script('js/jsdmkt.js') }}
<!-- idkc: Report Library -->
{{ HTML::script('js/locales/bootstrap-tagsinput.min.js') }}
{{ HTML::script('js/moment.js') }}
{{ HTML::script('js/moment.locale.es.js') }}
{{ HTML::script('js/daterangepicker.js') }}
{{ HTML::script('js/jquery.bootstrap.wizard.js') }}
{{ HTML::script('js/bago.reports.js') }}
{{ HTML::script('js/bago.reports.main.js') }}
{{ HTML::script('js/wizard.js') }}
<!-- idkc: Report Library -->

</body>
</html>