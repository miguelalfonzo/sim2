<?php

namespace Dmkt;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use \Auth;

class InvestmentType extends Eloquent
{

    use SoftDeletingTrait;

    protected $table = TB_TIPO_INVERSION;
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

    public function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_inversion' , 'id' );
    }

    protected static function orderMkt()
    {
        $investments = InvestmentType::orderBy('nombre','asc');
        if( Auth::user()->username == 'HOLISTIC' )
        {
            $investments->whereIn( 'codigo_actividad' , [ INVERSION_MKT , INVERSION_PROV ] ); 
        }
        else
        {
            $investments->where( 'codigo_actividad' , INVERSION_MKT );
        }
        return $investments->get();
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