<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf8">
		<title>CONSULTA RUC</title>
	</head>
	<body>
		<?php
			$rucConsultado='10461782863';
			// $rucConsultado= '20160641810';
			$url = 'http://www.sunat.gob.pe/w/wapS01Alias?ruc='.$rucConsultado;
			$proxy = 'proxy.bagoperu.com.pe:3128';
			$proxyauth = 'outinf01:uy349asx';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			$sunat = curl_exec($ch);
			curl_close($ch);

			$busqueda = '<card title="Resultado" id="frstcard">';
			$existe = strpos($sunat, $busqueda);
			preg_match_all("(<small>(.*?)</small>)", $sunat, $salida, PREG_PATTERN_ORDER);
			
			$i = 0;
			if (preg_match("(Ruc. </b>(.*?)-)", $salida[1][$i], $ruc) == 0){
				echo $salida[1][$i];
			}else{
				$ruc = trim($ruc[1]);
				preg_match("(-(.*?)<br/>)", $salida[1][$i++], $razonSocial);
				$razonSocial = trim($razonSocial[1]);
				$rucAntiguo = ($salida[1][++$i] == '-')?'':trim($salida[1][$i]);
				$i++;
				preg_match("(</b>(.*))", $salida[1][$i++], $estado);
				$estado = trim($estado[1]);
				if (preg_match("(Nombre Comercial)", $salida[1][$i]) == 0){
					$exc = trim($salida[1][$i++]);
					preg_match("(<br/>(.*))", $salida[1][$i++], $nomComercial);
					$nomComercial = trim($nomComercial[1]) == '-'? '': trim($nomComercial[1]);
				}else{
					$exc = '';
					preg_match("(<br/>(.*))", $salida[1][$i++], $nomComercial);
					$nomComercial = trim($nomComercial[1]) == '-'? '': trim($nomComercial[1]);
				}
				preg_match("(<br/>(.*))", $salida[1][$i++], $direccion);
				$direccion = trim($direccion[1]);
				preg_match("(<b>(.*)</b>)", $salida[1][$i++], $situacion);
				$situacion = trim($situacion[1]);
				preg_match("(<br/>(.*))", $salida[1][$i++], $telefonos);
				$telefonos = trim($telefonos[1]) == '-'? '': trim($telefonos[1]);
				preg_match("(<br/>(.*))", $salida[1][$i++], $dependencia);
				$dependencia = trim($dependencia[1]);
				preg_match("(<br/>(.*))", $salida[1][$i++], $tipo);
				$tipo = trim($tipo[1]);
				
				print_r('Datos RUC');
				var_dump('RUC: '.$ruc);
				var_dump('Razon Social: '.$razonSocial);
				var_dump('Ruc anterior: '.$rucAntiguo);
				var_dump('Estado: '.$estado);
				var_dump('Observacion: '.$exc);
				var_dump('Nombre Comercial: '.$nomComercial);
				var_dump('Direccion: '.$direccion);
				var_dump('Situacion: '.$situacion);
				var_dump('Telefonos: '.$telefonos);
				var_dump('Dependencia: '.$dependencia);
				var_dump('Tipo: '.$tipo);
			}
		?>
	</body>
</html>