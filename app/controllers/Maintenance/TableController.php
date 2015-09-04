<?php

namespace Maintenance;

use \BaseController;
use \Input;
use \View;
use \Common\TypeUser;
use \Fondo\Fondo;
use \Expense\Proof;
use \Expense\MarkProofAccounts;
use \Expense\Mark;
use \Dmkt\Account;
use \Expense\PlanCta;
use \Common\TypeMoney;
use \DB;
use \Log;
use \Exception;
use \Dmkt\InvestmentType;
use \Dmkt\InvestmentActivity;
use \Dmkt\Activity;
use \Client\ClientType;
use \Parameter\Parameter;
use \Fondo\FondoSupervisor;
use \Fondo\FondoGerProd;
use \Fondo\FondoInstitucional;
use \System\FondoMktHistory;
use \Policy\ApprovalInstanceType;

class TableController extends BaseController
{

	private function getModel( $type )
	{
		switch( $type ):
			case 'Fondo_Contable':
				return array( 'model' => new Fondo , 'id' => MANTENIMIENTO_FONDO , 'key' => 'nombre' , 'add' => true );
			case 'Cuenta_Gasto_Marca':
				return array( 'model' => new MarkProofAccounts , 'id' => 1 , 'add' => true );
			case 'Parametro':
				return array( 'model' => new Parameter , 'id' => 2 , 'add' => false );
			case 'Fondo_Supervisor':
				return array( 'model' => new FondoSupervisor , 'id' => 3 , 'add' => true );
			case 'Fondo_Gerente_Producto':
				return array( 'model' => new FondoGerProd , 'id' => 4 , 'add' => true );
			case 'Fondo_Institucion':
				return array( 'model' => new FondoInstitucional , 'id' => 5 , 'add' => true );
			case 'Tipo_Inversion':
				return array( 'model' => new InvestmentType , 'id' => 7 , 'key' => 'nombre' , 'add' => true );
			case 'Tipo_Actividad':
				return array( 'model' => new Activity , 'id' => 8 , 'key' => 'nombre' , 'add' => true );
			case 'Inversion_Actividad':
				return array( 'model' => new InvestmentActivity , 'id' => 9 , 'add' => true );
			case 'Tipo_Cliente':
				return array( 'model' => new ClientType , 'key' => 'descripcion' , 'add' => true );
			case 'Tipo_Instancia_Aprobacion':
				return array( 'model' => new ApprovalInstanceType , 'key' => 'descripcion' , 'add' => true );
			case 'Documento':
				return array( 'model' => new Proof , 'key' => 'codigo' , 'add' => false );
		endswitch;
	}

	private function getName( $function , $type )
	{
		if( $type === 1 )
			return substr( $function , 7 );
		elseif ( $type === 4 )
			return substr( $function , 3 );
	}

