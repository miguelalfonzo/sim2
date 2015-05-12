<?php

namespace Source;

use Dmkt\Solicitude;
use Dmkt\Rm;
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

class Seeker extends BaseController 
{
	
	/*private function clientsTables()
	{
		$tables = array( 'VTA.CLIENTES' , 'SIP.MEDICOS2' );
		$wheres = array( 'clcodigo , clnombre,clrutholding','rutmed,patmed,matmed,nommed');
		$selects = array('clcodigo,clnombre',"rutmed,(patmed||' '||matmed||' '||nommed)");
		$sJson = array();
		for ($i=0;$i<count($tables) ;$i++)
		{
			$tab = array();
			$tab['name'] = $tables[$i];
			$where = explode(',',$wheres[$i]);
			$tab['wheres'] = $where;
			$select = explode(',',$selects[$i]);
			$tab['selects'] = $select;
			$sJson[] = $tab;
		}
		return json_encode($sJson);
	}*/

	public function clientSource()
	{
		try
		{
			$inputs = Input::all();
			$json = ' [{"name":"FICPE.PERSONAFIS" , '.
					' "wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"], '.
					' "equal":{"PEFESTADO":1}},'.
					' "selects":["PEFCODPERS","( PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)" , "\'DOCTOR\'" ]}, '.
					' {"name":"FICPEF.PERSONAJUR",'.
					' "wheres":{"likes":["PEJNRODOC","PEJRAZON"], '.
					' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":1 }}, '.
					' "selects":["PEJCODPERS","( PEJNRODOC || \'-\' || PEJRAZON )" , "\'FARMACIA\'" ]}, '.
					' {"name":"FICPE.PERSONAJUR",'.
					' "wheres":{"likes":[ "PEJRAZON" ], '.
					' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":2 }}, '.
					' "selects":["PEJCODPERS","PEJRAZON" , "\'INSTITUCION\'" ]}, '.
					' {"name":"VTADIS.CLIENTES",'.
					' "wheres":{"likes":[ "CLRUT" , "CLNOMBRE" ], '.
					' "equal":{ "CLESTADO":1 }, '.
					' "in":{ "CLCLASE": [ 1 , 6 ] } }, '.
					' "selects":[ "CLCODIGO" , " ( CLRUT || \'-\' || CLNOMBRE ) " , "CASE WHEN CLCLASE = 1 THEN \'DISTRIBUIDOR\' WHEN CLCLASE = 6 THEN \'BODEGA\' END" ]} '.
					']';
	    	$cAlias = array( 'value' , 'label' , 'type' );
	    	return Response::Json( $this->searchSeeker( $inputs['sVal'] , $json , $cAlias ) );
		}
		catch (Exception $e)
		{
			$rpta = $this->internalException($e,__FUNCTION__);
		}
	}

	public function repInfo()
	{
		try
		{
			$inputs = Input::all();
			$rm = Visitador::find($inputs['rm']);
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
			$json = '[{"name":"FICPE.VISITADOR","wheres":{"likes":["VISLEGAJO","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)"],"equal":{"VISACTIVO":"S","LENGTH(VISLEGAJO)":8}},"selects":["VISVISITADOR","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)"]}]';
			$cAlias = array('value','label');
	    	
			return $this->searchSeeker( $inputs['sVal'] , $json , $cAlias );
			/*$like = "VISNOMBRE || ' ' || VISMATERNO || ' ' || VISPATERNO";
	    	$reps = DB::TABLE('FICPE.VISITADOR')->SELECT('VISVISITADOR')->WHERE("UPPER(".$like.")","like","%".strtoupper($sVal)."%")->get();
	    	return $reps;
	    	$rpta = $this->searchSeeker($inputs['sVal'],$json);*/
		}
		catch (Exception $e)
		{
			return $this->internalException( $e , __FUNCTION__ );
		}
		//return Response::Json($rpta);
	}

    private function searchSeeker($inputs,$json,$cAlias)
    {
    	try
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
			    			Log::error( $key );
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
			    				{
			    					Log::error( 'IN ');
			    					Log::error( $key );
			    					Log::error( $in );
			    					$query->whereIn( $key , $in );
			    				}
			    			}
			    		}
			    		/*foreach ( $table->wheres->equal as $key => $value )
			    			$query->where( $key,$value);
			    		foreach ( $table->wheres->in as $key => $value )
			    			$query->whereIn( $key , $value );*/
			    		for ( $i=0 ; $i< count( $cAlias ) ; $i++ )
			    			$select = $select. ' ' .$table->selects[$i]. ' as "' .$cAlias[$i]. '",';			
			    		$select = substr($select,0,-1);
			    		$query->select(DB::raw($select));
			    		$query->take(2);
			    		$tms = $query->get();
			    		for ($i=0; $i < count($tms); $i++)
			    			$tms[$i]->table = $table->name;
			    		$array = array_merge($tms,$array);
			    	}	    		
			    	return $this->setRpta($array);
			    }
			    else
			    	return $this->warningException( __FUNCTION__ , 'Json: Formato Incorrecto' );
		    }
		    else
		    	return $this->warningException( __FUNCTION__ , 'Input Vacio (Post: "Json" Vacio)');   
	    }
	    catch (Exception $e)
	   	{
	    	return $this->internalException($e,__FUNCTION__);
    	}
    }

    public function getClientView()
    {
    	$inputs = Input::all();
    	$rules = array(
    		'value' => 'required|numeric|min:1',
    		'label' => 'required|min:1',
    		'table'	=> 'required|min:1',
    		'type'	=> 'required|min:1'
    	);
    	$validator = Validator::make( $inputs[ 'data' ] , $rules );
            if ($validator->fails()) 
                return $this->warningException( __FUNCTION__ , substr($this->msgValidator($validator) , 0 , -1 ) );
            else
    			return $this->setRpta( View::make( 'Seeker.client' )->with( $inputs['data'] )->render() );
    }
}
