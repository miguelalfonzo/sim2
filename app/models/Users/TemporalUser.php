<?php

namespace Users;

use \Eloquent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TemporalUser extends Eloquent 
{
    use SoftDeletingTrait;

    protected $table = 'USER_TEMPORAL';
    protected $primaryKey = 'id' ;
    protected $dates = ['deleted_at'];

    public function lastId()
    {
        $lastId = TemporalUser::orderBy( 'id' , 'DESC' )->withTrashed()->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    protected static function getAssignment( $iduser )
    {
    	return TemporalUser::where( 'id_temp' , $iduser )->first();
    }

    protected function user()
    {
    	return $this->hasOne( 'User' , 'id' , 'id_user' );
    }

    

}