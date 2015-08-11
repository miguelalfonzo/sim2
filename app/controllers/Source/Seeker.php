<?php

namespace Source;

use Dmkt\Solicitud;
use Users\Personal;
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
        try {
            $inputs = Input::all();
            $json = ' [{"name":"' . TB_INSTITUCIONES . '",' .
                ' "wheres":{"likes":[ "PEJRAZON" ], ' .
                ' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":2 }}, ' .
                ' "selects":["PEJCODPERS","PEJRAZON" , "\'INSTITUCION\'" , 3 ]} ' .
                ']';
            $cAlias = array('value', 'label', 'type', 'id_tipo_cliente');
            return $this->searchSeeker($inputs['sVal'], $json, $cAlias);
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function clientSource()
    {
        try {
            $inputs = Input::all();
            $json = ' [{"name":"' . TB_DOCTOR . '", ' .
                ' "wheres":{"likes":["PEFNRODOC1","(PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)"], ' .
                ' "equal":{"PEFESTADO":1}},' .
                ' "selects":["PEFCODPERS","( PEFNRODOC1 || \'-\' || PEFNOMBRES || \' \' || PEFPATERNO || \' \' || PEFMATERNO)" , "\'DOCTOR\'" , 1 ]}, ' .
                ' {"name":"' . TB_FARMACIA . '",' .
                ' "wheres":{"likes":["PEJNRODOC","PEJRAZON"], ' .
                ' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":1 }}, ' .
                ' "selects":["PEJCODPERS","( PEJNRODOC || \'-\' || PEJRAZON )" , "\'FARMACIA\'" , 2 ]}, ' .
                ' {"name":"' . TB_INSTITUCIONES . '",' .
                ' "wheres":{"likes":[ "PEJRAZON" ], ' .
                ' "equal":{"PEJESTADO":1 , "PEJTIPPERJ":2 }}, ' .
                ' "selects":["PEJCODPERS","PEJRAZON" , "\'INSTITUCION\'" , 3 ]}, ' .
                ' {"name":"' . TB_DISTRIMED_CLIENTES . '",' .
                ' "wheres":{"likes":[ "CLRUT" , "CLNOMBRE" ], ' .
                ' "equal":{ "CLESTADO":1 }, ' .
                ' "in":{ "CLCLASE": [ 1 , 6 ] } }, ' .
                ' "selects":[ "CLCODIGO" , " ( CLRUT || \'-\' || CLNOMBRE ) " , "CASE WHEN CLCLASE = 1 THEN \'DISTRIBUIDOR\' WHEN CLCLASE = 6 THEN \'BODEGA\' END" , "CASE WHEN CLCLASE = 1 THEN 4 WHEN CLCLASE = 6 THEN 5 END" ]} ' .
                ']';
            \Log::error($json);
            $cAlias = array('value', 'label', 'type', 'id_tipo_cliente');
            return Response::Json($this->searchSeeker($inputs['sVal'], $json, $cAlias));
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function repInfo()
    {
        try {
            $inputs = Input::all();
            $rm = Visitador::find($inputs['rm']);
            $cuenta = $rm->cuenta;
            if (count($cuenta) == 0)
                $cuenta = null;
            else
                $cuenta = $cuenta->cuenta;
            $sup = DB::table(TB_LINSUPVIS . ' a')->where('LSVVISITADOR', $inputs['rm'])->leftJoin(TB_SUPERVISOR . ' b', 'b.SUPSUPERVISOR', '=', 'a.LSVSUPERVISOR')
                ->SELECT(DB::raw("b.supsupervisor as idsup , (b.supnombre || ' ' || b.suppaterno || ' ' || b.supmaterno) as nombre"))->first();
            $data = array('cuenta' => $cuenta, 'sup' => $sup);
            $rpta = $this->setRpta($data);
        } catch (Exception $e) {
            $rpta = $this->internalException($e, __FUNCTION__);
        }
        return Response::Json($rpta);
    }

    public function repSource()
    {
        try {
            $inputs = Input::all();
            $json = '[{"name":"' . TB_SOLICITUD . '","wheres":{"likes":["VISLEGAJO","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)"],"equal":{"VISACTIVO":"S","LENGTH(VISLEGAJO)":8}},"selects":["VISVISITADOR","(VISNOMBRE || \' \' || VISPATERNO || \' \' || VISMATERNO)" , "\'REP\'" ]}]';
            $cAlias = array('value', 'label', 'type');
            return $this->searchSeeker($inputs['sVal'], $json, $cAlias);
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function userSource()
    {
        $inputs = Input::all();
        $personal = Personal::select(DB::raw('(NOMBRES || \' \' || APELLIDOS) as label, USER_ID as value, tipo_personal_id'))
//                    ->whereRaw("NOMBRES || ' ' || APELLIDOS like UPPER('%mel%')")
            ->whereRaw("NOMBRES || ' ' || APELLIDOS like UPPER('%" . $inputs['sVal'] . "%')")
            ->orderBy('label')
            ->get();


//        if (\Auth::user()->type == SUP)
//            return ($var->type == 'SUPERVISOR' || $var->type == 'G. PROMOCION');
//        elseif (\Auth::user()->type == GER_PROD)
//            return ($var->type == 'G. PRODUCTO' || $var->type == 'G. COMERCIAL');
//        elseif (\Auth::user()->type == GER_COM)
//            return $var->type == 'G. PROMOCION';
//        elseif (\Auth::user()->type == GER_PROM)
//            return $var->type == 'G. COMERCIAL';
        $personal_filter = array();
        foreach ($personal as $p) {
            if ($p->getType){
                $p->type = $p->getType->descripcion;
                if (\Auth::user()->type == SUP)
                    if($p->getType->id == 4)
                        $personal_filter[] = $p;
//                      return ($var->type == 'SUPERVISOR' || $var->type == 'G. PROMOCION');
                elseif (\Auth::user()->type == GER_PROD)
                    if($p->getType->id == 5)
                        $personal_filter[] = $p;
//                    return ($var->type == 'G. PRODUCTO' || $var->type == 'G. COMERCIAL');
                elseif (\Auth::user()->type == GER_COM)
                    if($p->getType->id == 6)
                        $personal_filter[] = $p;
//                    return $var->type == 'G. PROMOCION';
                elseif (\Auth::user()->type == GER_PROM)
                    if($p->getType->id == 7)
                        $personal_filter[] = $p;
//                    return $var->type == 'G. COMERCIAL';

            }

        }

        $rpta = array();
        foreach ($personal_filter as $array)
            $rpta[] = $array;
        return $this->setRpta($rpta);
//        $json  = "";
//		$json = '[{ "name": "OUTDVP.DMKT_RG_SUPERVISOR" , "wheres":{ "likes": ["( NOMBRES || \' \' || APELLIDOS )" ] ,
//		 "notnull": ["IDUSER"] } , "selects" : [ "IDUSER" , "( NOMBRES || \' \' || APELLIDOS )" , "\'SUPERVISOR\'" ] } , ' .
//				'{ "name" : "OUTDVP.GERENTES" , "wheres":{ "likes": ["DESCRIPCION"] , "notnull" : [ "IDUSER" ] } ,
//				 "selects" : [ "IDUSER" , "DESCRIPCION" , "\'G. PRODUCTO\'"] } , ' .
//				'{ "name" : "OUTDVP.PERSONAS a" , "joins":{ "innerjoin":[ "outdvp.users b" , "a.iduser" , "=" , "b.id" ] } ,
//				 "wheres":{ "likes": [ "( a.NOMBRES || \' \' || a.APELLIDOS )" ] , "notnull" : [ "a.IDUSER" ] ,
//				  "in":{ "b.TYPE":[ "GP" , "G" ] } } , "selects" : [ "a.IDUSER" , "( a.NOMBRES || \' \' || a.APELLIDOS )" ,
//				  "CASE WHEN b.type = \'GP\' THEN \'G. PROMOCION\' WHEN b.type = \'G\' THEN \'G. COMERCIAL\' END" ] }]';
//		$cAlias = array( 'value' , 'label' , "type" );	$json = '[{ "name": "OUTDVP.DMKT_RG_SUPERVISOR" , "wheres":{ "likes": ["( NOMBRES || \' \' || APELLIDOS )" ] ,
//		 "notnull": ["IDUSER"] } , "selects" : [ "IDUSER" , "( NOMBRES || \' \' || APELLIDOS )" , "\'SUPERVISOR\'" ] } , ' .
//				'{ "name" : "OUTDVP.GERENTES" , "wheres":{ "likes": ["DESCRIPCION"] , "notnull" : [ "IDUSER" ] } ,
//				 "selects" : [ "IDUSER" , "DESCRIPCION" , "\'G. PRODUCTO\'"] } , ' .
//				'{ "name" : "OUTDVP.PERSONAS a" , "joins":{ "innerjoin":[ "outdvp.users b" , "a.iduser" , "=" , "b.id" ] } ,
//				 "wheres":{ "likes": [ "( a.NOMBRES || \' \' || a.APELLIDOS )" ] , "notnull" : [ "a.IDUSER" ] ,
//				  "in":{ "b.TYPE":[ "GP" , "G" ] } } , "selects" : [ "a.IDUSER" , "( a.NOMBRES || \' \' || a.APELLIDOS )" ,
//				  "CASE WHEN b.type = \'GP\' THEN \'G. PROMOCION\' WHEN b.type = \'G\' THEN \'G. COMERCIAL\' END" ] }]';
//		$cAlias = array( 'value' , 'label' , "type" );
//		return $this->searchSeeker( $inputs['sVal'] , $json , $cAlias , 2 );
//		return $this->searchSeeker( 'users', $json , $cAlias , 2 );
    }

    private function searchSeeker($inputs, $json, $cAlias, $type = 1)
    {
        if (!empty($inputs)) {
            $json = json_decode($json);
            if (json_last_error() == JSON_ERROR_NONE) {
                $array = array();
                foreach ($json as $table) {
                    $select = '';
                    $query = DB::table($table->name);
                    if (isset($table->joins)) {
                        foreach ($table->joins as $key => $join) {
                            if ($key == 'innerjoin') {
                                $query->join($join[0], $join[1], $join[2], $join[3]);
                            }
                        }
                    }

                    foreach ($table->wheres as $key => $where) {
                        if ($key == 'likes') {
                            foreach ($where as $key => $like)
                                $query->orWhereRaw(" UPPER(" . $like . ") like '%" . strtoupper($inputs) . "%' ");
                        } else if ($key == 'equal') {
                            foreach ($where as $key => $equal)
                                $query->where($key, $equal);
                        } else if ($key == 'in') {
                            foreach ($where as $key => $in)
                                $query->whereIn($key, $in);
                        } else if ($key === 'notnull') {
                            foreach ($where as $key => $field)
                                $query->whereNotNull($field);
                        }
                    }
                    for ($i = 0; $i < count($cAlias); $i++)
                        $select = $select . ' ' . $table->selects[$i] . ' as "' . $cAlias[$i] . '",';
                    $select = substr($select, 0, -1);
                    $query->select(DB::raw($select));
                    $query->take(50);
                    $tms = $query->get();
                    foreach ($tms as $tm)
                        $tm->table = $table->name;
                    $array = array_merge($tms, $array);
                }
                if ($type == 1)
                    return $this->setRpta($array);
                else {
                    $arrayfilter = array_filter($array, array($this, 'filterUserType'));
                    $rpta = array();
                    foreach ($arrayfilter as $array)
                        $rpta[] = $array;
                    return $this->setRpta($rpta);
                }
            } else
                return $this->warningException('Json: Formato Incorrecto', __FUNCTION__, __LINE__, __FILE__);
        } else
            return $this->warningException('Input Vacio (Post: "Json" Vacio)', __FUNCTION__, __LINE__, __FILE__);
    }

    private function filterUserType($var)
    {
        //return true;
        if (\Auth::user()->type == SUP)
            return ($var->type == 'SUPERVISOR' || $var->type == 'G. PROMOCION');
        elseif (\Auth::user()->type == GER_PROD)
            return ($var->type == 'G. PRODUCTO' || $var->type == 'G. COMERCIAL');
        elseif (\Auth::user()->type == GER_COM)
            return $var->type == 'G. PROMOCION';
        elseif (\Auth::user()->type == GER_PROM)
            return $var->type == 'G. COMERCIAL';
    }

    public function getClientView()
    {
        try {
            $inputs = Input::all();
            $rules = array('id_tipo_cliente' => 'required|min:1|in:' . implode(',', ClientType::lists('id')));
            $validator = Validator::make($inputs['data'], $rules);
            if ($validator->fails())
                return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
            else {
                $tipo_cliente = $inputs['data']['id_tipo_cliente'];

                $act = Activity::where('tipo_cliente', $tipo_cliente)->lists('id');
                $inv = InvestmentActivity::whereIn('id_actividad', $act)->lists('id_inversion');

                return $this->setRpta(array(
                    'View' => View::make('Seeker.client')->with($inputs['data'])->render(),
                    'id_actividad' => $act,
                    'id_inversion' => $inv
                ));
            }
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }
}
