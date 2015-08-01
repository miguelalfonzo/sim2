<div class="timeLineModal">
    <div class="container-fluid hide">
        <div class="stage-container">
            <div class="stage col-md-3 col-sm-3 success">
                <div class="stage-header stage-success"></div>
                <div class="stage-content">
                    <h3 class="stage-title" style="white-space:nowrap">Inicio Fondo Institucional</h3>
						<span class="label label-info">
							E. FLORES
						</span>
                </div>

            </div>
            @foreach($flujo as $fl)
                <div class="stage col-md-3 col-sm-3 rejected">
                    <div class="stage-header stage-rejected"></div>
                        <div class="stage-content">
                            @if( is_null( $fl->desde ) && is_null( $fl->hasta ) )
                                <h3 class="stage-title">Validaci&oacute;n {{$fl->tipo_usuario}} .</h3>
                            @else
                                <h3 class="stage-title" style="white-space:nowrap">Aprobaci&oacute;n</h3>
                            @endif
                                <span class="label label-info">

                                </span>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>