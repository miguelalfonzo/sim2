<?php

namespace PPTO;

use \BaseController;

use \Users\Personal;
use \PPTO\PPTOSupervisor;
use \Fondo\FondoMktPeriodHistory;
use \Fondo\FondoSubCategoria;
use \Fondo\FondoSupervisor;
use \Expense\Table;
use \System\FondoMktHistory;

use \Carbon\Carbon;
use \Validator;
use \StdClass;
use \Input;
use \Excel;
use \View;
use \DB;

use \Exception;

class PPTOController extends BaseController
{
    private $supPPTOType = 1;
    private $genPPTOType = 2;
    private $insPPTOType = 3;

	public function view()
    {
        $startYear = $this->getStartYear();
        $years = range( $startYear , $startYear + 50 , 1 );
        $categories = FondoSubCategoria::select( [ 'id' , 'descripcion' , 'trim( tipo ) tipo' ] )
                        ->whereIn( 'trim( tipo )' , [ SUP , GER_PROD , GER_PROM ] )
                        ->orderBy( 'descripcion' , 'ASC' )->get();
    	return View::make( 'ppto.view' , [ 'years' => $years , 'categories' => $categories ] );    
    }

    public function upload()
    {
        $inputs = Input::all();
        
        if( ! isset( $inputs[ 'type' ] ) || ! in_array( $inputs[ 'type' ] , [ 1 , 2 , 3 ] ) )
        {
            return $this->warningException( 'Carga no identificada' , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        
        $middleRpta = $this->uploadValidate( $inputs );
        
        if( $middleRpta[ status ] == ok )
        {
            if( $inputs[ 'type' ] == $this->supPPTOType )
            {
                $fileRows = Excel::selectSheetsByIndex( 0 )->load( $inputs[ 'file' ] )->get();
             
                foreach( $fileRows as $row )
                {
                    $middleRpta = $this->validateRowSup( $row->toArray() );
                    if( $middleRpta[ status ] != ok )
                    {
                        return $this->warningException( $middleRpta[ description ] , __FUNCTION__ , __LINE__ , __FILE__ );
                    }
                }
                return $this->uploadCategoryFamilyUser();
            }
            else
            {
                return $this->warningException( 'Sin implementar' , __FUNCTION__ , __LINE__ , __FILE__ ); 
            }
        }
        
        return $middleRpta;
    }

    public function uploadValidate( $inputs )
    {
        $rules =
            [
                'year'     => 'required|numeric|min:' . $this->getStartYear()
            ];

        $messages =
            [
                'year.min' => 'El año :min es invalido'
            ];
        $validator = Validator::make( $inputs , $rules , $messages );

        $type = $inputs[ 'type' ];
        
        $validator->sometimes( [ 'category' ] , 'required|numeric|in:' . $this->typeCategories( $inputs[ 'type' ] ) , function() use( $type )
        {
            return in_array( $type , [ $this->supPPTOType , $this->genPPTOType ] );
        });

        $validator->sometimes( [ 'file' ] , 'required|mimes:xls,xlsx' , function() use( $type )
        {
            return in_array( $type , [ $this->supPPTOType , $this->genPPTOType ] );
        });

        $validator->sometimes( [ 'amount' ] , 'required|numeric' , function() use( $type )
        {
            return in_array( $type , [ $this->insPPTOType ] );
        });

        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __FILE__ , __LINE__ );
        }
        return $this->setRpta();
    }

    private function getStartYear()
    {
        $now = Carbon::now();
        return $now->format( 'Y' );
    }

    private function typeCategories( $type )
    {
        if( $type == $this->supPPTOType )
        {
            $fundCategoryIds = FondoSubCategoria::select( 'id' )
                    ->where( 'trim( tipo )' , SUP )
                    ->lists( 'id' );
            return implode( $fundCategoryIds , ',' );
        }
        elseif( $type = $this->genPPTOType )
        {
            $fundCategoryIds = FondoSubCategoria::select( 'id' )
                    ->whereIn( 'trim( tipo )' , [ GER_PROD , GER_PROM ] )
                    ->lists( 'id' );
            return implode( $fundCategoryIds , ',' );  
        }
        return 0;
    }

