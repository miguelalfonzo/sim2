@if ( ( $solicitud->id_user_assign == Auth::user()->id  && ! is_null( $solicitud->expenseHistory ) ) || ( Auth::user()->type == CONT && ! is_null( $solicitud->registerHistory ) ) )
	@if ( ( $solicitud->id_user_assign == Auth::user()->id &&  $solicitud->id_estado == GASTO_HABILITADO ) || ( Auth::user()->type == CONT && $solicitud->id_estado == REGISTRADO ) )
		<section class="row reg-expense">
			<input type="hidden" name="idgasto">
			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Comprobante</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<select id="proof-type" class="form-control">
						@foreach($typeProof as $val)
							<option value="{{$val->id}}" igv={{$val->igv}} marca="{{$val->marca}}">{{$val->descripcion}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">RUC</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="input-group">
						<input id="ruc" type="text" class="form-control" maxlength="11">
						<div class="input-group-addon search-ruc" data-sol="1">
							<span class="glyphicon glyphicon-search" style="font-size:1.0em"></span>
						</div>
						<input id="ruc-hide" type="hidden">
					</div>
				</div>
			</div>

			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Razón Social</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<button id="razon" type="button" class="form-control ladda-button" data-style="expand-left" data-spinner-color="#5c5c5c" value=0 data-edit=0 readonly>
				</div>
			</div>

			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Número de Comprobante</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="input-group">
						<input id="number-prefix" type="text" class="form-control" maxlength="4">
						<div class="input-group-addon">-</div>
				      	<input id="number-serie" class="form-control" type="text" maxlength="12">
					</div>
				</div>
			</div>

			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Descripción del Gasto</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<input id="desc-expense" type="text" class="form-control">
				</div>
			</div>

			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Fecha del Documento</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="input-group date">
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-calendar"></i>
						</span>
						<input id="date" type="text" class="form-control" maxlength="10" readonly>
					</div>
				</div>
			</div>

			<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Balance</label>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">	
					<div class="input-group">
				    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
				    	@if ( isset( $balance) )
				    		<input id="balance" class="form-control" type="text" value="{{$balance}}" disabled>
				    	@else
				      		<input id="balance" class="form-control" type="text" value="{{json_decode($detalle->detalle)->monto_aprobado}}" disabled>
				    	@endif
				    </div>
				</div>
			</div>

		</section>

		<section class="row reg-expense detail-expense" style="margin:0">
			<div style="padding:0 15px">
				<div class="panel panel-info">
					<div class="panel-heading">
						<span class="text-left">Detalle del Comprobante</span>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table id="table-items" class="table table-bordered">
								<thead>
									<tr>
										<th class="w-quantity">Cantidad</th>
										<th class="w-desc-item">Descripción</th>
										<th class="w-type-expense">Tipo de Gasto</th>
										<th class="w-total-item">Valor de Venta</th>
										<th>Eliminar</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th class="quantity"><input type="text" class="form-control" maxlength="4"></th>
										<th class="description"><input type="text" class="form-control"></th>
										<th>
											<select class="form-control type-expense">
												@foreach($typeExpense as $val)
													<option value="{{$val->id}}">{{$val->descripcion}}</option>
												@endforeach
											</select>
										</th>
										<th class="total-item">
											<div class="input-group">
										    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
										      	<input class="form-control" type="text" maxlength="8">
										    </div>
										</th>
										<th>
											<a class="delete-item" href="#">
												<span class="glyphicon glyphicon-remove"></span>
											</a>
										</th>
									</tr>
								</tbody>
							</table>
							@if ( Auth::user()->type == CONT )
								<aside class="col-xs-12 col-sm-6 col-md-4" style="padding:0;">
									<button id="add-item" type="button" class="btn btn-default">Agregar Item</button>
								</aside>
							@endif
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="row reg-expense detail-expense" style="margin:0">
			<div class="col-xs-12 col-sm-6 col-md-4 tot-document">
				<div class="form-expense">
					<label>Sub Total</label>
					<div class="input-group">
				    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
				      	<input id="sub-tot" class="form-control" type="text" value=0>
				    </div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 tot-document">
				<div class="form-expense">
					<label>Impuesto por Servicio</label>
					<div class="input-group">
				    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
				      	<input id="imp-ser" class="form-control" type="text" value=0>
				    </div>
				</div>
			</div>
			
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 tot-document">
				<div class="form-expense">
					<label>IGV</label>
					<div class="input-group">
				    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
				      	<input id="igv" class="form-control" type="text" igv="{{$igv->numero}}">
				    </div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-expense">
					<label>Monto Total</label>
					<div class="input-group">
				    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
				      	<input id="total-expense" class="form-control" type="text">
				    </div>
				</div>
			</div>

			@if ( Auth::user()->type == CONT )
				
				<!-- Retencion o Detraccion -->
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<div class="form-expense">
						<label>Retención o Detracción</label>
						<select id="regimen" class="form-control">
	                    	<option value=0 selected>NO APLICA</option>	
	                    	@foreach( $regimenes as $regimen )
		                        <option value="{{$regimen->id}}">{{$regimen->descripcion}}</option>                          
	                    	@endforeach
	                	</select>
					</div>
				</div>

				<!-- Monto de la Retencion -->
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" style="visibility:hidden">
					<div class="form-expense">
						<label>Monto de la Retención o Detracción</label>
						<input id="monto-regimen" type="text" class="form-control">
					</div>
				</div>

				<!-- REPARO -->
				<div id="dreparo" class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
					<div class="form-expense">
						<label>Reparo</label>
						<div class="input-group">
							<div class="btn-group" role="group">
								<label class="btn btn-default">
							 		<input value="1" type="radio" name="reparo" style="margin-top:.5em;">Si
								</label>
								<label class="btn btn-default">
									<input value="0" type="radio" name="reparo" style="margin-top:.5em;" checked>No
								</label> 
							</div>
					    </div>
					</div>
				</div>
			@endif
		</section>

		<section class="row reg-expense detail-expense" style="margin:0">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="form-expense">
					<button id="save-expense" type="button" class="btn btn-primary">Registrar</button>
					<button id="cancel-expense" type="button" class="btn btn-danger" style="display:none">Cancelar</button>
					<div class="inline"><p class="inline message-expense"></p></div>
				</div>
			</div>
		</section>

		@if ( Auth::user()->id == $solicitud->id_user_assign )
			<section class="container-fluid" >
	        	<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Detalle de Evento</h3>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" {{ isset($event) ? '' : 'action="'. URL::to('createEvent') .'" accept-charset="UTF-8" method="POST"' }}>
							{{ Form::token() }}
							<div class="form-group hide">
								<label for="name" class="col-sm-2 control-label">Id Solicitud</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="solicitud_id" placeholder="Id de Solicitud" maxlength="100" required="required" value="{{ $solicitud->id }}">
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">Nombre del Evento</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="name" placeholder="Nombre del Evento" maxlength="100" required="required" {{ isset($event) ? 'value="'. $event->name .'" disabled' : ''}}>
								</div>
							</div>
							<div class="form-group">
								<label for="event-date" class="col-sm-2 control-label">Fecha del Evento</label>
								<div class="col-sm-10">
									<div id="event-date" class="input-group">
										<span class="input-group-addon">
										<i class="glyphicon glyphicon-calendar"></i>
										</span>
										<input type="text" class="form-control" maxlength="10" maxlength="250" name="event_date" required="required"  {{ isset($event) ? 'value="'. $event->event_date .'" disabled' : ''}}>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="place" class="col-sm-2 control-label" >Lugar del Evento</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="place" placeholder="(Opcional)" maxlength="250" required="required" {{ isset($event) ? 'value="'. $event->place .'" disabled' : ''}}>
								</div>
							</div>
							<div class="form-group">
								<label for="description" class="col-sm-2 control-label">Descripcion del Evento</label>
								<div class="col-sm-10">
									<textarea name="description" type="text" class="form-control"  rows="4" maxlength="250" required="required" {{ isset($event) ? 'disabled' : '' }}>{{ isset($event) ? $event->description : ''}}</textarea>
								</div>
							</div>
							@if(!isset($event))
							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-10">
									<div class="btn-group" role="group" aria-label="Opciones">
										<button type="button" class="btn btn-primary btn_event_submit">Crear</button>
										<button type="button" class="btn btn-primary hide">CANCEL</button>
									</div>
								</div>
							</div>
							@endif
						</form>
						@if(!isset($event) || !$event->photos())
						<div class="container">
							<form class="form-horizontal" id="solicitude-upload-image-event" enctype="multipart/form-data" method="post" action="{{ url('testUploadImgSave') }}" autocomplete="off" style="{{ isset($event) ? '' : 'display:none;' }}">
								{{ Form::token() }}
								<div class="form-group hide">
									<label for="name" class="col-sm-2 control-label">Id Evento</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="event_id" placeholder="Id del Evento" maxlength="100" required="required" value="{{ isset($event) ? $event->id : '' }}">
									</div>
								</div>
								<div class="form-group ">
									<label for="name" class="col-sm-2 control-label"></label>
									<div class="col-sm-10">
										<span class="btn btn-info btn-file">
										Subir Imagenes <input type="file" name="image[]" id="upload-image-event" multiple="true" /> 
										</span>
									</div>
								</div>
							</form>
						</div>
						@endif
						<div class="span5" id="output">
							@if(isset($event))
								@if($event->photos())
									@foreach($event->photos() as $key => $photo)
										<div class="col-xs-6 col-md-3 solicitude_img thumbnail show_event_img" data-slide-num="{{ $key }}">
											<img data-img-id="{{$photo->id}}" src="{{asset($photo->directory.$photo->id.'.'.$photo->extension)}}" >
										</div>
									@endforeach
								@endif
							@endif
						</div>
					</div>
				</div>
	    	</section>
		
			<script>

			$(document).ready(function() {
			    $("#event-date input").datepicker({
			        clearBtn: true,
			        language: "es",
			        multidate: true,
			        todayHighlight: true,
			        toggleActive: true
			    });

			    var options = {
			                beforeSubmit:   showRequest,
			                success:        showResponse,
			                dataType:       'json' 
			        }; 
			    $('body').delegate('#upload-image-event','change', function(){
			        $('#solicitude-upload-image-event').ajaxForm(options).submit();         
			    }); 
			    $('.btn_event_submit').click(function(){
			        var eventForm = $(this).parent().parent().parent().parent();
			    	console.log("click");
			        eventForm.ajaxForm({
			                success: function(result){
			                    if(result.hasOwnProperty("status")){
			                        bootbox.alert(result.message);
			                        if(result.status=="error")
			                            console.log(eventForm);
			                        else if(result.hasOwnProperty("id")){
			                            $("input[name=event_id]").val(result.id);
			                            eventForm.find('input, textarea').prop('disabled', true);
			                            eventForm.find('button').first().hide('slow');
			                            $("#solicitude-upload-image-event").show('slow');
			                        }
			                    }
			                },
			                error: function(result){
			                    bootbox.alert(result);
			                }                
			        }).submit();     
			    });
			});     
			function showRequest(formData, jqForm, options) { 
			    $("#validation-errors").hide().empty();
			    $("#output").css('display','none');
			    return true; 
			} 
			function showResponse(response, statusText, xhr, $form)  { 
			    if(response.success == false)
			    {
			        var arr = response.errors;
			        $.each(arr, function(index, value)
			        {
			            if (value.length != 0)
			            {
			                $("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
			            }
			        });
			        $("#validation-errors").show();
			    } else {
			        for (var i = response.fileList.length - 1; i >= 0; i--) {
			            response.fileList[i]
			            $("#output").append('<div class="col-xs-6 col-md-3 solicitude_img thumbnail"> <img data-img-id='+ response.fileList[i]['id'] +' src="'+ response.fileList[i]['name'] +'" /></div>');
			            $("#output").css('display','block');
			        };
			    }
			}
			</script>
			@if( isset( $event ) && $event )
				<script>
	                $(document).ready(function()
	                {
	                 	{{ ''; $html = '<div id="carousel-example-captions" class="carousel slide" data-ride="carousel" data-interval="5000"><ol class="carousel-indicators">'; }}
	                 	@foreach($event->photos() as $key => $photo)
							{{''; $html.='<li data-target="#carousel-example-captions" data-slide-to="'. $key .'" class="'. ($key == 0 ? "active" : "") .'"></li>'; }}
	                 	@endforeach
						{{''; $html.='</ol>'; }}
						{{''; $html.='<div class="carousel-inner" role="listbox">'; }}
						@foreach($event->photos() as $key => $photo)
							{{''; $html .='<div class="item '. ($key == 0 ? "active" : "") .'">' .
									'<img class="img_idkc" src="'.asset($photo->directory.$photo->id.'.'.$photo->extension).'">' .
										'<div class="carousel-caption">' .
											'<h3 id="first-slide-label">'.$event->name.'</h3>' .
											'<p>'. $event->description .'</p>' .
										'</div>' .
								'</div>'; }}
	                 	@endforeach
						{{ ''; $html.='</div>'; }}
						{{ ''; $html .='<a class="left carousel-control" href="#carousel-example-captions" role="button" data-slide="prev">'.
											'<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>'.
											'<span class="sr-only">Previous</span>'.
										'</a>'.
										'<a class="right carousel-control" href="#carousel-example-captions" role="button" data-slide="next">'.
											'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'.
											'<span class="sr-only">Next</span>'.
										'</a>' }}
						{{'';$html.='</div>'; }}
						$(document).on('click', '.show_event_img', function(){
							var num = $(this).attr('data-slide-num');
							bootbox.dialog({
								message: '{{ $html }}',
								title  : "{{ $event->name }}",
								size   : "large"
							});
							$(".carousel-indicators>li, .carousel-inner>.item").removeClass("active");
							$(".carousel-indicators>li").eq(num).addClass("active");
							$(".carousel-inner>.item").eq(num).addClass("active");
						});
						$('.carousel').carousel();						
	                 });
		        </script>
			@endif
		@endif
	@endif
	<section class="row reg-expense" style="margin:0">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="form-expense">
				<div class="table-responsive" id="section-table-expense">
					@include('Dmkt.Solicitud.Section.gasto-table')
				</div>
				<input id="tot-edit-hidden" type="hidden">
			</div>
		</div>
	</section>
@endif

