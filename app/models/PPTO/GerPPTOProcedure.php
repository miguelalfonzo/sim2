<?php

namespace PPTO;
use \Exception;
use \PDO;
use \Log;
use \DB;

class GerPPTOProcedure
{
    public function uploadValidate( $input , $year , $category )
    {
        try
        {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_GERENTE.SP_VALIDAR_PPTO_GERENTE( ' . $input . ' , :año , :categoria , :rpta , :desc , :list ); end;' );
            $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
            $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
            $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 10 );
            $stmt->bindParam( ':desc' , $desc , PDO::PARAM_STR , 200 );
            $stmt->bindParam( ':list' , $list , PDO::PARAM_STR , 500 );
            $stmt->execute();
            $response = [ status => $rpta , description => $desc ];
            if( ! is_null( $list ) )
            {
                $response[ 'List' ] = [ 'Detail' => explode( '|' , substr( $list , 0 , -1 ) ) , 'Class' => 'list-warning' ];
            }
            return $response;
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return [ status => error , description => $e->getMessage() ];
        }
    }

    public function upload( $input , $year , $category , $user_id )
    {
        try
        {
            $pdo = DB::getPdo();
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_GERENTE.SP_CARGA_PPTO_GERENTE( ' . $input . ' , :año , :categoria , :user_id , :rpta ); end;' );
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
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_GERENTE.SP_UPDATE_PPTO_GERENTE( :ppto_id , :monto , :user_id , :rpta ); end;' );
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
