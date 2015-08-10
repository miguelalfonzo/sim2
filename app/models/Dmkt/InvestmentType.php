<?php

namespace Dmkt;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class InvestmentType extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = 'TIPO_INVERSION';
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
    	return InvestmentType::orderBy('nombre','asc')->get();
    }

    protected static function orderWithTrashed()
    {
        return InvestmentType::orderBy( 'updated_at' , 'desc' )->withTrashed()->get();
    }

    protected function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_inversion' , 'id' );
    }

    protected static function orderMkt()
    {
        return InvestmentType::orderBy('nombre','asc')->where( 'codigo_actividad' , INVERSION_MKT )->get();
    }

    protected static function orderInst()
    {
        return InvestmentType::orderBy('nombre','asc')->where( 'codigo_actividad' , INVERSION_INSTITUCIONAL )->get();
    }

    protected function accountFund()
    {
        return $this->hasOne( '\Fondo\Fondo' , 'id' , 'id_fondo_contable' );
    }

    protected function approvalInstance()
    {
        return $this->belongsTo( '\Policy\ApprovalInstanceType' , 'id_tipo_instancia_aprobacion' );
    }
}