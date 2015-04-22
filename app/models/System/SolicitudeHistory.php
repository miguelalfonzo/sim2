<?php

namespace System;

use \Eloquent;

class SolicitudeHistory extends Eloquent{
	
    protected $table = 'DMKT_RG_SOLICITUD_HISTORIAL';
    protected $fillable = array('id','status_to');
	protected $primaryKey = 'id';
    public $timestamps = true;

    protected function getUpdatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d h:i');
    }


	public function lastId()
    {
		$lastId = SolicitudeHistory::orderBy('id','desc')->first();
		if($lastId == null)
            return 0;
        else
            return $lastId->id;
	}

	public function lastState() {
        return $this->belongsTo('Dmkt/Solicitude');
    }

    public function user(){
    	return $this->hasOne('User','id','created_by');
    }

    protected function updatedBy()
    {
        return $this->hasOne('User','id','updated_by');
    }
}