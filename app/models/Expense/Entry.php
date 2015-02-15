<?php

namespace Expense;

use \Eloquent;

class Entry extends Eloquent
{
    protected $table= 'DMKT_RG_ASIENTOS';
    protected $primaryKey = 'idasiento';
    public $incrementing = false;

    function searchId(){

        $lastId = Entry::orderBy('idasiento', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idasiento;
        }

    }

}