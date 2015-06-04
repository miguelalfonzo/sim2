<?php

namespace Maintenance;

use \BaseController;
use \Input;
use \View;
use \Common\TypeUser;
use \Common\Fondo;
use \Expense\Proof;
use \Expense\MarkProofAccounts;
use \Expense\Mark;
use \Dmkt\Account;
use \Expense\PlanCta;
use \Common\TypeMoney;
use \DB;
use \Log;
use \Exception;

class TableController extends BaseController
{
	public function getMaintenanceCellData()
	{
		try
		{
			$inputs = Input::all();
			switch( $inputs['type'] )
			{
				case 'idusertype':
					return $this->maintenanceGetTipoUsuario( $inputs['val'] );
				case 'idfondo':
					return $this->maintenanceGetFondo( $inputs['val'] );
				case 'iddocumento':
					return $this->maintenanceGetTipoDocumento( $inputs['val'] );
				case 'id_moneda':
					return $this->maintenanceGetTipoMoneda( $inputs['val'] );
			}
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function maintenanceGetTipoMoneda( $val )
	{
		$data = array( 'datos' => TypeMoney::all() , 'val' => $val , 'key' => 'simbolo' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );
	}

	private function maintenanceGetTipoDocumento( $val )
	{
		$data = array( 'datos' => Proof::all() , 'val' => $val , 'key' => 'codigo' );
		return $this->setRpta( View::make( 'Maintenance.td' )->with( $data )->render() );
	}

	private function maintenanceGetTipoUsuario( $val )
	{
		$data = array( 'datos' => TypeUser::dmkt() , 'val' => $val , 'key' => 'descripcion' );
		return $this->setRpta( View::make( 'Maintenance.Fondo.usertype')->with( $data )->render() );
	}

	private function maintenanceGetFondo( $val )
	{
		$data = array( 'datos' => Fondo::order() , 'val' => $val , 'key' => 'nombre' );
		return $this->setRpta( View::make( 'Maintenance.td')->with( $data)->render() );
	}

	public function getMaintenanceTableData()
	{
		try
		{
			$inputs = Input::all();
			switch( $inputs['type'] )
			{
				case 'cuentas-marca':
					return $this->getDailySeatRelation();
				case 'fondo':
					return $this->getFondos();
				case 'fondo-cuenta':
					return $this->getFondoAccount();
			}
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	private function getFondos()
    {
        $fondos = Fondo::order();
        return $this->setRpta( View::make('Maintenance.Fondo.table')->with('fondos' , $fondos)->render() );
    }

	private function getDailySeatRelation()
	{
		$iAccounts = MarkProofAccounts::all();
		return $this->setRpta( View::make( 'Maintenance.CuentasMarca.table' )->with( 'iAccounts' , $iAccounts )->render() );
	}

	private function getFondoAccount()
	{
		$fondos = Fondo::order();
		return $this->setRpta( View::make( 'Maintenance.FondoCuenta.table' )->with( 'fondos' , $fondos )->render() );
	}

	public function updateMaintenanceData()
	{
		try
		{
			$inputs = Input::all();
			switch( $inputs['type'] )
			{
				case 'fondo':
					return $this->maintenanceUpdateFondo( $inputs );
				case 'cuentas-marca':
					return $this->maintenanceUpdateCuentasMarca( $inputs );
				case 'fondo-cuenta':
					return $this->maintenanceUpdateFondo( $inputs);
			}
		}
		catch ( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
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

	private function maintenanceUpdateCuentasMarca( $val )
	{
		$accountsMark = MarkProofAccounts::find($val['id'] );
		Log::error( $val );
		/*$val[data]['idcuentafondo'] = $this->getAccount( $val[data]['idcuentafondo'] )[data];
		$val[data]['idcuentagasto'] = $this->getAccount( $val[data]['idcuentagasto'] )[data];
		$val[data]['idmarca'] = $this->getMark( $val[data]['idmarca'] )[data];
*/
		foreach ( $val[data] as $key => $data )
			$accountsMark->$key = $data;
		$accountsMark->save();
		return $this->setRpta();
	}

	private function maintenanceUpdateFondo( $val )
	{
		$fondo = Fondo::find( $val['id'] );
		Log::error( $val );
		foreach ( $val[data] as $key => $data )
			$fondo->$key = $data ;
		Log::error( $fondo );
		$fondo->save();
		return $this->setRpta();
	}

	public function saveMaintenanceData()
	{
		$inputs = Input::all();
		switch( $inputs['type'] )
		{
			case 'fondo':
				return $this->maintenanceSaveFondo( $inputs );
			case 'cuentas-marca':
				return $this->maintenanceSaveCuentasMarca( $inputs );
		}
	}

	private function maintenanceSaveCuentasMarca( $val )
	{
		try
		{
			DB::beginTransaction();
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
			DB::rollback();
			return $middleRpta;
		}
		catch ( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
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

	private function updateFondo( $idfondo , $idcuenta )
	{
		try
		{
			$fondo = Fondo::where( 'idcuenta' , $idcuenta )->get();
			if ( $fondo->count() >= 1 )
				return $this->warningException( 'No se puede asignar la misma cuenta a mas de 1 Fondo' , __FUNCTION__ , __LINE__ , __FILE__ );
			else
			{
				$fondo = Fondo::find($idfondo);
				if ( is_null( $fondo->idcuenta) )
				{
					$fondo->idcuenta = $idcuenta;
					$fondo->save();
					return $this->setRpta();
				}
				else
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

	private function maintenanceSaveFondo( $val )
	{
		try
		{
			DB::beginTransaction();
			$fondo = new Fondo;
			$fondo->id = $fondo->lastId() + 1 ;
			foreach ( $val[data] as $key => $data )
				$fondo->$key = $data;
			$fondo->save();
			
			DB::commit();
			return $this->setRpta();
			
		}
		catch ( Exception $e )
		{
			DB::rollback();
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function addMaintenanceData()
	{
		$inputs = Input::all();
		switch( $inputs['type'] )
		{
			case 'fondo':
				return $this->maintenanceAddFondo();
			case 'cuentas-marca':
				return $this->maintenanceAddCuentasMarca();
		}
	}

	private function maintenanceAddCuentasMarca()
	{
		$data = array( 'fondos' => Fondo::all() , 'docs' => Proof::all() );
		return $this->setRpta( View::make( 'Maintenance.Cuentasmarca.tr')->with( $data )->render() );
	}

	private function maintenanceAddFondo()
	{
		$data = array( 'userTypes' => TypeUser::dmkt() );
		return $this->setRpta( View::make( 'Maintenance.Fondo.tr')->with( $data )->render() );
	}
} 