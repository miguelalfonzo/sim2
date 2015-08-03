<?php

namespace Policy;

use \Eloquent;

class AprovalPolicy extends Eloquent 
{
    protected $table = 'POLITICA_APROBACION';
    protected $primaryKey = 'id';

    protected static function getToUser( $investment , $order )
    {
    	return AprovalPolicy::where( 'orden' , $order )->select( 'tipo_usuario' ,'desde' , 'hasta' )->whereHas( 'investment' , function( $query ) use ( $investment )
        {
            $query->where( 'id_inversion' , $investment );
        })->first();
    }

    protected static function getUserInvestmentPolicy( $investment , $userType , $order )
    {
        return AprovalPolicy::whereIn( 'tipo_usuario' , $userType )->where( 'orden' , $order )->whereHas( 'investment' , function( $query ) use ( $investment )
        {
            $query->where( 'id_inversion' , $investment );
        })->first();
    }

    protected function userType()
    {
        return $this->hasOne( 'Common\TypeUser' , 'codigo' , 'tipo_usuario' );
    }

    public function investment()
    {
        return $this->hasMany( 'Policy\InvestmentAprovalPolicy' , 'id_politica_aprobacion' , 'id' );
    }
}
