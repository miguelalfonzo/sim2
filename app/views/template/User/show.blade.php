@extends('template.main')
@section('solicitude')
<div class="content">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitudes" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon>
                    Solicitudes
                </a>
            </li>
            @if ( Auth::user()->type == ASIS_GER )
                <li>
                    <a href="#fondos" role="tab" data-toggle="tab">
                        <icon class="fa fa-home"></icon>
                        Registrar Fondos
                    </a>
                </li>
            @endif
            @include('template.User.li_estado_cuenta')
            @if ( Auth::user()->type == REP_MED || Auth::user()->type == CONT || Auth::user()->type == TESORERIA)
                @if( Auth::user()->type == CONT)
                    <li>
                        <a href="#mantenimiento" role="tab" data-toggle="tab">
                            <i class="fa fa-user"></i>
                            Mantenimiento de Documentos
                        </a>
                    </li>
                @endif
            @endif
        </ul>
        <div class="tab-content" style="margin-top: .75em;">
            <div class="tab-pane fade active in" id="solicitudes">
                <div class="panel panel-default">
                    @include('template.User.menu')
                    @if( Auth::user()->type == TESORERIA )
                        @include('template.Modals.deposit')
                    @endif
                </div>
            </div>
            @include('template.tb_estado_cuenta')
            
            <!-- Mantenimiento de Documentos -->
            @if (Auth::user()->type == CONT)
                <div class="tab-pane fade" id="mantenimiento">
                    <div class="panel panel-default">
                        <div class="panel-body panel-default table_document_contabilidad">
                            <div id="" class="form-group col-xs-6 col-sm-3 col-md-3"></div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Solicitud Institucional -->
            @include('template.User.institucion')

        </div>
        <div>
            <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
            <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#">Ocultar leyenda</a>
        </div>
    </div>
</div>
@include('template.leyenda')
@if(isset($warnings[status]))
    @if ($warnings[status] == ok )
        <script type="text/javascript">
            $(document).ready(function() {
                bootbox.alert('<h4>' + {{$warnings[data]}} + '</h4>');
            });
        </script>
    @endif
@endif
@stop