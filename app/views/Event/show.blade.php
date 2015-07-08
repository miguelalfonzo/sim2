@extends('template.main')
@section('solicitude')
    
	<div class="container-fluid">
		{{ Form::token() }}
		<div class="page-header">
		  <h3>Eventos</h3>
		</div>
		<div class="row" id="container-event">

		</div>
	</div>
	<script>
	$(document).ready(function(){
		
		getEvents();
		GBREPORTS.changeDateRange('M');
	});
	function getEvents(){
		    var url = URL_BASE + "eventos/list";
		    $.ajax({
		        type       : 'POST',
		        url        : url,
		        ContentType: false,
		        cache      : false,
		        data: {
		        	_token     : GBREPORTS.token,
		        	date_start: $('#drp_menubar').data('daterangepicker').startDate.format("L"),
		            date_end  : $('#drp_menubar').data('daterangepicker').endDate.format("L")
		    	}
		    }).done(function(dataResult) {
		    	$("#container-event").empty();
		     	$("#container-event").html(dataResult);
		    });
		}
	</script>
@endsection