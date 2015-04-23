<?php

namespace Expense;

use \Eloquent;

class Entry extends Eloquent
{
    protected $table= 'DMKT_RG_ASIENTO';
    protected $primaryKey = 'id';
    protected $dates = ['fec_origen'] ;
    
    public function searchId()
    {
        $lastId = Entry::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

    protected function account()
    {
        return $this->hasOne( 'Dmkt\Account' , 'num_cuenta' , 'num_cuenta');
    }

}