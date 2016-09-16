<?php

namespace PPTO;
use \Exception;
use \PDO;
use \Log;
use \DB;

class SupPPTOProcedure
{

    public function uploadValidate( $input , $year , $category )
    {
        try
        {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_SUPERVISOR.SP_VALIDAR_PPTO_SUPERVISOR( ' . $input . ' , :año , :categoria , :rpta ); end;' );
            $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
            $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
            $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
            $stmt->execute();
            return $rpta;
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return json_encode( [ status => error , description => $e->getMessage() ] );
        }
    }

    public function upload( $input , $year , $category , $user_id )
    {
        try
        {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_SUPERVISOR.SP_CARGA_PPTO_SUPERVISOR( ' . $input . ' , :año , :categoria , :user_id , :rpta ); end;' );
            $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
            $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
            $stmt->bindParam( ':user_id' , $user_id , PDO::PARAM_INT );
            $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 90000 );
            $stmt->execute();
            return $rpta;
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return json_encode( [ status => error , description => $e->getMessage() ] );
        }
    }

    public function update( $ppto_id , $roundAmount , $user_id )
    {
        try
        {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_SUPERVISOR.SP_UPDATE_PPTO_SUPERVISOR( :ppto_id , :monto , :user_id , :rpta ); end;' );
            $stmt->bindParam( ':ppto_id' , $ppto_id , PDO::PARAM_INT );
            $stmt->bindParam( ':monto' , $roundAmount );
            $stmt->bindParam( ':user_id' , $user_id );
            $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
            $stmt->execute();
            return $rpta;      
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return json_encode( [ status => error , description => $e->getMessage() ] );
        }
    }

}
