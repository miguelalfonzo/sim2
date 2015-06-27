<?php

namespace Expense;

use \Eloquent;

class Entry extends Eloquent
{
    protected $table= 'SIM_ASIENTO';
    protected $primaryKey = 'id';
    protected $dates = ['fec_origen'] ;
    
    public function lastId()
    {
        $lastId = Entry::orderBy('id', 'DESC')->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    protected function account()
    {
        return $this->hasOne( 'Dmkt\Account' , 'num_cuenta' , 'num_cuenta');
    }
}