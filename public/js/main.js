var SUCCESS = 0;
var INFO = 1;
var WARNING = 2;
var ERROR = 3;
var SKIP = 4;

function commaSeparateNumber(val){
	while (/(\d+)(\d{3})/.test(val.toString())){
		val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
	}
	return val;
}

function replaceAll(str, find, replace) {
	return str.replace(new RegExp(find, 'g'), replace);
}

function isValidDate(month, year) {
	var d = new Date(year, month - 1, 1);
	return d && (d.getMonth() + 1) == month && d.getFullYear() == year;
}

function showResult(id, type, message){
	switch(type){
		case SUCCESS:
			$("#result-" + id).addClass("has-success");
			$("#btn_" + id).addClass("btn-success");
			$("#result-" + id + " > span").addClass("glyphicon-ok");
			$("#result-" + id + " > span").css('display', 'block');
			break;
		case INFO:
			$("#result-" + id).addClass("has-info");
			$("#btn_" + id).addClass("btn-info");
			$("#result-" + id + " > span").addClass("glyphicon-comment");
			$("#result-" + id + " > span").css('display', 'block');
			break;
		case WARNING:
			$("#result-" + id).addClass("has-warning");
			$("#btn_" + id).addClass("btn-warning");
			$("#result-" + id + " > span").addClass("glyphicon-warning-sign");
			$("#result-" + id + " > span").css('display', 'block');
			break;
		case ERROR:
			$("#result-" + id).addClass("has-error");
			$("#btn_" + id).addClass("btn-danger");
			$("#result-" + id + " > span").addClass("glyphicon-remove");
			$("#result-" + id + " > span").css('display', 'block');
			break;
		case SKIP:
			$("#result-" + id).addClass("has-skip");
	}
	$("#err_" + id).html(message);
}

function ajaxReady(period){

	$.ajax({
		type: 'post',
		url: 'setReady',
		data: {
			year: $('#year').val(),
			month: $('#month').val()
		},
		error: function(){
			alert('No se pudo actualizar el periodo');
		}
	}).done(function (response){
		if (response != 1)
			alert('No se pudo actualizar el periodo');
	});
}

function ajaxEmail(page){
	var ready = $('#periodo_ready').val();
	if(page=="salary")
	{
		atr = "sal";
		mod = "Remuneraciones";
	}
	else if(page=="promotionalTime")
	{
		atr = "tp";
		mod = "Tiempos Promocionales";
	}
	else
	{
		atr = "pub";
		mod = "Publicidad y Cmg3";
	}
	
	if(ready==0)
	{
		if(page!="configuration")
		{
			bootbox.confirm("¿Dese enviar email para confirmar la carga de datos en "+mod+"?", function(result) {
				if(result)
				{
					$.ajax({
						type: 'post',
						url: atr+'/sendEmail',
						data: {
							year: $('#year').val(),
							month: $('#month').val(),
							ready: ready
						},
						success: function(response){
							// console.log(response);
						},
						error: function(){
							alert('No se pudo actualizar el periodo');
						}
					})
				}	
			});
		}	
	}
	else
	{
		if(page!="configuration")
		{
			$.ajax({
				type: 'post',
				url: atr+'/sendEmail',
				data: {
					year: $('#year').val(),
					month: $('#month').val(),
					ready: ready
				},
				success: function(response){
					// console.log(response);
				},
				error: function(){
					alert('No se pudo actualizar el periodo');
				}
			})
			bootbox.alert("Se ha enviado un correo para informar las modificaciones en "+mod+'.');	
		}
	}
}

function updateProgressBar(id){
	var numItems = $('.has-feedback').size() - $('.has-skip').size();
	var numProcess = $('.has-success').size() + $('.has-info').size() + $('.has-error').size() + $('.has-warning').size();
	var numSucces = $('.has-success').size() + $('.has-info').size();
	var numError = 0;
	var processed = numProcess + numError;
	
	if (processed >= numItems / 2){
		$(".progress > span").css('color', 'white');
	}
	
	width = (numError * 100) / numItems;
	$(".progress .progress-bar-danger").css('width', width + '%');
	
	width = (numProcess * 100) / numItems;
	
	if (processed >= numItems / 2){
		$(".progress > span").css('color', 'white');
		if (processed >= numItems){
			$(".progress").removeClass('active');
			$("#btnProcess").removeAttr('disabled');
		}
	}
	if(id=='inputs' && (numSucces == numItems))
	{
		ajaxReady();
	}
	if(id!='inputs' && (numSucces == numItems) && numSucces>0)
	{
		ajaxEmail(id);
	}
	
	$(".progress > span").html(numProcess + '/' + numItems);
	
	$(".progress .progress-bar-tot").css('width', width + '%');
}

