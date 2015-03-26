<?php

namespace Dmkt;

use \Eloquent;

class CtaRm extends Eloquent 
{
    protected $table = 'VAR.BENEFICIARIOS_CTA_BANC';
    protected $primaryKey = 'CL_CODIGO';

    public function cuenta($dni)
    {
    	try
    	{
    		$rpta = array();
    		$cta = CtaRm::WHERE('CODBENEFICIARIO',$dni)->WHERE('TIPO','H')->SELECT('CUENTA')->first();
    		if (!$cta)
    			$cta = CtaRm::WHERE('CODBENEFICIARIO',$dni)->WHERE('TIPO','B')->SELECT('CUENTA')->first();
    		$rpta[status] = ok;
    		$rpta[data] = $cta;
    	}
    	catch (Exception $e)
    	{
    		$rpta[status] = error;
    	}
    	return $rpta;
    }
}