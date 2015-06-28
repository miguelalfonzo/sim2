<?php

namespace Dmkt;
use \Eloquent;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class InvestmentType extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'SIM_TIPO_INVERSION';
    protected $primaryKey = 'id';

    public function nextId()
    {
        $nextId = InvestmentType::withTrashed()->select('id')->orderBy( 'id' , 'desc' )->first();
        if ( is_null( $nextId ) )
            return 1;
        else
            return $nextId->id + 1;
    }

    protected static function order()
    {
    	return InvestmentType::orderBy('id','asc')->get();
    }

    protected function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_inversion' , 'id' );
    }
}