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

class TableController extends BaseController
{

	private function getModel( $type )
	{
		switch( $type ):
			case 'Fondo':
				return array( 'model' => new Fondo() , 'id' => MANTENIMIENTO_FONDO );
			case 'Cuenta_Gasto_Marca':
				return array( 'model' => new MarkProofAccounts , 'id' => 1 );
			case 'Parametro':
				return array( 'model' => new Parameter , 'id' => 2 );
			case 'Fondo_Supervisor':
				return array( 'model' => new FondoSupervisor , 'id' => 3 );
			case 'Fondo_Gerente_Producto':
				return array( 'model' => new FondoGerProd , 'id' => 4 );
			case 'Fondo_Institucion':
				return array( 'model' => new FondoInstitucional , 'id' => 5 );
			case 'Tipo_Inversion':
				return array( 'model' => new InvestmentType , 'id' => 7 );
			case 'Tipo_Actividad':
				return array( 'model' => new Activity , 'id' => 8 );
			case 'Inversion_Actividad':
				return array( 'model' => new InvestmentActivity , 'id' => 9 );
			case 'Tipo_Cliente':
				return array( 'model' => new ClientType , 'key' => 'descripcion' );
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
			/*switch( $inputs['type'] ):
				case 'idusertype':
					return $this->getCellTipoUsuario( $inputs['val'] );
				case 'idfondo':
					return $this->getCellFondo( $inputs['val'] );
				case 'iddocumento':
					return $this->getCellTipoDocumento( $inputs['val'] );
				case 'id_moneda':
					return $this->getCellTipoMoneda( $inputs['val'] );
				case 'tipo_cliente':
					return $this->getCellClient( $inputs[ 'val' ] );
				case 'id_inversion':
					return $this->getCellInversion( $inputs[ 'val' ] );
				case 'id_actividad':
					return $this->getCellActividad( $inputs[ 'val' ] );
			endswitch;*/
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function getCellInversion( $val )
	{
		$data = array( 'datos' => InvestmentType::all() , 'val' => $val , 'key' => 'nombre' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );
	
	}

	private function getCellActividad( $val )
	{
		$data = array( 'datos' => Activity::all() , 'val' => $val , 'key' => 'nombre' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );	
	}

	private function getCellClient( $val )
	{
		$data = array( 'datos' => ClientType::all() , 'val' => $val , 'key' => 'descripcion' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );
	}

	private function getCellTipoMoneda( $val )
	{
		$data = array( 'datos' => TypeMoney::all() , 'val' => $val , 'key' => 'simbolo' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );
	}

	private function getCellTipoDocumento( $val )
	{
		$data = array( 'datos' => Proof::all() , 'val' => $val , 'key' => 'codigo' );
		return $this->setRpta( View::make( 'Maintenance.td' )->with( $data )->render() );
	}

	private function getCellTipoUsuario( $val )
	{
		$data = array( 'datos' => TypeUser::dmkt() , 'val' => $val , 'key' => 'descripcion' );
		return $this->setRpta( View::make( 'Maintenance.Fondo.usertype')->with( $data )->render() );
	}

/*	private function getCellFondo( $val )
	{
		$data = array( 'datos' => Fondo::order() , 'val' => $val , 'key' => 'nombre' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );
	}*/

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

	//FONDO CONTABILIDAD

	public function getView( $type )
	{
		\Log::error( $type );
		$vData       = $this->getModel( $type );
		\Log::error( $vData );
		$model  	 = $vData[ 'model' ];
		$id          = $vData[ 'id' ];
		$records     = $model::order();
		$maintenance = Maintenance::find( $id );
		$columns = json_decode( $maintenance->formula );
		return View::make( 'Maintenance.view' , 
			array( 
				'records' => $records , 
				'columns' => $columns , 
				'titulo'  => 'Mantenimiento de ' . $maintenance->descripcion , 
				'type'    => $type
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

	public function updateMaintenanceData()
	{
		try
		{
			$inputs = Input::all();
			\Log::error( $inputs );
			switch( $inputs[ 'type' ] ):
				case 'fondo':
					return $this->updateFondo( $inputs );
				case 'cuentasMarca':
					return $this->updateCuentasMarca( $inputs );
				case 'fondo-cuenta':
					return $this->updateFondo( $inputs);
			endswitch;
			return $this->updateGeneric( $inputs );
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function updateGeneric( $val )
	{
		$model  = $this->getModel( $val[ 'type' ] )[ 'model' ];
		$record = $model::find( $val['id'] );
		foreach ( $val[data] as $key => $data )
			$record->$key = $data ;
		$record->save();
		return $this->setRpta();
	}

	private function saveMaintenance( $inputs )
	{
		$vData = $this->getModel( $inputs[ 'type' ] );
		$record = $vData[ 'model' ];
		$record->id = $record->lastId() + 1 ;
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

	//////////////////////////////////////////////////////////////////////

	public function getTableDataInversion()
	{
		$inversion = InvestmentType::withTrashed()->orderBy( 'deleted_at' ,'desc' , 'updated_at' , 'desc')->get();
		return View::make( 'Maintenance.Investment.table')->with( 'inversion' , $inversion );
	}
	public function getTableDataActivity()
	{
		$actividad = Activity::withTrashed()->orderBy( 'deleted_at' ,'desc' , 'updated_at' , 'desc')->get();
		return View::make( 'Maintenance.Activity.table' )->with( 'actividad' , $actividad );

	}
	public function getTableDataInvestmentActivity()
	{
		$inversionActividad = InvestmentActivity::withTrashed()->orderBy( 'deleted_at' ,'desc' , 'updated_at' , 'desc')->get();
		return View::make( 'Maintenance.InvestmentActivity.table')->with( 'inversion_actividad' , $inversionActividad );
	}

	private function getDailySeatRelation()
	{
		$records = MarkProofAccounts::all();
		$columns = Maintenance::find(1);
		$columns = json_decode( $columns->formula );
		return $this->setRpta( View::make( 'Maintenance.table' )->with( array( 'records' => $records , 'columns' => $columns , 'type' => 'cuentasMarca' ) )->render() );
	}

	public function getSupFunds()
	{
		$records = FondoSupervisor::order();
		$columns = Maintenance::find( 3 );
		$columns = json_decode( $columns->formula );
		return View::make( 'Maintenance.table' , array( 'records' => $records , 'columns' => $columns , 'type' => 'FondosSupervisor' , 
														'titulo' => 'Fondos de Supervisor' , 'add' => false ) );	
	}

	public function getGerProdFunds()
	{
		$records = FondoGerProd::order();
		$columns = Maintenance::find( 4 );
		$columns = json_decode( $columns->formula );
		return View::make( 'Maintenance.table' , array( 'records' => $records , 'columns' => $columns ,	'type' 	  => 'FondosGerProd',
														'titulo'  => 'Fondos de Gerente de Producto' , 'add'     => false ) );
	}

	public function getInstitutionFunds()
	{
		$records = FondoInstitucional::order();
		$columns = Maintenance::find( 5 );
		$columns = json_decode( $columns->formula );
		return View::make( 'Maintenance.table' , array( 'records' => $records , 'columns' => $columns ,	'type' => 'FondosInstitution',
														'titulo' => 'Fondos de Institucion', 'add' => false ) );
	}

	private function getFondoAccount()
	{
		$fondos = Fondo::order();
		return $this->setRpta( View::make( 'Maintenance.FondoCuenta.table' )->with( 'fondos' , $fondos )->render() );
	}

	private function getInversion()
	{
		$inversion = InvestmentType::withTrashed()->orderBy( 'deleted_at' ,'desc' , 'updated_at' , 'desc')->get();
		return $this->setRpta( View::make( 'Maintenance.Investment.table')->with( 'inversion' , $inversion )->render() );
	}

	private function getActividad()
	{
		$actividad = Activity::withTrashed()->orderBy( 'deleted_at' ,'desc' , 'updated_at' , 'desc')->get();
		return $this->setRpta( View::make( 'Maintenance.Activity.table' )->with( 'actividad' , $actividad )->render() );
	}
	
	private function getInvestmentActivity()
	{
		$inversionActividad = InvestmentActivity::withTrashed()->orderBy( 'deleted_at' ,'desc' , 'updated_at' , 'desc')->get();
		return $this->setRpta( View::make( 'Maintenance.InvestmentActivity.table')->with( 'inversion_actividad' , $inversionActividad )->render() );
	}

	private function validateFondoSaldoNeto( $fondo )
	{
		if ( $fondo->saldo < $fondo->saldo_neto )
			return $this->warningException( 'No puede asignar un saldo menor al saldo reservado por las solicitudes' , __FUNCTION__ , __LINE__ , __FILE__ );
		else
			return $this->setRpta();
	}

	private function updateFondosSupervisor( $val )
	{
		$fondoSup = FondoSupervisor::find( $val[ 'id' ] );
		$oldSaldo = $fondoSup->saldo;
		
		foreach( $val[ data ] as $key => $data )
			$fondoSup->$key = $data;
		
		$middleRpta = $this->validateFondoSaldoNeto( $fondoSup );
		if ( $middleRpta[ status ] == ok )
		{
			$fondoSup->save();
			$fondoMktHistory = new FondoMktHistory;
			$fondoMktHistory->id = $fondoMktHistory->nextId();
			$fondoMktHistory->id_to_fondo = $val[ 'id' ];
			$fondoMktHistory->to_old_saldo = $oldSaldo;
			$fondoMktHistory->to_new_saldo = $fondoSup->saldo;
			$fondoMktHistory->id_fondo_history_reason = FONDO_AJUSTE;
			$fondoMktHistory->id_tipo_to_fondo = SUP;
			$fondoMktHistory->save();
			return $this->setRpta();
		}
		return $middleRpta;
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

	private function updateparametro( $val )
	{
		$parametro = Parameter::find( $val[ 'id' ] );
		foreach( $val[ data ] as $key => $data )
			$parametro->$key = $data;
		$parametro->save();
		return $this->setRpta();
	}

	private function updateinversion( $val )
	{
		$inversion = InvestmentType::find( $val['id'] );
		foreach ( $val[data] as $key => $data )
			$inversion->$key = $data;
		$inversion->save();
		return $this->setRpta();
	}

	private function updateactividad( $val )
	{
		$actividad = Activity::find( $val['id'] );
		foreach ( $val[data] as $key => $data )
			$actividad->$key = $data;
		$actividad->save();
		return $this->setRpta();
	}

	private function updateinversionactividad( $val )
	{
		$investmentActivity = InvestmentActivity::find( $val['id'] );
		foreach ( $val[data] as $key => $data )
			$investmentActivity->$key = $data;
		$investmentActivity->save();
		return $this->setRpta();
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

	private function maintenanceSaveActividad( $val )
	{
		$actividad = new Activity;
		$actividad->id = $actividad->nextId();
		foreach( $val[data] as $key => $data )
			$actividad->$key = $data;
		$actividad->save();
		DB::commit();
		return $this->setRpta();
	}

	private function maintenanceSaveInversion( $val )
	{
		$investment = new InvestmentType;
		$investment->id = $investment->nextId();
		foreach( $val[data] as $key => $data )
			$investment->$key = $data;
		$investment->save();
		DB::commit();
		return $this->setRpta();
	}

	private function maintenanceSaveInvestmentActivity( $val )
	{
		$investmentActivity = new InvestmentActivity;
		$investmentActivity->id = $investmentActivity->nextId();
		foreach( $val[data] as $key => $data )
			$investmentActivity->$key = $data;
		$investmentActivity->save();
		DB::commit();
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

	private function addactividad()
	{
		$data = array( 'tipo_cliente' => ClientType::all() );
		return $this->setRpta( View::make( 'Maintenance.Activity.tr')->with( $data )->render() );		
	}

	private function addcuentasMarca()
	{
		$data = array( 'fondos' => Fondo::all() , 'docs' => Proof::all() );
		return $this->setRpta( View::make( 'Maintenance.Cuentasmarca.tr')->with( $data )->render() );
	}

	private function addfondo()
	{
		$data = array( 'datos' => TypeUser::dmkt() );
		return $this->setRpta( View::make( 'Maintenance.Fondo.tr')->with( $data )->render() );
	}

	private function addinversion()
	{
		$data = array();
		return $this->setRpta( View::make( 'Maintenance.Investment.tr')->with( $data )->render() );
	}

	private function addinversionactividad()
	{
		$data = array( 'actividades' => Activity::withTrashed()->get() , 'inversiones' => InvestmentType::withTrashed()->get() );
		return $this->setRpta( View::make( 'Maintenance.InvestmentActivity.tr')->with( $data )->render() );	
	}

	public function enableRecord()
	{
		try
		{
			$inputs = Input::all();
			switch( $inputs['type'] )
			{
				case 'actividad':
					$this->enableActividad( $inputs['id'] );
					break;
				case 'inversion':
					$this->enableInversion( $inputs['id'] );
					break;
				case 'inversionactividad':
					$this->enableInvestmentActivity( $inputs['id'] );
					break;
			}
			return $this->setRpta();
		}
		catch( Exception $e )
		{
			return $this->internalException();
		}
	}

	private function enableActividad( $id )
	{
		Activity::withTrashed()->find( $id )->restore();
	}

	public function enableInversion( $id )
	{
		InvestmentType::withTrashed()->find( $id )->restore();
	}

	public function enableInvestmentActivity( $id )
	{
		InvestmentActivity::withTrashed()->find( $id )->restore();
	}

	public function disableRecord()
	{
		try
		{
			$inputs = Input::all();
			switch( $inputs['type'] )
			{
				case 'actividad':
					$this->disableActividad( $inputs['id'] );
					break;
				case 'inversion':
					$this->disableInversion( $inputs['id'] );
					break;
				case 'inversionactividad':
					$this->disableInvestmentActivity( $inputs['id'] );
					break;
			}
			return $this->setRpta();
		}
		catch( Exception $e )
		{
			return $this->internalException();
		}
	}

	public function disableActividad( $id )
	{
		Activity::find( $id )->delete();
	}

	public function disableInversion( $id )
	{
		InvestmentType::find( $id )->delete();
	}

	public function disableInvestmentActivity( $id )
	{
		InvestmentActivity::find( $id )->delete();
	}

} 