    private function validateRowSup( $inputs )
    {
        $rules =
            [
                'monto'   => 'required|numeric|min:0',
                'cod129'  => 'required|numeric|in:' . $this->getFamilyIds(),
                'codfico' => 'required|numeric|in:' . $this->getSupIds()
            ];

        $validator = Validator::make( $inputs , $rules );
        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        };
        return $this->setRpta();
    }

    private function getFamilyIds()
    {
        return implode( Table::getFamilyIds() , ',' );
    }

    private function getSupIds()
    {
        $data = Personal::select( 'bago_id' )
                    ->where( 'tipo' , 'S' )->lists( 'bago_id' );
        return implode( $data , ',' );
    }

    public function uploadCategoryFamilyUser()
    {
    	try
    	{
    		$inputs   = Input::all();
    		$file     = Input::file( 'file' );
    		$year     = $inputs[ 'year' ];
    		$category = $inputs[ 'category' ];
    		$fileRows = Excel::selectSheetsByIndex( 0 )->load( $file )->get();
    		
    		$version  = PPTOSupervisor::nextVersion( $year );
    		
    		$same = true;
    		foreach( $fileRows as $row )
    		{	
    			$personRegister = Personal::getBagoSup( $row->codfico );
    		
    			if( is_null( $personRegister ) )
    			{
    				return $this->warningException( 'El supervisor no esta registrado en el SIM' , __FUNCTION__ , __LINE__ , __FILE__ );
    			}
    			
    			$pptoLastSupRegister = PPTOSupervisor::getSameLast( $year , $category , $personRegister->user_id , $row->cod129 , $version - 1 , $row->monto );
    		
    			if( is_null( $pptoLastSupRegister ) )
    			{
    				$same = false;
    			}
    		
    			$row->user_id   = $personRegister->user_id;
			}

			if( $same )
			{
				return $this->warningException( 'No se ha encontrado diferencias en los archivos' , __FUNCTION__ , __LINE__ , __FILE__ );
			}

			$i = 0;
    		DB::beginTransaction();
    		foreach( $fileRows as $row )
    		{
    			$pptoLastSupRegister = PPTOSupervisor::getLast( $year , $category , $row->user_id , $row->cod129 , $version - 1 );
    			if( is_null( $pptoLastSupRegister ) )
    			{
    				$fundSupRegister = FondoSupervisor::getUnique( $year , $category , $row->user_id , $row->cod129 );
    				\Log::info( $fundSupRegister );
    				if( is_null( $fundSupRegister ) )
    				{
    					//\Log::info( 'Fondo Nulo' );
    					$a = 1;
    				}
    				else
    				{
    					$firstMoveRegister = FondoMktHistory::getFundFirstRegister( $fundSupRegister->id , SUP );
    					if( is_null( $firstMoveRegister ) )
    					{
    						// \Log::info( 'Fondo inicial no modificado' );
    						$a = 2;
    						$initialAmount = $fundSupRegister->saldo;
    					}
    					else
    					{
    						$initialAmount = $firstMoveRegister->old_saldo;
    					}

    					\Log::info($initialAmount );

    					if( $row->monto != $initialAmount )
    					{
    						$diffAmount = $row->monto - $initialAmount;
    						$fundSupRegister->saldo += $diffAmount;
    						$fundSupRegister->save();

    						\Log::info( $fundSupRegister );

    						$historyUpdates = FondoMktHistory::updateFundAmount( $fundSupRegister->id , SUP , $diffAmount );
    						if( $historyUpdates == -1 )
    						{
    							$i = 1;
    							DB::rollback();
    						}
    						$periodHistoryUpdates = FondoMktPeriodHistory::updateFundAmount( $category , $year , $diffAmount );
    						if( $periodHistoryUpdates == -1 )
    						{
    							$i = 1;
    							DB::rollback();
    						}	
    					}
    				}
    			}

				$data                  = new StdClass;
				$data->version 		   = $version;
				$data->year            = $year;
				$data->sub_category_id = $inputs[ 'category' ];
				$data->family_id       = $row->cod129;
				$data->user_id         = $row->user_id;
				$data->amount          = $row->monto;

    			$pptoSupRegister = new PPTOSupervisor;
    			$pptoSupRegister->insertPPTO( $data );
    		}

    		\Log::info( $i );

    		$data = FondoMktHistory::where( 'id_tipo_to_fondo' , 'S' )->whereHas( 'fromSupFund' , function( $query ) use( $category )
    		{
    			$query->where( 'subcategoria_id' , $category );
    		})->get();

    		// \Log::info( $data );

    		DB::commit();

    		return $this->setRpta( $fileRows );
    	}
    	catch( Exception $e )
    	{
    		return $this->internalException( $e , __FUNCTION__ );
    	}
    }

}
