<?php

namespace PPTO;

use \BaseController;

use \Users\Personal;
use \PPTO\PPTOSupervisor;
use \Fondo\FondoMktPeriodHistory;
use \Fondo\FondoSubCategoria;
use \System\FondoMktHistory;
use \Fondo\FondoSupervisor;

use \Carbon\Carbon;
use \Validator;
use \StdClass;
use \Input;
use \Excel;
use \View;
use \Auth;
use \DB;

use \Exception;


class PPTOController extends BaseController
{    
    const SupPPTOType  = 1;
    const GenPPTOType  = 2;
    const InsPPTOType  = 3;
	
    public function view()
    {
        $startYear  = $this->getStartYear();
        $years      = range( $startYear , $startYear + 50 , 1 );
        $categories = 
            FondoSubCategoria::select( [ 'id' , 'descripcion' , 'trim( tipo ) tipo' ] )
                ->whereIn( 'trim( tipo )' , [ SUP , GER_PROD , GER_PROM ] )
                ->orderBy( 'descripcion' , 'ASC' )->get();
    	return View::make( 'ppto.view' , [ 'years' => $years , 'categories' => $categories ] );    
    }

    public function loadPPTO()
    {
        $inputs = Input::all();
        if( $inputs[ 'type' ] == 1 )
        {
            $PPTOSupModel = new PPTOSupervisor;
            $data = $PPTOSupModel->getPPTO( $inputs[ 'year' ] );
            $columns =
            [
                [ 'title' => 'Categoría' , 'data' => 'sub_category.descripcion' , 'className' => 'text-center' ],
                [ 'title' => 'Supervisor' , 'data' => 'personal.nombres' , 'className' => 'text-center' ],
                [ 'title' => 'Familia' , 'data' => 'family.descripcion' , 'className' => 'text-center' ],
                [ 'title' => 'Monto' , 'data' => 'monto' ,  'className' => 'text-center' ],
                [ 'title' => '' , 'defaultContent' => '<button type="button" class="btn btn-success btn-xs" style="outline:none"><span class="glyphicon glyphicon-pencil"></span></button>' , 'className' => 'text-center' ],
            ];
            $rpta = $this->setRpta( $data );
            $rpta[ 'columns' ] = $columns;
            return $rpta;
        }
        return 'falso';
    }