function prepareData(page, array){
	var form = {};
	form['files'] = {};
	form['inputs'] = {};
	if(page!="inputs")
	{
		$.each(array, function(key, value){
			var element = document.getElementById(value);
			if (element.files != null){
				form['files'][value] = element.files;
			}else{
				if (element.value != ''){
					form['inputs'][value] = element.value;
				}
			}
		});
		$.ajax({
			url: page + '/getResults',
			type: 'post',
			data: {
				year: $('#year').val(),
				month: $('#month').val()
			},
			success: function(data, textStatus, jqXHR){
				$('#content-results').html(data);
				$('#principal').scrollTop(999999999);
				processForm(page, array, form);
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log('ERROR: ' + textStatus);
				alert('Hubo un error al procesar, intentelo de nuevo');
			}
		});
	}
	else
	{
		$.ajax({
			url: page + '/getResults',
			type: 'post',
			data: {
				year: $('#year').val(),
				month: $('#month').val()
			},
			success: function(data, textStatus, jqXHR){
				$('#content-results').html(data);
				$('#principal').scrollTop(999999999);
				var name = document.getElementsByName('inputs');
				var input = [];
				for (var i=0; i<name.length;i++){
					input[i] = name[i].value;
				}
				processForm(page, input);
			},
			error: function(jqXHR, textStatus, errorThrown){
				console.log('ERROR: ' + textStatus);
				alert('Hubo un error al procesar, intentelo de nuevo');
			}
		});
	}
}

function processForm(page, inputs, data){
	var o = data;
	$.each(inputs, function(k, input){
		$("#result-" + input).click(function (e){
			e.preventDefault();
			if ($("#err_" + input).html() != ''){
				if ($("#err_" + input).css('display') == 'block'){
					$("#err_" + input).css('display', 'none');
				}else{
					$("#err_" + input).css('display', 'block');
				}
			}
		});
		if(o === undefined)
		{
			var l = Ladda.create(document.getElementById('btn_' + input));
			var data = new FormData();
			data.append('year', $('#year').val());
			data.append('month', $('#month').val());
			data.append('input', input);
			ajaxInput(page, input, data, l);
		}
		else
		{
			$.each(o['files'], function (key, value){
				if (key == input){
					if (value.length > 0){
						var l = Ladda.create(document.getElementById('btn_' + input));
						var data = new FormData();
						data.append('year', $('#year').val());
						data.append('month', $('#month').val());
						data.append('file', value[0]);
						ajaxProcess(page, input, data, l);
					}else{
						showResult(input, SKIP, 'No se ingreso ningun archivo');
						updateProgressBar(page);
					}
				}
			});
			$.each(o['inputs'], function (key, value){
				if (key == input){
					var l = Ladda.create(document.getElementById('btn_' + input));
					var data = new FormData();
					data.append('year', $('#year').val());
					data.append('month', $('#month').val());
					data.append(input, value);
					ajaxProcess(page, input, data, l);
				}
			});
		}

	});
}

