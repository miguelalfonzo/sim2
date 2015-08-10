<?php

class FotoEventos extends Eloquent
{
    protected $table= TB_EVENTO_FOTO;

    public function event(){
    	return $this->belongsTo('Event\Event', 'event_id', 'id');
    }
}