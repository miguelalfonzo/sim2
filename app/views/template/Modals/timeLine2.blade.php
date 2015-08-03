<div class="timeLineModal">
    <div class="container-fluid hide">
        <div class="stage-container">
            <div class="stage col-md-3 col-sm-3 success">
                <div class="stage-header stage-success"></div>
                <div class="stage-content">
                    <h3 class="stage-title" style="white-space:nowrap">Inicio Fondo Institucional</h3>
						<span class="label label-info">
							{{ $solicitud_history{0}->createdBy->getName() }}
						</span>
                </div>

            </div>
            @foreach($flujo as $fl)
                <div class="stage col-md-3 col-sm-3">
                    <div class="stage-header"></div>
                    <p>{{ $fl->orden }}</p>
                    <div class="stage-content">
                        @if( is_null( $fl->desde ) && is_null( $fl->hasta ) )
                            <h3 class="stage-title">Validaci&oacute;n {{$fl->tipo_usuario}} .</h3>
                        @else
                            <h3 class="stage-title" style="white-space:nowrap">Aprobaci&oacute;n</h3>
                        @endif
                            <span class="label label-info">
                                {{$fl->nombre_usuario}}
                            </span>
                    </div>
                </div>
            @endforeach
            @foreach( $timelinehard as $linehard)

                @foreach( $linehard as $line)

                <div class="stage col-md-3 col-sm-3">
                    <div class="stage-header"></div>
                    {{--<p>{{ $linehard->orden }}</p>--}}
                    <div class="stage-content">

                            <h3 class="stage-title">{{ $line['title'] }} .</h3>
                        <span class="label label-info">
                                {{$line['info']}}
                            </span>
                    </div>
                </div>

                @endforeach
            @endforeach

            <table class="table table-bordered">
                @foreach($solicitud_history as $history)
                <tr>
                    <td>{{ $history->status_from }}</td>
                    <td>{{ $history->status_to }}</td>
                    <td>{{ $history->user_from }}</td>
                    <td>{{ $history->user_to }}</td>
                    <td>{{ $history->createdBy->getName()}}</td>
                </tr>
                @endforeach
            </table>



        </div>
    </div>
</div>

@include('template.Modals.timeLine')