	public function getMaintenanceCellData()
	{
		try
		{
			$inputs = Input::all();
			$vData = $this->getModel( $inputs[ 'type'] );
			$data = array( 'datos' => $vData[ 'model']::all() , 'val' => $inputs[ 'val' ] , 'key' => $vData[ 'key' ] );
			return $this->setRpta( View::make( 'Maintenance.td' , $data )->render() );
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function getTableDailySeatRelation()
	{
		$records = MarkProofAccounts::all();
		$columns = Maintenance::find(1);
		$columns = json_decode( $columns->formula );
		return View::make( 'Maintenance.table' )->with( array( 'records' => $records , 'columns' => $columns , 'type' => 'cuentasMarca' , 'titulo' => 'Mantenimiento de Cuentas - Marca' ) );
	}

	public function getTableParameter()
	{
		$records = Parameter::all();
		$columns = Maintenance::find(2);
		$columns = json_decode( $columns->formula );
		return View::make( 'Maintenance.table' )->with( array( 'records' => $records , 'columns' => $columns , 'type' => 'parametro' , 'titulo' => 'Mantenimiento de Parametros' ) );
	}

	public function getView( $type )
	{
		$vData       = $this->getModel( $type );
		$model  	 = $vData[ 'model' ];
		$id          = $vData[ 'id' ];
		
		if( $type == 'Inversion_Actividad' )
			$records = $model::has( 'activity' )->has('investment' )->get();
		else
			$records = $model::orderWithTrashed();
		
		$maintenance = Maintenance::find( $id );
		$columns = json_decode( $maintenance->formula );
		return View::make( 'Maintenance.view' , 
			array( 
				'records' => $records , 
				'columns' => $columns , 
				'titulo'  => 'Mantenimiento de ' . $maintenance->descripcion , 
				'type'    => $type , 
				'add'	  => $vData[ 'add' ]
			) 
		);		
	}

	public function getMaintenanceTableData()
	{
		try
		{
			$inputs = Input::all();
			return $this->getTable( $inputs );
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function getTable( $inputs )
	{
		$vData       = $this->getModel( $inputs[ 'type' ] );
		$model  	 = $vData[ 'model' ];
		$id          = $vData[ 'id' ];
		$records     = $model::order();
		$maintenance = Maintenance::find( $id );
		$columns = json_decode( $maintenance->formula );
		return $this->setRpta( 
			View::make( 'Maintenance.table' , 
				array( 
					'records' => $records , 
					'columns' => $columns , 
					'type'    => $inputs[ 'type' ]
				) 
			)->render()
		);
	}

	private function updateFondoMkt( $inputs )
	{
		DB::beginTransaction();
		$middleRpta = $this->updateGeneric( $inputs );
		$data   = $middleRpta[ data ];
		$middleRpta = $this->validateFondoSaldoNeto( $data[ 'newRecord' ] );
		if ( $middleRpta[ status ] == ok ):
			$this->setFondoMktHistory( $data , $inputs[ 'type' ] );
			DB::commit();
		else:
			DB::rollback();
		endif;
		return $middleRpta;
	}

	public function updateMaintenanceData()
	{
		try
		{
			$inputs = Input::all();
			switch( $inputs[ 'type' ] ):
				case 'Fondo_Gerente_Producto':
					return $this->updateFondoMkt( $inputs );
				case 'Fondo_Institucion':
					return $this->updateFondoMkt( $inputs );
				case 'Fondo_Supervisor':
					return $this->updateFondoMkt( $inputs );
			endswitch;
			$this->updateGeneric( $inputs );
			return $this->setRpta();
		}
		catch ( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function updateGeneric( $val )
	{
		$model  = $this->getModel( $val[ 'type' ] )[ 'model' ];
		$record = $model::withTrashed()->find( $val['id'] );
		$oldRecord = json_decode( $record->toJson() );
		foreach ( $val[data] as $key => $data )
			$record->$key = $data ;
		$record->save();
		return $this->setRpta( array( 'oldRecord' => $oldRecord , 'newRecord' => $record ) );
	}

	private function validateFondoSaldoNeto( $fondo )
	{
		if ( $fondo->saldo < $fondo->saldo_neto )
			return $this->warningException( 'No puede asignar un saldo menor al saldo reservado por las solicitudes' , __FUNCTION__ , __LINE__ , __FILE__ );
		else
			return $this->setRpta();
	}

	private function setFondoMktHistory( $fondos , $type )
	{
		$fondoMktHistory                          = new FondoMktHistory;
		$fondoMktHistory->id                      = $fondoMktHistory->nextId();
		$fondoMktHistory->id_to_fondo             = $fondos[ 'newRecord' ]->id ;
		$fondoMktHistory->to_old_saldo            = $fondos[ 'oldRecord' ]->saldo;
		$fondoMktHistory->to_new_saldo            = $fondos[ 'newRecord' ]->saldo;
		$fondoMktHistory->to_old_saldo_neto       = $fondos[ 'oldRecord' ]->saldo_neto;
		$fondoMktHistory->to_new_saldo_neto       = $fondos[ 'newRecord' ]->saldo_neto;
		$fondoMktHistory->id_fondo_history_reason = FONDO_AJUSTE;
		$fondoMktHistory->id_tipo_to_fondo        = $this->getFondoType( $type );
		$fondoMktHistory->save();
	}

	private function getFondoType( $type )
	{
		if ( $type == 'Fondo_Supervisor' )
			return SUP;
		elseif ( $type == 'Fondo_Gerente_Producto' )
			return GER_PROD;
		elseif( $type == 'Fondo_Institucion' )
			return 'I';
	}

	private function updateFondosSupervisor( $val )
	{
		$fondoSup = FondoSupervisor::find( $val[ 'id' ] );
		$oldSaldo = $fondoSup->saldo;
		
		foreach( $val[ data ] as $key => $data )
			$fondoSup->$key = $data;
		
		$middleRpta = $this->validateFondoSaldoNeto( $fondoSup );
		
	}

	private function updateFondosGerProd( $val )
	{
		$fondoGerProd = FondoGerProd::find( $val[ 'id' ] );
		$oldSaldo = $fondoGerProd->saldo;
		
		foreach( $val[ data ] as $key => $data )
			$fondoGerProd->$key = $data;
		
		$middleRpta = $this->validateFondoSaldoNeto( $fondoSup );
		if ( $middleRpta[ status ] == ok )
		{
			$fondoGerProd->save();	
			$fondoMktHistory = new FondoMktHistory;
			$fondoMktHistory->id = $fondoMktHistory->nextId();
			$fondoMktHistory->id_to_fondo = $val[ 'id' ];
			$fondoMktHistory->to_old_saldo = $oldSaldo;
			$fondoMktHistory->to_new_saldo = $fondoGerProd->saldo;
			$fondoMktHistory->id_fondo_history_reason = FONDO_AJUSTE; 
			$fondoMktHistory->id_tipo_to_fondo = GER_PROD;
			$fondoMktHistory->save();
			return $this->setRpta();
		}
		return $middleRpta;
	}

	private function updateFondosInstitution( $val )
	{
		$fondoInstitution = FondoInstitucional::find( $val[ 'id' ] );
		$oldSaldo = $fondoInstitution->saldo;		
		
		foreach( $val[ data ] as $key => $data )
			$fondoInstitution->$key = $data;
		
		$middleRpta = $this->validateFondoSaldoNeto( $fondoSup );
		if ( $middleRpta[ status ] == ok )
		{
			$fondoInstitution->save();
			$fondoMktHistory = new FondoMktHistory;
			$fondoMktHistory->id = $fondoMktHistory->nextId();
			$fondoMktHistory->id_to_fondo = $val[ 'id' ];
			$fondoMktHistory->to_old_saldo = $oldSaldo;
			$fondoMktHistory->to_new_saldo = $fondoInstitution->saldo;
			$fondoMktHistory->id_fondo_history_reason = FONDO_AJUSTE; 
			$fondoMktHistory->id_tipo_to_fondo = FONDO_SUBCATEGORIA_INSTITUCION;
			$fondoMktHistory->save();
			return $this->setRpta();
		}
		return $middleRpta;
	}


	private function saveMaintenance( $inputs )
	{
		$vData = $this->getModel( $inputs[ 'type' ] );
		$record = $vData[ 'model' ];
		$record->id = $record->nextId();
		foreach ( $inputs[ data ] as $column => $data )
			$record->$column = $data;
		$record->save();
		DB::commit();
		return $this->setRpta();
	}

	public function saveMaintenanceData()
	{
		try
		{
			$inputs = Input::all();
			return $this->saveMaintenance( $inputs );
		}
		catch( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function addMaintenanceData()
	{
		$inputs = Input::all();
		switch( $inputs[ 'type' ] ):
			case 'Tipo_Actividad':
				return $this->addActividad();
			case 'Tipo_Inversion':
				return $this->addInversion();
			case 'Inversion_Actividad':
				return $this->addInversionActividad();
			case 'Cuenta_Gasto_Marca':
				return $this->addcuentasMarca();
		endswitch;
		return $this->addRow( $inputs );
	}

	private function addRow( $inputs )
	{
		$id = $this->getModel( $inputs[ 'type' ] )[ 'id' ];
		$data = array(
			'records' => json_decode( Maintenance::find( $id )->formula ),
			'type'	  => $inputs[ 'type' ]
		);
		return $this->setRpta( View::make( 'Maintenance.tr' , $data )->render() );
	}

	private function addActividad()
	{
		$data = array( 'tipo_cliente' => ClientType::all() );
		return $this->setRpta( View::make( 'Maintenance.Activity.tr')->with( $data )->render() );		
	}

	private function getDailySeatRelation()
	{
		$records = MarkProofAccounts::all();
		$columns = Maintenance::find(1);
		$columns = json_decode( $columns->formula );
		return $this->setRpta( View::make( 'Maintenance.table' )->with( array( 'records' => $records , 'columns' => $columns , 'type' => 'cuentasMarca' ) )->render() );
	}

	private function getAccount( $val )
	{
		$account = Account::where('num_cuenta' , $val )->first();
		return $this->setRpta( $account->id );
	}

	private function getMark( $val )
	{
		$mark = Mark::where('codigo' , $val )->first();
		return $this->setRpta( $mark->id );
	}

	private function updateCuentasMarca( $val )
	{
		$accountsMark = MarkProofAccounts::find($val['id'] );
		foreach ( $val[data] as $key => $data )
			$accountsMark->$key = $data;
		$accountsMark->save();
		return $this->setRpta();
	}	
	
	private function maintenanceSaveCuentasMarca( $val )
	{
		$middleRpta = $this->processAccount( $val[data]['num_cuenta_fondo'] , 1 );
		if ( $middleRpta[status] == ok )
		{
			$middleRpta = $this->processAccount( $val[data]['num_cuenta_gasto'] , 4 );
			if ( $middleRpta[status] == ok )
			{
				$middleRpta = $this->processMark( $val[data]['marca_codigo'] );
				if ( $middleRpta[status] == ok )
				{
					$accountsMark = MarkProofAccounts::orderBy('id');
					foreach ( $val[data] as $key => $data)
						$accountsMark->where( $key , $data );
					$accountsMark->get();
					if ( $accountsMark->count() == 1 )
						return $this->warningException( 'La Relacion ya existe' , __FUNCTION__ , __LINE__ , __FILE__ );
					elseif ( $accountsMark->count() == 0 )
					{
						$accountsMark = new MarkProofAccounts;
						$accountsMark->id = $accountsMark->lastId() + 1 ;
						foreach ( $val[data] as $key => $data )
							$accountsMark->$key = $data;
						$accountsMark->save();
						DB::commit();
						return $this->setRpta();
						
					}
				}
			}
		}
		return $middleRpta;
	}

	private function processMark( $val )
	{
		try
		{
			$mark = Mark::where( 'codigo' , $val )->get();
			if ( $mark->count() == 1 )
				return $this->setRpta();
			elseif ( $mark->count() == 0 )
			{
				$mark = new Mark;
				$mark->id = $mark->lastId() + 1;
				$mark->codigo = $val;
				if ( substr( $val , 0 , 1 ) == 4 )
					$mark->idtipomarca == 1;
				elseif ( substr( $val , 0 , 1) == 6 )
					$mark->idtipomarca == 2;
				$mark->save();
				
				return $this->setRpta();
			}
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}


	private function processAccount( $val , $tipocuenta )
	{
		try
		{
			$account = Account::where('num_cuenta' , $val )->get();
			if ( $account->count() == 1 )
				return $this->setRpta();
			elseif ( $account->count() == 0 )
			{
				$bagoAccount = PlanCta::find( $val );
				if ( is_null( $bagoAccount ) )
					return $this->warningException( 'La cuenta N°: '.$val.' no existe' , __FUNCTION__ , __LINE__ , __FILE__ );
				else
				{
					$account = new Account;
					$account->id = $account->lastId() + 1 ;
					$account->num_cuenta = $bagoAccount->ctactaextern;
					$account->idtipocuenta = $tipocuenta;

					$account->save();
					return $this->setRpta();
				}
			}	
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function addcuentasMarca()
	{
		$data = array( 'Tipos_Documento' => Proof::all() );
		return $this->setRpta( View::make( 'Maintenance.Cuentasmarca.tr' , $data )->render() );
	}

	private function addfondo()
	{
		$data = array( 'datos' => TypeUser::dmkt() );
		return $this->setRpta( View::make( 'Maintenance.Fondo.tr')->with( $data )->render() );
	}

	private function addInversion()
	{
		$data = array( 'Fondos_Contable' => Fondo::all() , 'Tipo_Instancias_Aprobacion' => ApprovalInstanceType::all() );
		return $this->setRpta( View::make( 'Maintenance.Investment.tr' , $data )->render() );
	}

	private function addInversionActividad()
	{
		$data = array( 'actividades' => Activity::withTrashed()->get() , 'inversiones' => InvestmentType::withTrashed()->get() );
		return $this->setRpta( View::make( 'Maintenance.InvestmentActivity.tr')->with( $data )->render() );	
	}

	public function enableRecord()
	{
		try
		{
			$inputs = Input::all();
			$vData  = $this->getModel( $inputs[ 'type' ] );
			$vData[ 'model' ]::withTrashed()->find( $inputs[ 'id' ] )->restore(); 
			return $this->setRpta();
		}
		catch( Exception $e )
		{
			return $this->internalException();
		}
	}

	public function disableRecord()
	{
		try
		{
			$inputs = Input::all();
			$vData  = $this->getModel( $inputs[ 'type' ] );
			$vData[ 'model' ]::find( $inputs[ 'id' ] )->delete(); 
			return $this->setRpta();
		}
		catch( Exception $e )
		{
			return $this->internalException();
		}
	}

} 