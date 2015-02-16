@extends('template.main')
@section('solicitude')
<table id="table-expense" class="table table-bordered">
	<thead>
		<tr>
			<th>Comprobante</th>
			<th>RUC</th>
			<th>Razón Social</th>
			<th>Nro. Comprobante</th>
			<th>Fecha</th>
			<th>Monto Total</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<tr data-id="26">
			<th class="proof-type">Factura</th>
			<th class="ruc">20160641810</th>
			<th class="razon">LABORATORIOS BAGO DEL PERU S.A.</th>
			<th class="voucher_number">0001-654654</th>
			<th class="date_movement">14/02/2015</th>
			<th class="total"><span class="type_moeny">S/.&nbsp;<span class="total_expense">790</span></span></th>
			<th><a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
		</tr>	
												<tr data-id="24">
			<th class="proof-type">Otros</th>
			<th class="ruc"></th>
			<th class="razon"></th>
			<th class="voucher_number">-</th>
			<th class="date_movement">13/02/2015</th>
			<th class="total"><span class="type_moeny">S/.&nbsp;<span class="total_expense">30</span></span></th>
			<th><a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
		</tr>	
												<tr data-id="23">
			<th class="proof-type">Otros</th>
			<th class="ruc"></th>
			<th class="razon"></th>
			<th class="voucher_number">-</th>
			<th class="date_movement">13/02/2015</th>
			<th class="total"><span class="type_moeny">S/.&nbsp;<span class="total_expense">20</span></span></th>
			<th><a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
		</tr>	
		<tr data-id="25">
			<th class="proof-type">Otros</th>
			<th class="ruc"></th>
			<th class="razon"></th>
			<th class="voucher_number">-</th>
			<th class="date_movement">13/02/2015</th>
			<th class="total"><span class="type_moeny">S/.&nbsp;<span class="total_expense">12</span></span></th>
			<th><a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
		</tr>	
	</tbody>
</table>

<script>
$(document).off("click", ".elementCancel");
$(document).on("click", ".elementCancel", function(){
	var need_save = false;
	trElement 	  = $(this).parent().parent();
	trElement.children().each(function(i,data){
		if($(data).attr("class") != undefined){
			var input = $(data).children().first();
			if($(data).attr("data-data") != input.val())
					need_save = true;
		}
	});
	if(need_save == true){
		bootbox.confirm("Desea Salir sin guardar los cambios?", function(result){
			if(result == true){
				trElement.children().each(function(i,data){
					if($(data).attr("class") != undefined){
						$(data).html($(data).attr("data-data"));
					}else{
						$(data).html('<a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a>');
					}
				});
			}
		});
	}else{
		trElement.children().each(function(i,data){
			if($(data).attr("class") != undefined){
				$(data).html($(data).attr("data-data"));
			}else{
				$(data).html('<a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a>');
			}
		});
	}
});

$(document).off("click", ".elementSave");
$(document).on("click", ".elementSave", function(){
	var data_json = {}
	trElement 	  = $(this).parent().parent();
	trElement.children().each(function(i,data){
		if($(data).attr("class") != undefined){
			var input = $(data).children().first();
			$(data).html(input.val());
			data_json[$(data).attr("class")] = input.val();
		}else{
			$(data)	.html('<a class="elementEdit" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a> <a class="elementDelete" href="#"><span class="glyphicon glyphicon-remove"></span></a>');
		}
		$(data).attr("data-data", "");
	});
	bootbox.alert("Datos Guardados.");
});

$(document).off("click", ".elementEdit");
$(document).on("click", ".elementEdit", function(){
	var trElement = $(this).parent().parent();

	trElement.children().each(function(i,data){
		var tempData = $(data).html();
		if($(data).attr("class") != undefined){
			var input = $('<input type="text" style="width: 100%;"></input>');
			input.val(tempData);
			$(data).html(input);
		}else{
			$(data)	.html('<a class="elementSave" data-sol="1" href="#"><span class="glyphicon glyphicon-floppy-disk"></span></a> <a class="elementCancel" href="#"><span class="glyphicon glyphicon-remove"></span></a>');
		}
		$(data).attr("data-data", tempData);
	});
})

</script>

@stop