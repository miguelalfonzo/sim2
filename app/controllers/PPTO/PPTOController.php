<?php

namespace PPTO;

use \BaseController;

use \Users\Personal;
use \PPTO\PPTOInstitucion;
use \PPTO\PPTOSupervisor;
use \PPTO\PPTOGerente;
use \Fondo\FondoMktPeriodHistory;
use \Fondo\FondoSubCategoria;
use \System\FondoMktHistory;
use \Fondo\FondoSupervisor;
use \PPTO\PPTOProcedure;

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
    const GerPPTOType  = 2;
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
        
        $options = 
            '<button type="button" class="btn btn-info btn-xs edit-ppto-row">' .
                '<span class="glyphicon glyphicon-pencil"></span>' .
            '</button>' .
            '<button type="button" class="btn btn-success btn-xs save-ppto-row" style="display:none">' .
                '<span class="glyphicon glyphicon-ok"></span>' .
            '</button>' .
            '<button type="button" class="btn btn-info btn-xs cancel-ppto-row" style="display:none">' .
                '<span class="glyphicon glyphicon-share"></span>' .
            '</button>';
    
        if( $inputs[ 'type' ] == Self::SupPPTOType )
        {
            $PPTOSupModel = new PPTOSupervisor;
            $data = $PPTOSupModel->getPPTO( $inputs[ 'year' ] , $inputs[ 'category' ] );
            $columns =
            [
                [ 'title' => 'Categoría' , 'data' => 'sub_category.descripcion' , 'className' => 'text-center' ],
                [ 'title' => 'Supervisor' , 'data' => 'personal.nombres' , 'className' => 'text-center' ],
                [ 'title' => 'Familia' , 'data' => 'family.descripcion' , 'className' => 'text-center' ],
                [ 'title' => 'Monto' , 'data' => 'monto' ,  'className' => 'text-center monto-cell' ],
                [ 'title' => '' , 'defaultContent' => $options , 'className' => 'text-center option-cell' ],
            ];
        }
        elseif( $inputs[ 'type' ] == Self::GerPPTOType )
        {
            $PPTOGenModel = new PPTOGerente;
            $data = $PPTOGenModel->getPPTO( $inputs[ 'year' ] , $inputs[ 'category' ] );
            $columns =
            [
                [ 'title' => 'Categoría' , 'data' => 'sub_category.descripcion' , 'className' => 'text-center' ],
                [ 'title' => 'Familia' , 'data' => 'family.descripcion' , 'className' => 'text-center' ],
                [ 'title' => 'Monto' , 'data' => 'monto' ,  'className' => 'text-center monto-cell' ],
                [ 'title' => '' , 'defaultContent' => $options , 'className' => 'text-center option-cell' ],
            ];
        }
        elseif( $inputs[ 'type' ] == Self::InsPPTOType )
        {
            $PPTOInsModel = new PPTOInstitucion;
            $data = $PPTOInsModel->getPPTO( $inputs[ 'year' ] );
            $columns =
            [
                [ 'title' => 'Categoría' , 'data' => 'sub_category.descripcion' ,  'className' => 'text-center' ],
                [ 'title' => 'Monto' , 'data' => 'monto' ,  'className' => 'text-center monto-cell' ],
            ];
        }
        else
        {
            return $this->warningException( 'Carga de listado del PPTO no implementado' , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        $rpta = $this->setRpta( $data );
        $rpta[ 'columns' ] = $columns;
        return $rpta;
    }

    public function update()
    {
        try
        {
            $inputs = Input::all();
            $middleRpta = $this->validateUpdate( $inputs );
            if( $middleRpta[ status ] == ok )
            {
                $pptoProcedureModel = new PPTOProcedure;
                switch( $inputs[ 'type' ] )
                {
                    case Self::GerPPTOType:
                        return $pptoProcedureModel->gerUpdateProcedure( $inputs[ 'ppto_id' ] , $inputs[ 'monto' ] );
                    default:
                        return $this->warningException( 'Sin implementar' , __FUNCTION__ , __LINE__ , __FILE__ );
                }
            }
            else
            {
                return $middleRpta;
            }   
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function upload()
    {
        try 
        {
            $inputs = Input::all();
            $year   = $inputs[ 'year' ];
            
            if( ! isset( $inputs[ 'type' ] ) || ! in_array( $inputs[ 'type' ] , [ Self::SupPPTOType , Self::GerPPTOType , Self::InsPPTOType ] ) )
            {
                return $this->warningException( 'Carga no identificada' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            
            $middleRpta = $this->uploadValidate( $inputs );
            
            if( $middleRpta[ status ] == ok )
            {
                switch( $inputs[ 'type' ] )
                {
                    case Self::SupPPTOType:
                        return $this->categoryFamilyUserUploadProcess( $inputs[ 'file' ] , $year , $inputs[ 'category' ] );
                    case Self::GerPPTOType:
                        return $this->categoryFamilyUploadProcess( $inputs[ 'file' ] , $year , $inputs[ 'category' ] );  
                    case Self::InsPPTOType:
                        $pptoProcedureModel = new PPTOProcedure;
                        return $pptoProcedureModel->insPPTOTransaction( $inputs[ 'amount' ] , $year );
                    default:
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
            'year.min' => 'El año ' . $inputs[ 'year' ] . ' es menor que el actual',
        ];

        $validator = Validator::make( $inputs , $rules , $messages );

        $type = $inputs[ 'type' ];

        $validator->sometimes( [ 'file' ] , 'required|mimes:xls,xlsx' , function() use( $type )
        {
            return in_array( $type , [ Self::SupPPTOType , Self::GerPPTOType ] );
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

    private function validateRowGer( $inputs , $familiesId )
    {
        $rules =
        [
            'monto'   => 'required|numeric|min:0',
            'cod129'  => 'required|numeric|in:' . $familiesId ,
        ];

        $messages =
        [
            'cod129.in'  => 'La familia (codigo:' . $inputs[ 'cod129' ] . ') no figura en el PPTO de Ventas.'
        ];

        $validator = Validator::make( $inputs , $rules , $messages );
        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        };
        return $this->setRpta();
    }

    /*private function categoryUploadProcess( $amount , $year , $category )
    {
        return PPTOProcedure::insProcedure( $amount , $year );
    }*/

    private function categoryFamilyUploadProcess( $file , $year , $category )
    {
        $fileData = Excel::selectSheetsByIndex( 0 )->load( $file )->get();

        include( app_path() . '/models/Query/QueryProducts.php' );            
        $familiesId = implode( $qryProducts->lists( 'id' ) , ',' );

        $uniqueArray = [];
        $warnings = [];
        foreach( $fileData as $key => $row )
        {
            $middleRpta = $this->validateRowGer( $row->toArray() , $familiesId );
            if( $middleRpta[ status ] != ok )
            {
                $warnings[] = 'Fila N° ' . ( $key + 2 ) . '. ' . $middleRpta[ description ];
            }

            if( $key != 0 )
            {
                $sameKey = array_search( $row->cod129 , $uniqueArray );
                if( $sameKey !== FALSE )
                {
                    $warnings[] = 'El campo COD129 es igual en las filas N° ' . ( $sameKey + 2 ) . ' y ' . ( $key + 2 );
                    unset( $uniqueArray[ $sameKey ] );
                }
            }
            $uniqueArray[] = $row->cod129;
        }

        if( ! empty( $warnings ) )
        {
            $rpta = $this->warningException( 'Se encontraron las siguientes observaciones en la carga del PPTO:' , __FUNCTION__ , __LINE__ , __FILE__ );
            $rpta[ 'List' ] = [ 'Class' => 'list-group-item-warning' , 'Detail' => $warnings ];
            return $rpta;
        }

        $pptoProcedure = new PPTOProcedure;
        return $pptoProcedure->gerPPTOTransaction( $fileData , $year , $category );

    }

    public function categoryFamilyUserUploadProcess( $fileData , $year , $category )
    {
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
        elseif( $type = Self::GerPPTOType )
        {
            $fundCategoryIds = FondoSubCategoria::select( 'id' )
                    ->whereIn( 'trim( tipo )' , [ GER_PROD , GER_PROM ] )
                    ->lists( 'id' );
            return implode( $fundCategoryIds , ',' );  
        }
        return 0;
    }

    private function validateUpdate( $inputs )
    {
        $rules =
        [
            'monto'   => 'required|numeric|min:0'
        ];

        $messages =
        [
            'monto.numeric'  => 'El monto ingresado no es un valor numerico',
            'monto.min'      => 'El monto debe ser mayor o igual a 0'
        ];

        $validator = Validator::make( $inputs , $rules , $messages );
        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        };
        return $this->setRpta();
    }

}
