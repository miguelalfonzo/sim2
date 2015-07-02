<?php

namespace Policy;

use \Eloquent;

class AprovalPolicy extends Eloquent 
{
    protected $table = 'POLITICA_APROBACION';
    protected $primaryKey = 'id';

    protected static function getToUser( $investment , $order )
    {
    	return AprovalPolicy::where( 'id_inversion ' , $investment )->where( 'orden' , $order )->select( 'tipo_usuario' )->first();
    }

    protected static function getUserInvestmentPolicy( $investment , $userType , $order )
    {
    	return AprovalPolicy::where( 'id_inversion' , $investment )->whereIn( 'tipo_usuario' , $userType )->where( 'orden' , $order )->first();
    }

    protected function userType()
    {
        return $this->hasOne( 'Common\TypeUser' , 'codigo' , 'tipo_usuario' );
    }
}
