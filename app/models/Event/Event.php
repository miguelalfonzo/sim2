<?php

namespace Event;

use \Eloquent;
use \FotoEventos;

class Event extends Eloquent 
{
    protected $table = 'EVENT';
    protected $primaryKey = 'ID';
    protected $fillable = array('id', 'name', 'description', 'place', 'event_date', 'solicitud_id');

    function searchId()
    {
        $lastId = Event::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }
    
    public function photos(){
        //return $this->hasMany('FotoEventos', 'event_id', 'id');
        $photos = FotoEventos::where('event_id', $this->id)->get();
        if(count($photos)>0){
            return $photos;
        }
        return array();
    }
    
}