<?php

namespace Expense;

use \Eloquent;

class Entry extends Eloquent{

    protected $table= 'DMKT_RG_ASIENTOS';
    protected $primaryKey = null;
    public $incrementing = false;

}