<?php

namespace Event;

use \Eloquent;

class Event extends Eloquent 
{
    protected $table = 'SIM_EVENT';
    protected $primaryKey = 'ID';

    function searchId()
    {
        $lastId = Event::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }
}