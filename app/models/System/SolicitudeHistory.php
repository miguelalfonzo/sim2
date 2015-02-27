<?php

namespace System;

use \Eloquent;

class SolicitudeHistory extends Eloquent{
	protected $table = 'DMKT_RG_SOLICITUD_HISTORIAL';
	protected $primaryKey = 'id';

	public function lastId(){
		$lastId = SolicitudeHistory::orderBy('id','desc')->first();
		if($lastId == null){
            return 0;
        }else{
            return $lastId->id;
        }
	}
}