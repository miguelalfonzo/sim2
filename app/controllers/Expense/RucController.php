<?php

class RucController extends BaseController{

    public function show($rucConsultado = ''){
		$rucClass = new RUC;
		$data = $rucClass->consultarRUC($rucConsultado);
        var_dump($data);
    }
}