    public function upload()
    {
        try 
        {
            $inputs = Input::all();
            
            if( ! isset( $inputs[ 'type' ] ) || ! in_array( $inputs[ 'type' ] , [ Self::SupPPTOType , Self::GenPPTOType , Self::InsPPTOType ] ) )
            {
                return $this->warningException( 'Carga no identificada' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            
            $middleRpta = $this->uploadValidate( $inputs );
            
            if( $middleRpta[ status ] == ok )
            {
                $fileRows   = Excel::selectSheetsByIndex( 0 )->load( $inputs[ 'file' ] )->get();      
                if( $inputs[ 'type' ] == Self::SupPPTOType )
                {
                    return $this->processUploadCategoryFamilyUser( $fileRows );
                }
                else
                {
                    return $this->warningException( 'Sin implementar' , __FUNCTION__ , __LINE__ , __FILE__ ); 
                }
            }
            
            return $middleRpta;
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function uploadValidate( $inputs )
    {
        $rules =
        [
            'year' => 'required|numeric|min:' . $this->getStartYear(),
        ];

        $messages =
        [
            'year.min' => 'El año ' . $inputs[ 'year' ] . ' es menor que el actual'
        ];

        $validator = Validator::make( $inputs , $rules , $messages );

        $type = $inputs[ 'type' ];
        
        $validator->sometimes( [ 'category' ] , 'required|numeric|in:' . $this->typeCategories( $inputs[ 'type' ] ) , function() use( $type )
        {
            return in_array( $type , [ Self::SupPPTOType , Self::GenPPTOType ] );
        });

        $validator->sometimes( [ 'file' ] , 'required|mimes:xls,xlsx' , function() use( $type )
        {
            return in_array( $type , [ Self::SupPPTOType , Self::GenPPTOType ] );
        });

        $validator->sometimes( [ 'amount' ] , 'required|numeric' , function() use( $type )
        {
            return in_array( $type , [ Self::InsPPTOType ] );
        });

        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __FILE__ , __LINE__ );
        }
        return $this->setRpta();
    }

    private function processUploadCategoryFamilyUser( $fileRows )
    {
        include( app_path() . '/models/Query/QueryProducts.php' );
                    
        $supsId     = $this->getSupIds();
        $familiesId = implode( $qryProducts->lists( 'id' ) , ',' );

        $warnings = [];
        foreach( $fileRows as $row )
        {
            $middleRpta = $this->validateRowSup( $row->toArray() , $familiesId , $supsId );
            if( $middleRpta[ status ] != ok )
            {
                $warnings[] = $middleRpta[ description ];
            }
        }

        if( ! empty( $warnings ) )
        {
            $rpta = $this->warningException( 'Se encontraron las siguientes observaciones en la carga del PPTO:' , __FUNCTION__ , __LINE__ , __FILE__ );
            $rpta[ 'List' ] = [ 'Class' => 'list-group-item-warning' , 'Detail' => $warnings ];
            return $rpta;
        }
        return $this->uploadCategoryFamilyUser();           
    }


    private function validateRowSup( $inputs , $familiesId , $supsId )
    {
        $rules =
        [
            'monto'   => 'required|numeric|min:0',
            'cod129'  => 'required|numeric|in:' . $familiesId ,
            'codfico' => 'required|numeric|in:' . $supsId 
        ];

        $messages =
        [
            'cod129.in'  => 'La familia (codigo:' . $inputs[ 'cod129' ] . ') no figura en el PPTO de Ventas.',
            'codfico.in' => 'El supervisor (codigo:' . $inputs[ 'codfico' ] . ') no esta registrado en el sistema'
        ];

        $validator = Validator::make( $inputs , $rules , $messages );
        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        };
        return $this->setRpta();
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
            
            $registersPPTO = [];
            $user = Auth::user();

            $PPTOSupModel = new PPTOSupervisor;
            $PPTOId = $PPTOSupModel->nextId();
            
            foreach( $fileRows as $row )
            {
                $row->monto = round( $row->monto , 2 , PHP_ROUND_HALF_UP );
                $data = 
                [
                    'id'              => $PPTOId++,
                    'version'         => $version,
                    'anio'            => $year,
                    'supervisor_id'   => $row->user_id,
                    'subcategoria_id' => $inputs[ 'category' ],
                    'marca_id'        => $row->cod129,
                    'monto'           => $row->monto,
                    'created_by'      => $user->id,
                    'updated_by'      => $user->id
                ];

                $registersPPTO[] = $data;

            }

            DB::beginTransaction();

            $statusPPTO = PPTOSupervisor::insert( $registersPPTO );
            
            if( $statusPPTO == 1 )
            {
            
                $registersFund   = [];

                $fundSupModel = new FondoSupervisor;
                $fundId       = $fundSupModel->nextId();

                $modelPPTO = $PPTOSupModel->getCurrentPPTO( $year );

                foreach( $modelPPTO as $row )
                {
                    $fundSupRegister = FondoSupervisor::getUnique( $year , $category , $row->supervisor_id , $row->marca_id );
                    if( is_null( $fundSupRegister ) )
                    {
                        $data =
                        [
                            'id'              => $fundId++,
                            'anio'            => $year,
                            'supervisor_id'   => $row->supervisor_id,
                            'subcategoria_id' => $inputs[ 'category' ],
                            'marca_id'        => $row->marca_id,
                            'saldo'           => $row->monto,
                            'retencion'       => 0,
                            'created_by'      => $user->id,
                            'updated_by'      => $user->id
                        ];

                        $registersFund[] = $data;
                    }
                    else
                    {
                        $firstMoveRegister = FondoMktHistory::getFundFirstRegister( $fundSupRegister->id , SUP );
                        if( is_null( $firstMoveRegister ) )
                        {
                            $initialAmount = $fundSupRegister->saldo;
                        }
                        else
                        {
                            $initialAmount = $firstMoveRegister->old_saldo;
                        }

                        if( $row->monto != $initialAmount )
                        {

                            $diffAmount = $row->monto - $initialAmount;

                            $fundUpdate = FondoSupervisor::updateFundAmount( $fundSupRegister->id , $diffAmount );
                            if( $fundUpdate == -1 )
                            {
                                DB::rollback();
                                return $this->warningException( 'No se pudo realizar la carga del PPTO' , __FUNCTION__ , __LINE__ , __FILE__ );
                            }

                            $historyUpdates = FondoMktHistory::updateFundAmount( $fundSupRegister->id , SUP , $diffAmount );
                            if( $historyUpdates == -1 )
                            {
                                DB::rollback();
                                return $this->warningException( 'No se pudo realizar la carga del PPTO' , __FUNCTION__ , __LINE__ , __FILE__ );
                            }
                            $periodHistoryUpdates = FondoMktPeriodHistory::updateFundAmount( $category , $year , $diffAmount );
                            if( $periodHistoryUpdates == -1 )
                            {
                                DB::rollback();
                                return $this->warningException( 'No se pudo realizar la carga del PPTO' , __FUNCTION__ , __LINE__ , __FILE__ );
                            }   
                        }
                    }
                }
                
                if( empty( $registersFund ) )
                {
                    DB::commit();
                    return $this->setRpta();
                }
                else
                {
                    $statusFund = FondoSupervisor::insert( $registersFund );
                
                    if( $statusFund == 1 )
                    {
                        DB::commit();
                        return $this->setRpta();
                    }
                    else
                    {
                        DB::rollback();
                        return $this->warningException( 'No se pudo realizar la carga del PPTO' , __FUNCTION__ , __LINE__ , __FILE__ );            
                    }
                }
            }
            else
            {
                DB::rollback();
                return $this->warningException( 'No se pudo realizar la carga del PPTO' , __FUNCTION__ , __LINE__ , __FILE__ );            
            }
    	}
    	catch( Exception $e )
    	{
            DB::rollback();
    		return $this->internalException( $e , __FUNCTION__ );
    	}
    }

    private function getStartYear()
    {
        $now = Carbon::now();
        return $now->format( 'Y' );
    }

    private function getSupIds()
    {
        $data = Personal::select( 'bago_id' )
                    ->where( 'tipo' , 'S' )->lists( 'bago_id' );
        return implode( $data , ',' );
    }

    private function typeCategories( $type )
    {
        if( $type == Self::SupPPTOType )
        {
            $fundCategoryIds = FondoSubCategoria::select( 'id' )
                    ->where( 'trim( tipo )' , SUP )
                    ->lists( 'id' );
            return implode( $fundCategoryIds , ',' );
        }
        elseif( $type = Self::GenPPTOType )
        {
            $fundCategoryIds = FondoSubCategoria::select( 'id' )
                    ->whereIn( 'trim( tipo )' , [ GER_PROD , GER_PROM ] )
                    ->lists( 'id' );
            return implode( $fundCategoryIds , ',' );  
        }
        return 0;
    }

}
