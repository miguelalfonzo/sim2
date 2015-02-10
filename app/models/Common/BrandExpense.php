<?php

namespace Common;
use \Eloquent;
use \Common\Fondo;

class BrandExpense extends Eloquent {

    protected $table = 'DMKT_RG_MARCA_GASTOS';
    protected $primaryKey = null;
    protected $incrementing = false;

    public function fondo() {
        return $this->hasOne('Common\Fondo', 'CUENTA_CONT', 'MARCAS_GASTOS');
    }
}