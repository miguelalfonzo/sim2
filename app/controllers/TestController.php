<?php

use Dmkt\Solicitude;
use Dmkt\Rm;
use Dmkt\Account;

class TestController extends BaseController 
{
	public function tm()
	{
		$id = 3;
		$models = Solicitude::with('state.rangeState')->whereHas('state', function ($q) use($id)
		{
			$q->whereHas('rangeState', function ($t) use($id)
			{
				$t->where('id',$id);
			});
		})->get();
		//$m = $models->has('id');
		
		/*foreach ($models as $model)
		
			Log::error($model->state->range_state);*/
		Log::error(DB::getQueryLog());
		
		return $models;
	}

	public function withHistory()
	{
		$data = array('alias' => 'BANCOS');
		


		$account = Account::firstOrNew($data);
		if (isset($account->rn))
			return 'true';
		else
			return $account;

		$account->idcuenta = $account->searchId() + 1;
		$account->save();
		//$account->save();

		/*if (isset($account->rn))
		{
			$account->save
		}*/
		return $account;

		/*$solicitude = Solicitude::with(array('history' => function($z)
		{
			$z->orderBy('created_at','DESC')->first();	
		}))->get();*/
		/*$solicituds = Solicitude::with(array('history' => function($q)
        {
            $q->orderBy('created_at','DESC')->first();  
        }))->take(2)->get();*/
        /*$rSolicituds = Solicitude::with(array('history' => function($q)
        {
            $q->orderBy('created_at','DESC')->first();  
        }))->get();
        $solicituds->merge($rSolicituds);*/
        //Log::error(DB::getQueryLog());
        //$solicituds = Solicitude::find(44)->history()->get();

      /*  $solicituds = Rm::select('*')->where('iduser',39)->first();
		return json_encode($solicituds->rmSup);*/
	}

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
			$json = '[{"name":"FICPE.PERSONAFIS","wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"],"equal":[{"PEFESTADO":1}]},"selects":["PEFCODPERS","(\'DOCTOR: \' || PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"]},{"name":"FICPEF.PERSONAJUR","wheres":{"likes":["PEJNRODOC","PEJRAZON"],"equal":[{"PEJESTADO":1}]},"selects":["PEJCODPERS","(\'CENTRO: \' || PEJNRODOC || \'-\' || PEJRAZON)"]}]';
	    	$rpta = $this->searchSeeker($inputs['sVal'],$json);
		}
		catch (Exception $e)
		{
			$rpta = $this->internalException($e,__FUNCTION__);
		}
		return Response::Json($rpta);

	}


    private function searchSeeker($inputs,$json)
    {
    	try
    	{
	    	//$inputs = Input::all();
	    	//$inputs['sVal'] = 'rafael';
	    	if (!empty($inputs))
	    	{
		    	//$json = json_decode($this->clientsTables());
	    		//$json =  '[{"name":"FICPE.PERSONAFIS","wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"],"equal":[{"PEFESTADO":"1"}]},"selects":["PEFCODPERS","(\'DOCTOR: \' || PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"]}]';
	    		$json = json_decode($json);
	    		$cAlias = array('value','label');
		    	if (json_last_error() == JSON_ERROR_NONE)
		    	{
			    	$array = array();
			    	foreach ($json as $table)
			    	{
			    		$select = '';
			    		$query = DB::table($table->name);
			    		foreach ( $table->wheres->likes as $like)
			    			$query->orWhereRaw(" UPPER(" .$like. ") like '%" .strtoupper($inputs). "%' ");
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