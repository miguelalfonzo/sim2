<?php

namespace Dmkt;
use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Activity extends Eloquent
{
    use SoftDeletingTrait;

    protected $table = TB_TIPO_ACTIVIDAD;
    protected $primaryKey = 'id';

    public function nextId()
    {
        $nextId = Activity::withTrashed()->select('id')->orderBy( 'id' , 'desc' )->first();
        if ( is_null( $nextId ) )
            return 1;
        else
            return $nextId->id + 1;
    }

    protected static function order()
    {
    	return Activity::orderBy( 'nombre' , 'asc' )->get();
    }


    public function investmentActivity()
    {
    	return $this->hasMany('Dmkt\InvestmentActivity' , 'id_actividad' , 'id' );
    }

    protected function client()
    {
        return $this->hasOne( 'Client\ClientType' , 'id' , 'tipo_cliente' );
    }
}