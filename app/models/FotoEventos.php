<?php

class FotoEventos extends Eloquent
{
    protected $table= 'FILE_STORAGE';

    public function event(){
    	return $this->belongsTo('Event\Event', 'event_id', 'id');
    }
}