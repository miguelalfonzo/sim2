function eventChange(position){
	$("#canela" + position).change(function (){
		var linea = $(this).attr('linea');
		var canelaAnt = parseFloat($("#canelaAnt" + position).val());
		var canela = $(this).val() == ''? 0: parseFloat($(this).val());
		var cant = parseFloat((replaceAll($("#cant" + position).text(),',','')).replace('(', '-').replace(')',''));
		var ppto_cant = parseFloat((replaceAll($("#ppto_cant" + position).text(),',','')).replace('(', '-').replace(')',''));
		var ppto_prec_unit = parseFloat((replaceAll($("#ppto_prec_unit" + position).text(),',','')).replace('(', '-').replace(')',''));
		var neto = parseFloat((replaceAll($("#valor" + position).text(),',','')).replace('(', '-').replace(')','')) - canela;
		var prec_unit = cant != 0? (neto / cant): 0;
		var var_x_precAnt = parseFloat((replaceAll($("#var_x_prec" + position).text(),',','')).replace('(', '-').replace(')',''));
		var var_x_prec = ppto_cant * (prec_unit - ppto_prec_unit);
		var var_x_volAnt = parseFloat((replaceAll($("#var_x_vol" + position).text(),',','')).replace('(', '-').replace(')',''));
		var var_x_vol = prec_unit * (cant - ppto_cant);
		var var_total = var_x_prec + var_x_vol;
		var canelaLin = parseFloat((replaceAll($("#lblCanelaT" + linea).text(),',','')).replace('(', '-').replace(')','')) - canelaAnt + canela;
		var canelaTotal = parseFloat((replaceAll($("#table-canela tr:last #canela").text(),',','')).replace('(', '-').replace(')','')) - canelaAnt + canela;
		if (neto < 0){
			$("#neto" + position).text('(' + commaSeparateNumber((neto * -1).toFixed(2)) + ')');
			$("#neto" + position).css('color', 'red');
		}else{
			$("#neto" + position).text(commaSeparateNumber(neto.toFixed(2)));
			$("#neto" + position).css('color', 'auto');
		}
		if (prec_unit < 0){
			$("#prec_unit" + position).text('(' + commaSeparateNumber((prec_unit * -1).toFixed(2)) + ')');
			$("#prec_unit" + position).css('color', 'red');
		}else{
			$("#prec_unit" + position).text(commaSeparateNumber(prec_unit.toFixed(2)));
			$("#prec_unit" + position).css('color', 'auto');
		}
		if (var_x_prec < 0){
			$("#var_x_prec" + position).text('(' + commaSeparateNumber((var_x_prec * -1).toFixed(2)) + ')');
			$("#var_x_prec" + position).css('color', 'red');
		}else{
			$("#var_x_prec" + position).text(commaSeparateNumber(var_x_prec.toFixed(2)));
			$("#var_x_prec" + position).css('color', 'auto');
		}
		if (var_x_vol < 0){
			$("#var_x_vol" + position).text('(' + commaSeparateNumber((var_x_vol * -1).toFixed(2)) + ')');
			$("#var_x_vol" + position).css('color', 'red');
		}else{
			$("#var_x_vol" + position).text(commaSeparateNumber(var_x_vol.toFixed(2)));
			$("#var_x_vol" + position).css('color', 'auto');
		}
		if (var_total < 0){
			$("#var_total" + position).text('(' + commaSeparateNumber((var_total * -1).toFixed(2)) + ')');
			$("#var_total" + position).css('color', 'red');
			$("#var_neg" + position).text('(' + commaSeparateNumber((var_total * -1).toFixed(2)) + ')');
			$("#var_neg" + position).css('color', 'red');
		}else{
			$("#var_total" + position).text(commaSeparateNumber(var_total.toFixed(2)));
			$("#var_total" + position).css('color', 'auto');
			$("#var_neg" + position).text('');
			$("#var_neg" + position).css('color', 'auto');
		}
		if (arr.indexOf(position) == -1)
			arr.push(position);
		
		var posLin = $("#lblCanelaT" + linea).attr('pos');
		var neto = parseFloat((replaceAll($("#valor" + posLin).text(),',','')).replace('(', '-').replace(')','')) - canelaLin;
		var var_x_prec = parseFloat((replaceAll($("#var_x_prec" + posLin).text(),',','')).replace('(', '-').replace(')','')) - var_x_precAnt + var_x_prec;
		var var_x_vol = parseFloat((replaceAll($("#var_x_vol" + posLin).text(),',','')).replace('(', '-').replace(')','')) - var_x_volAnt + var_x_vol;
		var var_total = var_x_prec + var_x_vol;
		
		$("#canelaAnt" + position).val(canela);
		$("#var_x_volAnt" + position).val(var_x_vol);
		
		if (canelaLin < 0){
			$("#lblCanelaT" + linea).text('(' + commaSeparateNumber((canelaLin * -1).toFixed(2)) + ')');
			$("#lblCanelaT" + linea).css('color', 'red');
		}else{
			$("#lblCanelaT" + linea).text(commaSeparateNumber(canelaLin.toFixed(2)));
			$("#lblCanelaT" + linea).css('color', 'auto');
		}
		if (neto < 0){
			$("#neto" + posLin).text('(' + commaSeparateNumber((neto * -1).toFixed(2)) + ')');
			$("#neto" + posLin).css('color', 'red');
		}else{
			$("#neto" + posLin).text(commaSeparateNumber(neto.toFixed(2)));
			$("#neto" + posLin).css('color', 'auto');
		}
		if (var_x_prec < 0){
			$("#var_x_prec" + posLin).text('(' + commaSeparateNumber((var_x_prec * -1).toFixed(2)) + ')');
			$("#var_x_prec" + posLin).css('color', 'red');
		}else{
			$("#var_x_prec" + posLin).text(commaSeparateNumber(var_x_prec.toFixed(2)));
			$("#var_x_prec" + posLin).css('color', 'auto');
		}
		if (var_x_vol < 0){
			$("#var_x_vol" + posLin).text('(' + commaSeparateNumber((var_x_vol * -1).toFixed(2)) + ')');
			$("#var_x_vol" + posLin).css('color', 'red');
		}else{
			$("#var_x_vol" + posLin).text(commaSeparateNumber(var_x_vol.toFixed(2)));
			$("#var_x_vol" + posLin).css('color', 'auto');
		}
		if (var_total < 0){
			$("#var_total" + posLin).text('(' + commaSeparateNumber((var_total * -1).toFixed(2)) + ')');
			$("#var_total" + posLin).css('color', 'red');
			$("#var_neg" + posLin).text('(' + commaSeparateNumber((var_total * -1).toFixed(2)) + ')');
			$("#var_neg" + posLin).css('color', 'red');
		}else{
			$("#var_total" + posLin).text(commaSeparateNumber(var_total.toFixed(2)));
			$("#var_total" + posLin).css('color', 'auto');
			$("#var_neg" + posLin).text('');
			$("#var_neg" + posLin).css('color', 'auto');
		}
		
		var neto = parseFloat((replaceAll($("#table-canela tr:last #valor").text(),',','')).replace('(', '-').replace(')','')) - canelaLin;
		var var_x_prec = parseFloat((replaceAll($("#table-canela tr:last #var_x_prec").text(),',','')).replace('(', '-').replace(')','')) - var_x_precAnt + var_x_prec;
		var var_x_vol = parseFloat((replaceAll($("#table-canela tr:last #var_x_vol").text(),',','')).replace('(', '-').replace(')','')) - var_x_volAnt + var_x_vol;
		var var_total = var_x_prec + var_x_vol;
		
		if (canelaTotal < 0){
			$("#table-canela tr:last #canela").text('(' + commaSeparateNumber((canelaTotal * -1).toFixed(2)) + ')');
			$("#table-canela tr:last #canela").css('color', 'red');
		}else{
			$("#table-canela tr:last #canela").text(commaSeparateNumber(canelaTotal.toFixed(2)));
			$("#table-canela tr:last #canela").css('color', 'auto');
		}
		if (neto < 0){
			$("#table-canela tr:last #neto").text('(' + commaSeparateNumber((neto * -1).toFixed(2)) + ')');
			$("#table-canela tr:last #neto").css('color', 'red');
		}else{
			$("#table-canela tr:last #neto").text(commaSeparateNumber(neto.toFixed(2)));
			$("#table-canela tr:last #neto").css('color', 'auto');
		}
		if (var_x_prec < 0){
			$("#table-canela tr:last #var_x_prec").text('(' + commaSeparateNumber((var_x_prec * -1).toFixed(2)) + ')');
			$("#table-canela tr:last #var_x_prec").css('color', 'red');
		}else{
			$("#table-canela tr:last #var_x_prec").text(commaSeparateNumber(var_x_prec.toFixed(2)));
			$("#table-canela tr:last #var_x_prec").css('color', 'auto');
		}
		if (var_x_vol < 0){
			$("#table-canela tr:last #var_x_vol").text('(' + commaSeparateNumber((var_x_vol * -1).toFixed(2)) + ')');
			$("#table-canela tr:last #var_x_vol").css('color', 'red');
		}else{
			$("#table-canela tr:last #var_x_vol").text(commaSeparateNumber(var_x_vol.toFixed(2)));
			$("#table-canela tr:last #var_x_vol").css('color', 'auto');
		}
		if (var_total < 0){
			$("#table-canela tr:last #var_x_total").text('(' + commaSeparateNumber((var_total * -1).toFixed(2)) + ')');
			$("#table-canela tr:last #var_x_total").css('color', 'red');
			$("#table-canela tr:last #var_neg").text('(' + commaSeparateNumber((var_total * -1).toFixed(2)) + ')');
			$("#table-canela tr:last #var_neg").css('color', 'red');
		}else{
			$("#table-canela tr:last #var_x_total").text(commaSeparateNumber(var_total.toFixed(2)));
			$("#table-canela tr:last #var_x_total").css('color', 'auto');
			$("#table-canela tr:last #var_neg").text('');
			$("#table-canela tr:last #var_neg").css('color', 'auto');
		}
	});
}

