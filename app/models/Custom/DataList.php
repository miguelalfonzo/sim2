<?php

namespace Custom;
use \DB;
use \Auth;

class DataList
{
	/*public static function getSolicituds()
    {
        return DB::table( 'LISTADO_SOLICITUD' )->get();
    }*/

	public static function getSolicituds( array $dates , $state )
    {
    	DB::setDateFormat('DD/MM/YYYY');

    	$test = DB::select( 'SELECT SYSDATE FROM DUAL' );
    	\Log::info( $test );



    	$user = Auth::user();
    	$user_id = Auth::user()->id;
    	$start = $dates[ 'start' ];
    	$end   = $dates[ 'end' ];
    	\Log::info( $user_id );
    	$data = DB::table( "TABLE( LISTADO_SOLICITUD_FN( $user_id , '$start' , '$end' , $state ) )" )->get();
        //\Log::info( $data );
        return $data;
        return DB::table( 'SOLICITUD a' )
        	->join( 'solicitud_detalle b')

        ->get();
    }
}
