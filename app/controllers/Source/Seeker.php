<?php

namespace Source;

use Dmkt\Solicitud;
use Users\Rm;
use Dmkt\Account;
use \BaseController;
use \Input;
use \DB;
use \Response;
use \Log;
use \Dmkt\CtaRm;
use \Users\Visitador;
use \View;
use \Validator;
use \Dmkt\Activity;
use \Dmkt\InvestmentActivity;
use \Dmkt\InvestmentType;
use \Client\ClientType;
use \Exception;

class Seeker extends BaseController 
{	

	public function institutionSource()
	{
		try
		{
			$inputs = Input::all();
			$json = ' [{"name":"FICPE.PERSONAJUR",'.
					' "wheres":{"likes":[ "PEJRAZON" ], '.
					' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":2 }}, '.
					' "selects":["PEJCODPERS","PEJRAZON" , "\'INSTITUCION\'" , 3 ]} '.
					']';
	    	$cAlias = array( 'value' , 'label' , 'type' , 'id_tipo_cliente' );
	    	return $this->searchSeeker( $inputs['sVal'] , $json , $cAlias );
		}
		catch( Exception $e )
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function clientSource()
	{
		try
		{
			$inputs = Input::all();
			$json = ' [{"name":"FICPE.PERSONAFIS" , '.
					' "wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"], '.
					' "equal":{"PEFESTADO":1}},'.
					' "selects":["PEFCODPERS","( PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)" , "\'DOCTOR\'" , 1 ]}, '.
					' {"name":"FICPEF.PERSONAJUR",'.
					' "wheres":{"likes":["PEJNRODOC","PEJRAZON"], '.
					' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":1 }}, '.
					' "selects":["PEJCODPERS","( PEJNRODOC || \'-\' || PEJRAZON )" , "\'FARMACIA\'" , 2 ]}, '.
					' {"name":"FICPE.PERSONAJUR",'.
					' "wheres":{"likes":[ "PEJRAZON" ], '.
					' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":2 }}, '.
					' "selects":["PEJCODPERS","PEJRAZON" , "\'INSTITUCION\'" , 3 ]}, '.
					' {"name":"VTADIS.CLIENTES",'.
					' "wheres":{"likes":[ "CLRUT" , "CLNOMBRE" ], '.
					' "equal":{ "CLESTADO":1 }, '.
					' "in":{ "CLCLASE": [ 1 , 6 ] } }, '.
					' "selects":[ "CLCODIGO" , " ( CLRUT || \'-\' || CLNOMBRE ) " , "CASE WHEN CLCLASE = 1 THEN \'DISTRIBUIDOR\' WHEN CLCLASE = 6 THEN \'BODEGA\' END" , "CASE WHEN CLCLASE = 1 THEN 4 WHEN CLCLASE = 6 THEN 5 END" ]} '.
					']';
	    	$cAlias = array( 'value' , 'label' , 'type' , 'id_tipo_cliente' );
	    	return Response::Json( $this->searchSeeker( $inputs['sVal'] , $json , $cAlias ) );
		}
		catch (Exception $e)
		{
			return $this->internalException($e,__FUNCTION__);
		}
	}

	public function repInfo()
	{
		try
		{
			$inputs = Input::all();
			$rm = Visitador::find($inputs['rm']);
			$cuenta = $rm->cuenta;
			if (count($cuenta) == 0)
				$cuenta = null;
			else
				$cuenta = $cuenta->cuenta;
			$sup = DB::table('FICPE.LINSUPVIS a')->where('LSVVISITADOR',$inputs['rm'])->leftJoin('FICPE.SUPERVISOR b','b.SUPSUPERVISOR','=','a.LSVSUPERVISOR')
			->SELECT(DB::raw("b.supsupervisor as idsup , (b.supnombre || ' ' || b.suppaterno || ' ' || b.supmaterno) as nombre"))->first();
			$data = array('cuenta' => $cuenta , 'sup' => $sup);
			$rpta = $this->setRpta($data);
		}
		catch (Exception $e)
		{
			$rpta = $this->internalException($e,__FUNCTION__);
		}
		return Response::Json($rpta);
	}

	public function repSource()
	{
		try
		{
			$inputs = Input::all();
			$json = '[{"name":"FICPE.VISITADOR","wheres":{"likes":["VISLEGAJO","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)"],"equal":{"VISACTIVO":"S","LENGTH(VISLEGAJO)":8}},"selects":["VISVISITADOR","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)" , "\'REP\'" ]}]';
			$cAlias = array('value','label' , 'type' );	
			return $this->searchSeeker( $inputs['sVal'] , $json , $cAlias );
		}
		catch (Exception $e)
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
	}

	public function userSource()
	{
		$inputs = Input::all();
		$json = '[{ "name": "OUTDVP.DMKT_RG_SUPERVISOR" , "wheres":{ "likes": ["( NOMBRES || \' \' || APELLIDOS )" ] , "notnull": ["IDUSER"] } , "selects" : [ "IDUSER" , "( NOMBRES || \' \' || APELLIDOS )" , "\'SUPERVISOR\'" ] } , ' . 
				'{ "name" : "OUTDVP.GERENTES" , "wheres":{ "likes": ["DESCRIPCION"] , "notnull" : [ "IDUSER" ] } , "selects" : [ "IDUSER" , "DESCRIPCION" , "\'G. PRODUCTO\'"] } ]';
		$cAlias = array( 'value' , 'label' , "type" );
		return $this->searchSeeker( $inputs['sVal'] , $json , $cAlias , 2 );			
	}

    private function searchSeeker( $inputs , $json , $cAlias , $type = 1 )
    {
		if (!empty($inputs))
    	{
	    	$json = json_decode($json);
    		if (json_last_error() == JSON_ERROR_NONE)
	    	{
		    	$array = array();
		    	foreach ($json as $table)
		    	{
		    		$select = '';
		    		$query = DB::table($table->name);
		    		foreach ( $table->wheres as $key => $where )
		    		{
		    			if ( $key == 'likes' )
		    			{
		    				foreach ( $where as $key => $like )
		    					$query->orWhereRaw(" UPPER(" .$like. ") like '%" .strtoupper( $inputs ). "%' ");
		    			}
		    			else if ( $key == 'equal' )
		    			{
		    				foreach ( $where as $key => $equal )
		    					$query->where( $key , $equal );
		    			}
		    			else if ( $key == 'in' )
		    			{
		    				foreach ( $where as $key => $in )
		    					$query->whereIn( $key , $in );
		    			}
		    			else if ( $key === 'notnull' )
		    			{
		    				foreach ( $where as $key => $field )
		    					$query->whereNotNull( $field );
		    			}
		    		}
		    		for ( $i=0 ; $i< count( $cAlias ) ; $i++ )
		    			$select = $select. ' ' .$table->selects[$i]. ' as "' .$cAlias[$i]. '",';			
		    		$select = substr($select,0,-1);
		    		$query->select( DB::raw( $select ) );
		    		$query->take( 50 );
		    		$tms = $query->get();
		    		foreach ( $tms as $tm )
		    			$tm->table = $table->name;
		    		$array = array_merge( $tms , $array );
		    	}
		    	if ( $type == 1 )
		    		return $this->setRpta( $array );
	    		else
	    			return $this->setRpta( array_filter( $array , array( $this , 'filterUserType' ) ) );
		    }
		    else
		    	return $this->warningException( 'Json: Formato Incorrecto' , __FUNCTION__ , __LINE__ , __FILE__ );
	    }
	    else
	    	return $this->warningException( 'Input Vacio (Post: "Json" Vacio)' , __FUNCTION__ , __LINE__ , __FILE__ );   
	}

	private function filterUserType( $var )
	{
		if ( \Auth::user()->type == SUP )
			return $var->type == 'SUPERVISOR';
		else
			return $var->type == 'G. PRODUCTO';
	}

    public function getClientView()
    {
    	try
    	{
	    	$inputs = Input::all();
	    	$rules = array(	'id_tipo_cliente' => 'required|min:1|in:'.implode( ',' , ClientType::lists('id') ) );
	    	$validator = Validator::make( $inputs[ 'data' ] , $rules );
            if ($validator->fails()) 
                return $this->warningException( substr($this->msgValidator($validator) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            else
            {
            	$tipo_cliente = $inputs[ 'data' ][ 'id_tipo_cliente'];

				$act = Activity::where('tipo_cliente' , $tipo_cliente )->lists('id');
				$inv = InvestmentActivity::whereIn( 'id_actividad' , $act )->lists( 'id_inversion');
        		
        		return $this->setRpta( array( 
        			'View' => View::make( 'Seeker.client' )->with( $inputs[ 'data' ] )->render() , 
        			'id_actividad' => $act ,
        			'id_inversion' => $inv 
        		));
            }
        }
        catch ( Exception $e )
        {
        	return $this->internalException( $e , __FUNCTION__ );
        }
    }
}