function ajaxInput(page, value, data, ladda){	
	$.ajax({
		url: page + '/getInput',
		type: 'post',
		data: data,
		contentType: false,
		processData: false,
		cache: false,
		dataType: 'json',
		beforeSend: function (){
			ladda.start();
		},
		success: function(data, textStatus, jqXHR){
			if (typeof data.error === 'undefined'){
				if (typeof data.warning === 'undefined'){
					if (typeof data.info === 'undefined'){
						showResult(value, SUCCESS);
					}else{
						showResult(value, INFO, data.info);
					}
				}else{
					showResult(value, WARNING, data.warning);
				}
			}else{
				showResult(value, ERROR, data.error);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			showResult(value, ERROR, 'Hubo un error al procesar, intentelo de nuevo');
			console.log('ERROR: ' + textStatus);
		},
		complete: function(){
			updateProgressBar(page);
			ladda.stop();
		}
	});
}

function ajaxProcess(page, value, data, ladda){	
	$.ajax({
		url: page + '/' + value,
		type: 'post',
		data: data,
		contentType: false,
		processData: false,
		cache: false,
		dataType: 'json',
		beforeSend: function (){
			ladda.start();
		},
		success: function(data, textStatus, jqXHR){
			if (typeof data.error === 'undefined'){
				if (typeof data.warning === 'undefined'){
					if (typeof data.info === 'undefined'){
						showResult(value, SUCCESS);
					}else{
						showResult(value, INFO, data.info);
					}
				}else{
					showResult(value, WARNING, data.warning);
				}
			}else{
				showResult(value, ERROR, data.error);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			showResult(value, ERROR, 'Hubo un error al procesar, intentelo de nuevo');
			console.log('ERROR: ' + textStatus);
		},
		complete: function(){
			updateProgressBar(page);
			ladda.stop();
		}
	});
}

function validateConfiguration(foa_ptv,por){
	if (isValidDate($("#month").val(), $("#year").val())){
		var v = $('#slc-p300').val();
		var period;
		//registro de los foalias seleccionados
		$('.option-des-1').each(function(index){
			foa_ptv[index] = $(this).val();
		});
		//Verificación de los campos duplicados
		var positions = [];
		var dup = 0;
		foa_ptv.forEach(function(entry){
		    positions = foa_ptv.indicesOf(entry);  
		    if(positions.length>1)
		    {
		    	for(var i=0;i<=positions.length;i++)
		    	{
		    		if(i>0)
		    		{
		    			$('#list-publi>li>select').eq(positions[i]).addClass('error');
		    			$('.repeat').show();		
		    			dup = 1;
		    		}	
		    	}
		    }	
		});
		var val  = 0;
		var sum  = 0; 
		var msg0 = "";
		var msgn = "";
		var error_por;
		$('.porcentaje').each(function(index){
			val = parseInt($(this).val());
			if(val == 0)
			{
				msg0=", no puede registrar 0";
				error_por = 1;
				sum = 0;
				// $(this).focus();
				// return false;
			}
			else if(val < 0)
			{
				msgn=", no puede registrar negativos";
				error_por = 1;
				sum = 0;
				// $(this).focus();
				// return false;
			}
			else
			{
				por[index]=val;
				sum = sum + val;
			}
		});
		if(sum!=100 || error_por==1)
		{
			$('.porcentaje_error').css({"border-left":"2px solid #4B4848","border-right":"2px solid #4B4848"});
			$('#list-publi>li:first-child .porcentaje_error').css({
				"border-top":"2px solid #4B4848"
			});
			$("#list-publi>li:last-child .porcentaje_error").css({
				"border-bottom": "2px solid #4B4848"
			});
			$("#list-publi>li:last-child #tooltip1").tooltip({'trigger': 'manual', 'title': 'La suma no es 100%'+msgn+msg0+'.'}).tooltip('show');	
		}
		else if(sum==100 && dup==1 || sum!=100 && dup==0)
		{
			return false;
		} 
		else
		{
			var temp = {};
			temp["foalias"] = foa_ptv;
			temp["share"] = por;
			var jsonPubTv = JSON.stringify(temp);
			$("#pubTv").val(jsonPubTv);
			$("#p300").val($('#slc-p300').val());
			return true;
		}
	}else{
		alert('Fecha no válida');
		return false;
	}
}

function unloadPage(){
	return "Las canelas estan siendo modificadas";
}

$(window).click(function (e){
	if ($('#month').attr('page') == 'canela'){
		if ($(".txtCanela").css('display') == 'block'){
			window.onbeforeunload = unloadPage;
		}else{
			window.onbeforeunload = null;
		}
	}
});

// <script type="text/javascript">
// 			paceOptions={
// 				startOnPageLoad: false,
// 			}
// 		</script>
// var activeRequests = 0;

// $(document).ajaxStart(function(){
// if (activeRequests == 0)
// Pace.restart(); // stop

// activeRequests++;
// });
// $(document).ajaxStop(function(){
// activeRequests--;

// if (activeRequests == 0)
// Pace.stop(); // show progress indicator
// });

$().ready(function(){

    $("#btn-add").on('click',function(){
        //console.log('hola');
        $('#listprod>li:first-child').clone(true,true).appendTo('#listprod');
    });

	var promotionalTimes = ['prod', 'merch', 'sam', 'contra', 'bode'];
	var salaries = ['remun'];
	var parameters = ['pubTv', 'cmg3', 'p300'];
	var date_options = {
		format: "mm/yyyy",
		startDate: $('#startDate').val(),
		endDate: $('#endDate').val(),
		minViewMode: 1,
		language: "es",
		autoclose: true
	};
	var spinner_options = {
		lines: 17, // The number of lines to draw
		length: 6, // The length of each line
		width: 18, // The line thickness
		radius: 50, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		direction: 1, // 1: clockwise, -1: counterclockwise
		color: '#a15ea1', // #rgb or #rrggbb or array of colors
		speed: 1, // Rounds per second
		trail: 48, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: true, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left: 'auto' // Left position relative to parent in px
	};
	
	$(".date").datepicker(date_options).on('changeDate', function (e){
		$(this).tooltip('hide');
		$(".date").removeClass('has-error');
		if (isNaN(e.date)){
			$("#year").val('');
			$("#month").val('');
		}else{
			$("#year").val(e.format('yyyy'));
			$("#month").val(parseInt(e.format('mm')));
		}
		//evento obligatorio para configuración
		$("#month").change();
	});

	$(".date input[type=text]").change(function (){
		if ($(this).val() == ''){
			$("#year").val('');
			$("#month").val('');
		}
	});

	$(document).on('click', '#btnProcess', function(e){
		e.preventDefault();
		var foa_ptv = [];
		var por  = [];
		if ($("#year").val() == '' || $("#month").val() == ''){
			$(".date").addClass('has-error');
			$(".date").tooltip('show');
			return false;
		}
		$(this).attr('disabled', true);
		var page = $(this).attr('page');
		var res;
		switch(page){
			case 'promotionalTime':
				prepareData(page, promotionalTimes);
				break;
			case 'salary':
				prepareData(page, salaries);
				break;
			case 'configuration':
				if(validateConfiguration(foa_ptv, por))
				{
					prepareData(page, parameters);
				}else{
					$(this).removeAttr('disabled');
				}
				break;
			case 'inputs':
				prepareData(page);
				break;
		}
	});
	
	$('#month').change(function (){
		if ($(this).attr('page') == 'report'){
			if ($("#month").val() == ''){
				$('#btnExport').css('display', 'none');
				$('#content-report').html('');
			}else{
				$.ajax({
					type: 'post',
					url: 'showReport',
					data: {
						year: $("#year").val(),
						month: $("#month").val(),
						manager: $("#manager").val()
					},
					beforeSend: function(){
						$('#content-report').html('<div id="loader" style="width: 0px; margin:auto; margin-top: 100px;"></div>');
						var target = document.getElementById('loader');
						var spinner = new Spinner(spinner_options).spin(target);
					}
				}).done(function (response){
					$('#content-report').html(response);
					if ($('.alert').length != 1){
						$('#btnExport').css('display', 'block');
						$('.divider').css('display', 'block');
						$('#lock').css('display', 'block');
						if (response.indexOf('bloqueado') != -1){
							$('.divider').css('display', 'none');
							$('#lock').css('display', 'none');
						}
						$('#testXLSX').attr('href', 'export/' + $("#year").val() + '/' + $("#month").val() + '/' + $("#manager").val());
					}
				});
			}
		} else {
			if (typeof $(this).attr('page') == 'undefined'){
				if ($("#month").val() == ''){
					$('#content-form').html('');
				}else{
					$.ajax({
						type: 'post',
						url: window.location + '/validatePeriod',
						data:{
							year: $("#year").val(),
							month: $("#month").val()
						},
						beforeSend:function (){
							$("#content-form").html('<div id="loader" style="width: 0px; margin:auto; margin-top: 100px;"></div>');
							var target = document.getElementById ('loader');
							new Spinner(spinner_options).spin(target);
						}
					}).done(function (response){
						$("#content-form").html(response);
					});
				}
			}
		}
	});
	
	$.each(promotionalTimes, function (index, value){
		$(document).on('click', "#file-" + value + " button", function (){
			$("#file-" + value + " input[type=file]").click();
		});
		
		$(document).on('change', "#file-" + value + " input[type=file]", function (){
			var arr = this.files[0].name.split('.');
			if (arr[1].toLowerCase() != 'xls' && arr[1].toLowerCase() != 'xlsx'){
				$("#file-" + value + " input[type=file]").val('');
				$("#file-" + value + " input[type=text]").val('');
				alert('Debe seleccionar un archivo de excel');
			}else{
				$("#file-" + value + " input[type=text]").val(arr[0]);
			}
		});
	});
	
	$.each(salaries, function (index, value){
		$(document).on('click', "#file-" + value + " button", function (){
			$("#file-" + value + " input[type=file]").click();
		});
		
		$(document).on('change', "#file-" + value + " input[type=file]", function (){
			var arr = this.files[0].name.split('.');
			if (arr[1].toLowerCase() != 'dbf'){
				$("#file-" + value + " input[type=file]").val('');
				$("#file-" + value + " input[type=text]").val('');
				alert('Debe seleccionar un archivo dbf');
			}else{
				$("#file-" + value + " input[type=text]").val(arr[0]);
			}
		});
	});
	
	$(document).on('click', "#file-cmg3 button", function (){
		$("#file-cmg3 input[type=file]").click();
	});
	
	$(document).on('change', "#file-cmg3 input[type=file]", function (){
		var arr = this.files[0].name.split('.');
		if (arr[1].toLowerCase() != 'xls' && arr[1].toLowerCase() != 'xlsx'){
			$("#file-cmg3 input[type=file]").val('');
			$("#file-cmg3 input[type=text]").val('');
			alert('Debe seleccionar un archivo de Excel');
		}else{
			$("#file-cmg3 input[type=text]").val(arr[0]);
		}
	});

	$(document).on('click', "#file-estimate button", function (){
		$("#file-estimate input[type=file]").click();
	});
	
	$(document).on('change', "#file-estimate input[type=file]", function (){
		var arr = this.files[0].name.split('.');
		if (arr[1].toLowerCase() != 'xls' && arr[1].toLowerCase() != 'xlsx'){
			$("#file-estimate input[type=file]").val('');
			$("#file-estimate input[type=text]").val('');
			$("#message").html('Debe seleccionar un archivo de Excel');
		}else{
			$("#message").html("");
			$("#file-estimate input[type=text]").val(arr[0]);
		}
	});

	
	
	$("#save").on("click",function(){
		$("#message").html("");	
		if($("#file-estimate input[type=text]").val())
		{
			$("#lada").show();
			var data = new FormData();
				data.append('year', $('#year').val());
				data.append('month', $('#month').val());
				data.append('file', document.getElementById('estimate').files[0]);
			var l = Ladda.create(document.getElementById('lada'));
			l.start();
			$(".close").css("cursor:no-drop");
			$(".close").css("pointer-events","auto");
			$(".close").attr("disabled","disabled");
			$("#save").css("cursor:no-drop");
			$("#save").css("pointer-events","auto");
			$("#save").attr("disabled","disabled");
			$("#cancel").css("cursor","no-drop");
			$("#cancel").css("pointer-events","auto");
			$("#cancel").attr("disabled","disabled");
			$.ajax({
				contentType: false,
				processData: false,
				cache: false,
				type: 'post',
				url: 'migration',
				data: data,
				complete: function(response) {
					l.stop();
					$("#save").removeAttr("disabled");
					$("#save").css("cursor","auto");
					$("#cancel").removeAttr("disabled");
					$("#cancel").css("cursor","auto");
					$(".close").removeAttr("disabled");
					$(".close").css("cursor","pointer");
					$("#lada").hide();
					$('#myModal').modal({
					  backdrop: true
					});
				},
				success: function(response){
					if(response=="OK")
					{
						$("#cancel").html("Aceptar");
						$("#message").html("Se migró el test de producto satisfactoriamente");
						$("#save").remove();
						$("#cancel").on("click",function(){
							$('.divider').css('display', 'none');
							$('#lock').css('display', 'none');
						});
					}
					else
					{
						$("#message").html(response);
					}
				},
				error: function(){
					$("#message").html('Verifique que el formato del archivo es el adecuado.');
				}
			});
		}
		else
		{
			$("#message").html('Debe seleccionar un archivo Excel');
		}
	});
	
	$("#manager").change(function (){
		$("#month").change();
	});

});