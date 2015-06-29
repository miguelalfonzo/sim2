<?php

namespace Dmkt;
use \Eloquent;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class InvestmentActivity extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'SIM_INVERSION_ACTIVIDAD';
    protected $primaryKey = 'id';

    public function nextId()
    {
        $nextId = InvestmentActivity::withTrashed()->select('id')->orderBy( 'id' , 'desc' )->first();
        if ( is_null( $nextId ) )
            return 1;
        else
            return $nextId->id + 1;
    }

    protected static function order()
    {
    	return InvestmentActivity::orderBy('id','asc')->get();
    }

    public function activity()
    {
    	return $this->hasOne( 'Dmkt\Activity' , 'id' , 'id_actividad' );
    }

    public function investment()
    {
    	return $this->hasOne( 'Dmkt\InvestmentType' , 'id' , 'id_inversion' );
    }
}