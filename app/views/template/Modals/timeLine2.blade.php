<div class="timeLineModal">
    <div class="container-fluid hide">

        {{--<div class="stage-container">--}}
        {{--@foreach($flujo as $fl)--}}
        {{--<div class="stage col-md-3 col-sm-3">--}}
        {{--<div class="stage-header "></div>--}}
        {{--<div class="stage-content">--}}
        {{--@if( is_null( $fl->desde ) && is_null( $fl->hasta ) )--}}
        {{--<h3 class="stage-title">Validaci&oacute;n {{$fl->tipo_usuario}} .</h3>--}}
        {{--@else--}}
        {{--<h3 class="stage-title" style="white-space:nowrap">Aprobaci&oacute;n</h3>--}}
        {{--@endif--}}
        {{--<span class="label label-info">--}}
        {{--{{$fl->nombre_usuario}}--}}
        {{--</span>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endforeach--}}
        {{--</div>--}}
        <div class="stage-container">
            @for ($i = 0; $i < count($solicitud_history); $i++)
                {{ ''; $history = $solicitud_history[$i] }}
                <div class="stage col-md-3 col-sm-3 @if($history->status_to == 9 || $history->status_to == 8) rejected @else success @endif">
                    <div class="stage-header @if($history->status_to == 9 || $history->status_to == 8) stage-rejected @else stage-success @endif"></div>
                    {{--<p>{{ $history->status_from }}</p>--}}

                    {{--<p>{{ $history->orden }}</p>--}}

                    {{--<p>{{ $history->count }}</p>--}}

                    <div class="stage-content">

                        <h3 class="stage-title"
                            style="white-space:nowrap">
                            @if($i==0)
                                @if($solicitud->idtiposolicitud == SOL_INST )
                                    Inicio de Fondo Institucional
                                @else
                                    Inicio de Solicitud
                                @endif
                            @else {{ $history->statusFrom['descripcion_min'] }}
                            @endif
                        </h3>

                        <span class="label label-info">
                                {{ strtoupper($history->createdBy->getName())}}
                            </span>
                        {{--<span class="label label-default">--}}
                        {{--{{ $history->user_from}}--}}
                        {{--</span>--}}
                        {{--<span class="label label-default">--}}
                        {{--{{ $history->status_from}}--}}
                        {{--</span>--}}
                        @if($history->estimed_time)
                            <div class="time-estimated">
                                <span class="label label-primary">
                                    {{ $history->estimed_time}}
                                </span>
                            </div>
                        @endif
                        <span class="label label-info">{{ $history->created_at}}</span>
                        <div class="time-history-duration">
                            <span class="label label-{{$history->duration_color}}">{{ $history->duration}} <span class="glyphicon {{$history->hand}}">
                            </span></span>
                        </div>
                    </div>
                </div>
            @endfor
            <?php
            $historyArray = $solicitud_history->toArray();
            $adicional = 0;

            /*  if (count($historyArray) - 2 >= 0) { // idkc : VERIFICA QUE EL ARRAY TENGA ALMENOS 2 ELEMENTOS
                  if ($historyArray[count($historyArray) - 1] == $historyArray[count($historyArray) - 2]) { // idkc : VERIFICA SI EL ULTIMO ESTADO ES IGUAL AL PENULTIMO
                      $adicional = 1;
                  }
              }

               if (count($historyArray) > 1) {
                  $lastHistory = $historyArray[count($historyArray) - 1];
                  $num = $lastHistory["status_from"]["id"] + $adicional;
              }*/
            $num = $historyArray[count($historyArray) - 1]['orden'];
            $count_flujo = 0;
            $j = count($historyArray) - 1;
            if ($solicitud->idtiposolicitud != SOL_INST) {
                $count_flujo = count($flujo);
                $num = $num == $count_flujo ? $num + 1 : $num;
            }
            ?>
            @if( $solicitud->idtiposolicitud != SOL_INST)
                @for ($i = $num; $i < $count_flujo; $i++)
                    {{ ''; $fl = $flujo[$i] }}
                    <div class="stage col-md-3 col-sm-3 @if($i == $num) pending @endif">
                        <div class="stage-header @if($i == $num) stage-pending @endif"></div>
                        <div class="stage-content">
                            @if( is_null( $fl->desde ) && is_null( $fl->hasta ) )
                                <h3 class="stage-title">Validaci&oacute;n {{$fl->tipo_usuario}} .</h3>
                            @else
                                <h3 class="stage-title" style="white-space:nowrap">Aprobaci&oacute;n</h3>
                            @endif
                            <span class="label label-info">
                                {{$fl->nombre_usuario}}
                            </span>
                                <div class="time-estimated">
                                <span class="label label-primary ">{{$fl->estimed_time}}
                                    </span>
                                </div>


                        </div>
                    </div>
                @endfor
            @endif
            <?php
            $count_history = $j - $count_flujo;
            $num_static = ($count_history < 0) ? 0 : ($count_history);
            ?>
            @for($i = $num_static; $i < count($line_static); $i ++)

                {{ ''; $line = $line_static[$i] }}
                <div class="stage col-md-3 col-sm-3 @if($i == $count_history) pending @endif">
                    <div class="stage-header @if($i == $count_history) stage-pending @endif"></div>

                    <div class="stage-content">

                        <h3 class="stage-title">{{ $line['title'] }} .</h3>
                <span class="label label-info">
                {{$line['info']}}
                </span>
                        @foreach ($time_flow_event as $time_flow)
                            @if ($time_flow->status_id == $line['status_id'] && $time_flow->to_user_type == $line['user_type_id'])
                                <div class="time-estimated">
                                <span class="label label-primary">
                                    {{ $time_flow->hours  }}
                                </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            @endfor
        </div>
    </div>
</div>

{{--@include('template.Modals.timeLine')--}}