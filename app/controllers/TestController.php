<?php

use Dmkt\Solicitud;
use Users\Rm;
use Dmkt\Account;
use \Exception;

class TestController extends BaseController 
{
	public function testQuery()
	{
		try
		{
			$a = "fgsssssssssssssssssssssssssssssssssssssssssss";
			$query = 'SELECT a.titulo , a.CREATED_AT , b.type as "USUARIO" , d.DESCRIPCION as "PRODUCTO" 
					, f.ID_CLIENTE , g.DESCRIPCION as "TIPO_CLIENTE" , h.NOMBRE as "RUBRO"
					FROM SOLICITUD a 
					LEFT JOIN OUTDVP.USERS b on a.CREATED_BY = b.id
					LEFT JOIN SOLICITUD_PRODUCTO c on c.ID_SOLICITUD = a.id
					LEFT JOIN OUTDVP.MARCAS d on d.id = c.ID_PRODUCTO
					LEFT JOIN SOLICITUD_CLIENTE f on f.ID_SOLICITUD = a.id
					LEFT JOIN TIPO_CLIENTE g on g.ID = f.ID_TIPO_CLIENTE
					LEFT JOIN TIPO_ACTIVIDAD h on h.id = a.ID_ACTIVIDAD
					where idtiposolicitud = 1';

			$query = DB::select( DB::raw( $query ) );
			$frecuency = 'N';

			$fromDate = '2015/05/18';
			$toDate = '2015/05/30';


			$q = "SELECT " . ($frecuency == 'M' ? "('SEMANA ' || to_char(z.the_date,'IW'))" : "to_char(". ($frecuency == 'S' ? "z.the_date" : "a.created_at" ) .", 'YYYY/MM/DD')" ) ." as FECHA, a.titulo , b.type as USUARIO , d.DESCRIPCION as PRODUCTO 
							, f.ID_CLIENTE , g.DESCRIPCION as TIPO_CLIENTE , h.NOMBRE as RUBRO ,
							CASE 
							WHEN g.DESCRIPCION = 'MEDICO' THEN i.pefnombres || ' ' || i.pefpaterno || ' ' || i.pefmaterno
							WHEN g.DESCRIPCION = 'FARMACIA' THEN j.pejrazon
							WHEN g.DESCRIPCION = 'INSTITUCION' THEN k.pejrazon 
							WHEN g.DESCRIPCION = 'DISTRIBUIDOR' OR g.DESCRIPCION = 'BODEGA' THEN l.clnombre
							ELSE 'No Identificado'END as CLIENTE ,
							CASE 
							WHEN b.type = 'R' THEN m.nombres
							WHEN b.type = 'S' THEN n.nombres
							WHEN b.type = 'P' THEN o.descripcion
							WHEN b.type != '' THEN p.nombres
							ELSE 'No Identificado'END as USUARIO
							FROM ". ($frecuency == 'S' || $frecuency == 'M' ? " ( 
							SELECT to_date('$fromDate','YYYY/MM/DD')+level-1 as the_date 
							FROM dual connect by level <= to_date('$toDate','YYYY/MM/DD') - to_date('$fromDate','YYYY/MM/DD') + 1) z LEFT JOIN SOLICITUD a ON TO_DATE ( TO_CHAR( a.created_at , 'YYYY/MM/DD' ) , 'YYYY/MM/DD' )  = z.the_date " : "SOLICITUD a") ." 
							LEFT JOIN OUTDVP.USERS b on a.CREATED_BY = b.id
							LEFT JOIN OUTDVP.DMKT_RG_RM m on b.id = m.IDUSER
							LEFT JOIN OUTDVP.DMKT_RG_SUPERVISOR n ON b.id = n.IDUSER
							LEFT JOIn OUTDVP.GERENTES o ON b.id= o.IDUSER
							LEFT JOIN OUTDVP.PERSONAS p ON b.id = p.IDUSER
							LEFT JOIN SOLICITUD_PRODUCTO c on c.ID_SOLICITUD = a.id
							LEFT JOIN OUTDVP.MARCAS d on d.id = c.ID_PRODUCTO
							LEFT JOIN SOLICITUD_CLIENTE f on f.ID_SOLICITUD = a.id
							LEFT JOIN TIPO_CLIENTE g on g.ID = f.ID_TIPO_CLIENTE
							LEFT JOIN TIPO_ACTIVIDAD h on h.id = a.ID_ACTIVIDAD
							LEFT JOIN FICPE.PERSONAFIS i on i.pefcodpers = f.ID_CLIENTE  
							LEFT JOIN FICPEF.PERSONAJUR j on j.PEJCODPERS = f.ID_CLIENTE
							LEFT JOIN FICPE.PERSONAJUR k on k.PEJCODPERS = f.ID_CLIENTE
							LEFT JOIN VTADIS.CLIENTES l on l.CLCODIGO = f.ID_CLIENTE ". 
							($frecuency == 'S' ? "" : "WHERE a.created_at between to_date('".$fromDate."','yyyy/mm/dd') and to_date('".$toDate."','yyyy/mm/dd') ") . " " . ($frecuency == 'S' || $frecuency == 'M' ? 
							"ORDER BY z.the_date" : "")."";
			eval('$qu = ' . $q . ';');
			return DB::select( DB::raw( $q ) );
			$sol = Solicitud::getAllData();
			return $sol;
			return $query;
		}
		catch( Exception $e )
		{
			return $e;
		}
	}


	public function tm()
	{
		$id = 3;
		$models = Solicitud::with('state.rangeState')->whereHas('state', function ($q) use($id)
		{
			$q->whereHas('rangeState', function ($t) use($id)
			{
				$t->where('id',$id);
			});
		})->get();
		
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
		return $account;


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
	    	if (!empty($inputs))
	    	{
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