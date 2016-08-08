<?php

namespace Custom;
use \DB;
use \Auth;
use \BaseController;
use \Exception;
use \Log;

class DataList
{

	public static function getSolicituds( array $dates , $state )
    {
        try
        {
            DB::setDateFormat('DD/MM/YYYY');
        	$user = Auth::user();
        	$user_id = $user->id;
        	$start = $dates[ 'start' ];
        	$end   = $dates[ 'end' ];
        	$data = DB::table( "TABLE( LISTADO_SOLICITUD_FN( $user_id , '$start' , '$end' , $state ) )" )->get();
            return $data;
        }
        catch( Exception $e )
        {
            Log::error( __FUNCTION__ );
            Log::error( $e );
            return [ status => error , description => 'No se pudo cargar el listado de solicitudes' ];
        }
    }
}
