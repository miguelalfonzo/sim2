<?php

class RucController extends BaseController{

    public function show(){
		$rucClass = new RUC;
		$data = $rucClass->consultarRUC('2016064181');
        var_dump($data);
    }
}