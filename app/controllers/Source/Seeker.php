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

class Seeker extends BaseController 
{
	
	private function clientsTables()
	{
		$tables = array('VTA.CLIENTES','SIP.MEDICOS2');
		$wheres = array('clcodigo,clnombre,clrutholding','rutmed,patmed,matmed,nommed');
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
	}

	public function clientSource()
	{
		try
		{
			$inputs = Input::all();
			$json = '[{"name":"FICPE.PERSONAFIS","wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"],"equal":{"PEFESTADO":1}},"selects":["PEFCODPERS","(\'DOCTOR: \' || PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"]},{"name":"FICPEF.PERSONAJUR","wheres":{"likes":["PEJNRODOC","PEJRAZON"],"equal":[{"PEJESTADO":1}]},"selects":["PEJCODPERS","(\'CENTRO: \' || PEJNRODOC || \'-\' || PEJRAZON)"]}]';
	    	$cAlias = array('value','label');
	    	$rpta = $this->searchSeeker($inputs['sVal'],$json,$cAlias);
		}
		catch (Exception $e)
		{
			$rpta = $this->internalException($e,__FUNCTION__);
		}
		return Response::Json($rpta);
	}

	public function repInfo()
	{
		try
		{
			$this->setRpta();
			
			Log::error('repinfo');
			$inputs = Input::all();
			$rm = Visitador::find($inputs['rm']);
			//Log::error(json_encode($rm));
			/*$sup = $rm->sup;
			Log::error($sup);
			*/
			/*$cuenta = new CtaRm;
			$cuenta = $cuenta->cuenta($rm->vislegajo)*/;
			$cuenta = $rm->cuenta;
			Log::error($cuenta);
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
			//$sVal = 'ACK';
			//\'DNI: \' || VISLEGAJO || \' - \' || 
			$json = '[{"name":"FICPE.VISITADOR","wheres":{"likes":["VISLEGAJO","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)"],"equal":{"VISACTIVO":"S","LENGTH(VISLEGAJO)":8}},"selects":["VISVISITADOR","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)"]}]';
			$cAlias = array('value','label');
	    	
			return $this->searchSeeker($inputs['sVal'],$json,$cAlias);
			//$cuenta = CtaRm::where('CODBENEFICIARO',$data)
			//$inputs = Input::all();
			//$json = '[{"name":"FICPE.VISITADOR","wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"],"equal":[{"PEFESTADO":1}]},"selects":["PEFCODPERS","(\'DOCTOR: \' || PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"]},{"name":"FICPEF.PERSONAJUR","wheres":{"likes":["PEJNRODOC","PEJRAZON"],"equal":[{"PEJESTADO":1}]},"selects":["PEJCODPERS","(\'CENTRO: \' || PEJNRODOC || \'-\' || PEJRAZON)"]}]';
	    	$like = "VISNOMBRE || ' ' || VISMATERNO || ' ' || VISPATERNO";
	    	$reps = DB::TABLE('FICPE.VISITADOR')->SELECT('VISVISITADOR')->WHERE("UPPER(".$like.")","like","%".strtoupper($sVal)."%")->get();
	    	Log::error($reps);
	    	return $reps;
	    	$rpta = $this->searchSeeker($inputs['sVal'],$json);
		}
		catch (Exception $e)
		{
			$rpta = $this->internalException($e,__FUNCTION__);
		}
		return Response::Json($rpta);
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
			    		foreach ( $table->wheres->likes as $like)
			    			$query->orWhereRaw(" UPPER(" .$like. ") like '%" .strtoupper($inputs). "%' ");
			    		foreach ($table->wheres->equal as $key=>$value)
			    			$query->where($key,$value);
			    		for ( $i=0; $i<2; $i++)
			    			$select = $select. ' ' .$table->selects[$i]. ' as "' .$cAlias[$i]. '",';				
			    		$select = substr($select,0,-1);
			    		$query->select(DB::raw($select));
			    		$query->take(4);
			    		$tms = $query->get();
			    		for ($i=0; $i < count($tms); $i++)
			    			$tms[$i]->table = $table->name;
			    		$array = array_merge($tms,$array);
			    		
			    	}
			    	$rpta = $this->setRpta($array);
			    }
			    else
			    {
			    	$rpta = array(status => warning , description => 'Json: Formato Incorrecto');
		    	}
		    }
		    else
		    {
		    	$rpta = array(status => warning , description => 'Post Input "JSON" Vacio');   
		    }
	    }
	    catch (Exception $e)
	   	{
	    	$rpta = $this->internalException($e,__FUNCTION__);
    	}
    	return $rpta;
    }
}
