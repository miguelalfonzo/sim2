<?php

namespace Policy;

use \Eloquent;

class InvestmentAprovalPolicy extends Eloquent 
{
    protected $table = 'INVERSION_POLITICA_APROBACION';
    protected $primaryKey = 'id';

    public function policy()
    {
        return $this->hasOne( 'Policy\AprovalPolicy' , 'id' , 'id_politica_aprobacion' );
    }
}
