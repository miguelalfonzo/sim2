@extends( 'template.main' )
@section( 'solicitude' )
	
	<ul class="nav nav-tabs" role="tablist">
		<li class="active">
			<a href="#tab-ppto-sup" role="tab" data-toggle="tab">
            	Supervisor
            </a>
		</li>
		<li>
			<a href="#tab-ppto-ger" role="tab" data-toggle="tab">
            	Gerente
            </a>
		</li>
		<li>
			<a href="#tab-ppto-ins" role="tab" data-toggle="tab">
            	Institucional
            </a>
		</li>
	</ul>		

	<div class="tab-content">
		<div class="tab-pane fade active in" id="tab-ppto-sup" data-type="1">

			<div class="row">
				<div class="form-group col-md-12">
					<h4><b>Carga de Presupuesto Supervisor</b></h4>
				</div>
			</div>
			<div class="form-group col-lg-2">
				<label>Año</label>
				<select class="form-control ppto-year">
					@foreach( $years as $year )
						<option value="{{ $year }}">{{ $year }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-lg-2">
				<label>Categoría</label>
				<select class="form-control ppto-category">   
					@foreach( $categories as $category )
						@if( $category->tipo == SUP )
							<option value="{{ $category->id }}">{{ $category->descripcion }}</option>
						@endif
					@endforeach
				</select>
			</div>

			<div class="form-group col-lg-4">
				<label>Excel</label>
				<div class="input-group">
					<span  class="input-group-addon btn glyphicon glyphicon-folder-open open-file" style="top:0"></span>
					<input type="text" class="form-control filename" readonly="true">
				</div>
				<input type="file" class="file" accept=" application/vnd.ms-excel , application/vnd.openxmlformats-officedocument.spreadsheetml.sheet , application/vnd.ms-excel.sheet.macroEnabled.12 " style="display:none">	
			</div>

			<div class="form-group col-lg-1">
				<button type="button" class="btn btn-primary load-ppto" style="margin-top:24px" >Cargar</button>
			</div>

			<div class="form-group col-lg-1">
				<button type="button" class="btn btn-primary search-ppto" style="margin-top:24px" >
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</div>

			<div class="container-fluid">
				<table id="table-ppto-1" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
				</table>
			</div>
		</div>

		<div class="tab-pane fade" id="tab-ppto-ger" data-type="2">
			<div class="row">
				<div class="form-group col-md-12">
					<h4><b>Carga de Presupuesto Gerentes</b></h4>
				</div>
			</div>
			<div class="form-group col-lg-2">
				<label>Año</label>
				<select class="form-control ppto-year">
					@foreach( $years as $year )
						<option value="{{ $year }}">{{ $year }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-lg-2">
				<label>Categoría</label>
				<select class="form-control ppto-category"> 
					@foreach( $categories as $category )
						@if( in_array( $category->tipo , [ GER_PROD , GER_PROM ] ) ) 
							<option value="{{ $category->id }}">{{ $category->descripcion }}</option>
						@endif
					@endforeach
				</select>
			</div>

			<div class="form-group col-lg-4">
				<label>Excel</label>
				<div class="input-group">
					<span  class="input-group-addon btn glyphicon glyphicon-folder-open open-file" style="top:0"></span>
					<input type="text" class="form-control filename" readonly="true">
				</div>
				<input type="file" class="file" accept=" application/vnd.ms-excel , application/vnd.openxmlformats-officedocument.spreadsheetml.sheet , application/vnd.ms-excel.sheet.macroEnabled.12 " style="display:none">	
			</div>

			<div class="form-group col-lg-1">
				<button type="button" class="btn btn-primary load-ppto" style="margin-top:24px" >Cargar</button>
			</div>

			<div class="form-group col-lg-1">
				<button type="button" class="btn btn-primary search-ppto" style="margin-top:24px" >
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</div>

			<div class="container-fluid">
				<table class="table table-striped table-hover table-bordered">
					<thead>
						<tr>
							<th>Categoría</th>
							<th>Familia</th>
							<th>Monto</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						{{-- @foreach( $rpta[ 'Data' ] as $row )
							<tr>
								<td>{{ $row->supervisor }}</td>
								<td>{{ $row->visitador }} </td>
							</tr>
						@endforeach --}}
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane fade" id="tab-ppto-ins" data-type="3">
			<div class="row">
				<div class="form-group col-md-12">
					<h4><b>Carga de Presupuesto Institucional</b></h4>
				</div>
			</div>
			<div class="form-group col-lg-2">
				<label>Año</label>
				<select class="form-control ppto-year">
					@foreach( $years as $year )
						<option value="{{ $year }}">{{ $year }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-lg-2">
				<label>Monto</label>
				<input id="ppto-amount" class="form-control">
			</div>

			<div class="form-group col-lg-1">
				<button type="button" class="btn btn-primary load-ppto" style="margin-top:24px" >Cargar</button>
			</div>

			<div class="form-group col-lg-1">
				<button type="button" class="btn btn-primary search-ppto" style="margin-top:24px" >
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</div>

		</div>
	</div>

	<script>
		
		var file     = $( '.file' );
		var filename = $( '.filename' );

		function loadPPTO( type , year )
		{
			$.ajax(
			{
				type : 'POST',
				url  : 'load-ppto',
				data : 
				{
					_token : GBREPORTS.token,
					type   : type,
					year   : year
				}
			}).fail( function( statusCode , errorThrown )
			{
				ajaxError( statusCode , errorThrown );
			}).done( function( response )
			{
				var dataTable = $( '#table-ppto-' + type ).DataTable(
	            {
	                columns         : response.columns,
	                data            : response.Data ,
	                dom             : "<'row'<'col-xs-6'><'col-xs-6 pull-right'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
	                destroy         : true,
	                pageLength      : 10,
	                stateSave       : true,
	                scrollX         : true,
	                language        :
	                {
	                    search       : 'Buscar',
	                    zeroRecords  : 'No hay ' + 'solicitudes' ,
	                    infoEmpty    : 'No ha encontrado ' + 'solicitudes' +' disponibles',
	                    info         : 'Mostrando _END_ de _TOTAL_ ' + 'solicitudes' ,
	                    lengthMenu   : "Mostrando _MENU_ registros por página",
	                    infoEmpty    : "No ha encontrado información disponible",
	                    infoFiltered : "(filtrado de _MAX_ regitros en total)",
	                    paginate     : 
	                    {
	                        sPrevious : 'Anterior',
	                        sNext     : 'Siguiente'
	                    }
	                },
	                createdRow: function( row , data , dataIndex )
	                {
	                    $( row ).append( '<input type="hidden" class="ppto-id" value="' + data.id + '">' );
	                }
	            });
			});
		}

		$( document ).ready( function()
		{
			$( '#ppto-amount' ).numeric( { negative : false } );
			loadPPTO( 1 , $( '#tab-ppto-sup .ppto-year' ).val() );
    	});

		$( '.open-file' ).click( function()
		{
			$( this ).closest( '.form-group' ).find( 'input[ type=file ]' ).click();
		});

		file.on( 'change' , function()
		{
			$( this ).parent().find( '.filename' ).val( this.files[ 0 ].name ).closest( '.form-group' ).addClass( 'has-success' ).removeClass( 'has-error' );
		});

		$( '.load-ppto' ).click( function()
		{
			var panel = $( this ).closest( '.tab-pane' );
			var data = new FormData();
			var type = panel[ 0 ].dataset.type;
			if( type == 3 )
			{
				data.append( 'year'  , panel.find( '.ppto-year' ).val() );
				data.append( 'amount' , panel.find( '#ppto-amount' ).val() );
			}
			else
			{
				data.append( 'file' , panel.find( '.file' )[ 0 ].files[ 0 ] );
				data.append( 'year'  , panel.find( '.ppto-year' ).val() );
				data.append( 'category'  , panel.find( '.ppto-category' ).val() );	
			}
			data.append( 'type' , type );
			data.append( '_token' , GBREPORTS.token );
			console.log( data );
			
			$.ajax(
			{
				type : 'POST',
				url  : 'upload-ppto',
				data : data,
				contentType: false,
				processData: false,
				cache: false,
				dataType: 'json',
			}).fail( function( statusCode , errorThrown )
			{
				ajaxError( statusCode , errorThrown );
			}).done( function( response )
			{
				if( response.Status == ok )
				{
					response.Description = 'Se cargo el ppto correctamente';
				}
				bootboxMessage( response );
			});
		});

	</script>
@stop
