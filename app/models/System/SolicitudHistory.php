<?php

namespace System;

use \Eloquent;

class SolicitudHistory extends Eloquent{
	
    protected $table = 'SOLICITUD_HISTORIAL';
    protected $fillable = array( 'user_from' , 'id_solicitud' , 'status_to');
	protected $primaryKey = 'id';
    
    protected function getUpdatedAtAttribute( $attr )
    {
        return \Carbon\Carbon::parse( $attr )->format('Y-m-d H:i');
    }

	public function lastId()
    {
		$lastId = SolicitudHistory::orderBy('id','desc')->first();
		if( $lastId == null )
            return 0;
        else
            return $lastId->id;
	}

	public function lastState() {
        return $this->belongsTo('Dmkt/Solicitud');
    }

    public function user(){
    	return $this->hasOne('User','id','created_by');
    }

    public function createdBy()
    {
        return $this->hasOne( 'User' , 'id' , 'created_by' );
    }

    protected function updatedBy()
    {
        return $this->hasOne('User','id','updated_by');
    }

    public function toState()
    {
        return $this->hasOne( 'Common\State' , 'id' , 'status_to' );
    }

    public function statusFrom(){
        return $this->hasOne( 'Common\State' , 'id' , 'status_from' );
    }

    protected function fromUserType()
    {
        return $this->hasOne( 'Common\TypeUser' , 'codigo' , 'user_from' );
    }
}