function ajaxCanela(){
	$.ajax({
		type: 'post',
		url: 'showCanela',
		data: {
			year: $("#year").val(),
			month: $("#month").val(),
			type: $("#type").val()
		},
		beforeSend: function(){
			var opts = {
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
			$('#content-canela').html('<div id="loader" style="width: 0px; margin:auto; margin-top: 100px;"></div>');
			var target = document.getElementById('loader');
			var spinner = new Spinner(opts).spin(target);
		}
	}).done(function (response){
		$('#content-canela').html(response);
		if (response.indexOf('Error') == -1){
			$('#btnModify').css('display', 'block');
			$("#separator").css('display', 'block');
			$('#btnExport').css('display', 'block');
			$('#canelaDBF').attr('href', 'canela/dbf/' + $("#year").val() + '/' + $("#month").val() + '/' + $("#type").val());
			$('#canelaXLSX').attr('href', 'canela/xlsx/' + $("#year").val() + '/' + $("#month").val() + '/' + $("#type").val());
		}
	});
}

var arr;

$().ready(function(){
	$("#btnSave").click(function (e){
		e.preventDefault();
		var canelas = new Array();
		
		for (i = 0; i < arr.length; i++){
			canelas[i] = new Array();
			canelas[i][0] = $("#codigo" + arr[i]).val();
			canelas[i][1] = $("#canela" + arr[i]).val();
		}
		
		var l = Ladda.create(document.getElementById('btnSave'));
		l.start();
		$("#btnSave").addClass('btn-primary');
		$.ajax({
			type: 'post',
			url: 'saveCanela',
			data: {
				canelas: canelas,
				year: $("#year").val(),
				month: $("#month").val(),
				type: $("#type").val()
			}
		}).done(function (response){
			l.stop();
			$("#btnSave").removeClass('btn-primary');
			$("#btnCancel").css('display', 'none');
			$("#btnSave").css('display', 'none');
			$('#content-canela').html(response);
			$('#btnModify').css('display', 'block');
			$("#separator").css('display', 'block');
			$('#btnExport').css('display', 'block');
		});
	});
	
	$("#btnModify").click(function (e){
		e.preventDefault();
		$(".lblCanela").css('display', 'none');
		$(".txtCanela").css('display', 'block');
		$("#btnCancel").css('display', 'block');
		$("#btnModify").css('display', 'none');
		$('#btnExport').css('display', 'none');
		$("#btnSave").css('display', 'block');
		$("#separator").css('display', 'block');
		arr = [];
	});
	
	$("#btnCancel").click(function (e){
		e.preventDefault();
		$("#btnCancel").css('display', 'none');
		$("#btnSave").css('display', 'none');
		ajaxCanela();
	});
	
	$(window).resize(function(){
		$("#table-canela").css('height', $(window).height()-240)
	});
	
	$('#month').change(function (){
		if ($(this).attr('page') == 'canela'){
			$('#btnExport').css('display', 'none');
			$('#btnModify').css('display', 'none');
			$("#btnSave").css('display', 'none');
			$("#btnCancel").css('display', 'none');
			if ($("#month").val() == ''){
				$('#content-canela').html('');
			}else{
				ajaxCanela();
			}
		}
	});
	
	$("#type").change(function (){
		$('#btnExport').css('display', 'none');
		$('#btnModify').css('display', 'none');
		$("#btnSave").css('display', 'none');
		$("#btnCancel").css('display', 'none');
		if ($("#year").val() != '' && $("#month").val() != ''){
			ajaxCanela();
		}
	});
});