<?php

namespace PPTO;
use \Exception;
use \Auth;
use \PDO;
use \Log;
use \DB;

class PPTOProcedure
{
    public function insPPTOTransaction( $amount , $year )
    {
        try
        {
            $roundAmount = round( $amount , 2 , PHP_ROUND_HALF_UP );
            $middleRpta = $this->insPPTOValidate( $roundAmount , $year );
            if( $middleRpta[ status ] == ok )
            {
                $middleRpta = $this->insPPTOUpdate( $roundAmount , $year );
            }
            return $middleRpta;
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return [ status => error , description => $e->getMessage() ];
        }
    }

    private function insPPTOValidate( $roundAmount , $year )
    {
        $pdo = DB::getPdo();
        $user_id = Auth::user()->id;
        $stmt = $pdo->prepare( 'BEGIN PK_PPTO_INSTITUCION.SP_VALIDAR_PPTO_INSTITUCION( :monto , :año , :rpta ); end;' );
        $stmt->bindParam( ':monto' , $roundAmount );
        $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
        $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
        $stmt->execute();

        return $this->validateResponse( $rpta );    
    }

	private function insPPTOUpdate( $roundAmount , $year )
    {
        $pdo = DB::getPdo();
        $user_id = Auth::user()->id;
        $stmt = $pdo->prepare( 'BEGIN PK_PPTO_INSTITUCION.SP_CARGA_PPTO_INSTITUCION( :monto , :año , :user_id , :rpta ); end;' );
        $stmt->bindParam( ':monto' , $roundAmount );
        $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
        $stmt->bindParam( ':user_id' , $user_id , PDO::PARAM_INT );
        $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
        $stmt->execute();

        return $this->validateResponse( $rpta , 'Se cargo el presupuesto institucional correctamente' );
    }

    public function gerPPTOTransaction( $fileData , $year , $category )
    {
        try
        {
            $rowInputs = '';
            foreach( $fileData as $key => $row )
            {
                $rowInputs .= 'FILE_GERENTE_ROW( ' . $row->cod129 . ' , ' . round( $row->monto , 2 , PHP_ROUND_HALF_UP ) . ' ),';
            }
            $rowInputs = substr( $rowInputs , 0 , -1 );
            $dataInput = 'FILE_GERENTE_TAB( ' . $rowInputs . ' )';
            
            $middleRpta = $this->gerPPTOValidate( $dataInput , $year , $category );
            if( $middleRpta[ status ] == ok )
            {
                return $this->gerPPTOLoad( $dataInput , $year , $category );
            }
            return $middleRpta;
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return [ status => error , description => $e->getMessage() ];
        }
    }

    private function gerPPTOValidate( $input , $year , $category )
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare( 'BEGIN PK_PPTO_GERENTE.SP_VALIDAR_PPTO_GERENTE( ' . $input . ' , :año , :categoria , :rpta ); end;' );
        $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
        $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
        $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
        $stmt->execute();

        return $this->validateResponse( $rpta );
    }

    private function gerPPTOLoad( $input , $year , $category )
    {
        $pdo = DB::getPdo();
        $user_id = Auth::user()->id;
        $stmt = $pdo->prepare( 'BEGIN PK_PPTO_GERENTE.SP_CARGA_PPTO_GERENTE( ' . $input . ' , :año , :categoria , :user_id , :rpta ); end;' );
        $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
        $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
        $stmt->bindParam( ':user_id' , $user_id , PDO::PARAM_INT );
        $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
        $stmt->execute();

        return $this->validateResponse( $rpta );
    }

    public function gerUpdateProcedure( $ppto_id , $amount )
    {
        try
        {
            $roundAmount = round( $amount , 2 , PHP_ROUND_HALF_UP );
            $pdo = DB::getPdo();
            $user_id = Auth::user()->id;
            $stmt = $pdo->prepare( 'BEGIN PK_PPTO_GERENTE.SP_UPDATE_PPTO_GERENTE( :ppto_id , :monto , :user_id , :rpta ); end;' );
            $stmt->bindParam( ':ppto_id' , $ppto_id , PDO::PARAM_INT );
            $stmt->bindParam( ':monto' , $roundAmount );
            $stmt->bindParam( ':user_id' , $user_id );
            $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
            $stmt->execute();

            return $this->validateResponse( $rpta );      
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return [ status => error , description => $e->getMessage() ];
        }
    }

    public function supPPTOTransaction( $fileData , $year , $category )
    {
        try
        {
            $rowInputs = '';
            foreach( $fileData as $key => $row )
            {
                $rowInputs .= 'FILE_SUPERVISOR_ROW( ' . $row->user_id . ' , ' . $row->cod129 . ' , ' . round( $row->monto , 2 , PHP_ROUND_HALF_UP ) . ' ),';
            }
            $rowInputs = substr( $rowInputs , 0 , -1 );
            $dataInput = 'FILE_SUPERVISOR_TAB( ' . $rowInputs . ' )';
            
            $middleRpta = $this->supPPTOValidate( $dataInput , $year , $category );
            if( $middleRpta[ status ] == ok )
            {
                return $this->supPPTOLoad( $dataInput , $year , $category );
            }
            return $middleRpta;
        }
        catch( Exception $e )
        {
            Log::error( $e );
            return [ status => error , description => $e->getMessage() ];
        }
    }

    private function supPPTOValidate( $input , $year , $category )
    {
        $pdo = DB::getPdo();
        $stmt = $pdo->prepare( 'BEGIN PK_PPTO_SUPERVISOR.SP_VALIDAR_PPTO_SUPERVISOR( ' . $input . ' , :año , :categoria , :rpta ); end;' );
        $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
        $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
        $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
        $stmt->execute();

        return $this->validateResponse( $rpta );
    }

    private function supPPTOLoad( $input , $year , $category )
    {
        $pdo = DB::getPdo();
        $user_id = Auth::user()->id;
        $stmt = $pdo->prepare( 'BEGIN PK_PPTO_SUPERVISOR.SP_CARGA_PPTO_SUPERVISOR( ' . $input . ' , :año , :categoria , :user_id , :rpta ); end;' );
        $stmt->bindParam( ':año' , $year , PDO::PARAM_INT );
        $stmt->bindParam( ':categoria' , $category , PDO::PARAM_INT );
        $stmt->bindParam( ':user_id' , $user_id , PDO::PARAM_INT );
        $stmt->bindParam( ':rpta' , $rpta , PDO::PARAM_STR , 4000 );
        $stmt->execute();

        return $this->validateResponse( $rpta );
    }

    private function validateResponse( $response    )
    {
        $response = json_decode( $response );
        if( $response->{ status } == ok )
        {
            $rpta = [ status => ok ];
        }
        else
        {
            $rpta = [ status => warning ];
        }

        if( isset( $response->{ description } ) )
        {
            $rpta[ description ] = $response->{ description };
        }

        if( isset( $response->{ data } ) )
        {
            $rpta[ data ] = $response->{ data };
        }   

        return $rpta;

    }
}
