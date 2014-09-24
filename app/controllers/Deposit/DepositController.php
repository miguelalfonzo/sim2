<?php

namespace Deposit;

use \BaseController;
use \View;
use \Session;
use \Auth;
use \User;
use \Common\State;
use \Common\Deposit;
use \Dmkt\Solicitude;
use \Input;
use \Redirect;
use \DB;
use \Exception;

class DepositController extends BaseController{

    private function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

	public function show_tes()
	{
		$state = Session::get('state');
        $states = State::orderBy('idestado', 'ASC')->get();
        $data = [
            'states' => $states,
            'state' => $state,
        ];
		return View::make('Treasury.show_tes',$data);
	}

	public function listSolicitudeTes($id)
    {
        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', APROBADO)->where('asiento','=',1)->get();
        }
        $view = View::make('Treasury.view_solicituds_tes')->with('solicituds', $solicituds);
        return $view;
    }

    public function viewSolicitudeTes($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        return View::make('Treasury.view_solicitude_tes')->with('solicitude', $solicitude);
    }

    public function searchSolicitudeTes()
    {
        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));
        $user = Auth::user();

        if ($start != null && $end != null) {
            if ($estado != 0) {
                $solicituds = Solicitude::where('estado',$estado)
                	->where('asiento',1)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            } else {
                $solicituds = Solicitude::where('estado', $estado)
                	->where('asiento',1)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        } else {
            if ($estado != 0) {
                $solicituds = Solicitude::where('estado', $estado)
                	->where('asiento',1)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {
                $solicituds = Solicitude::where('estado', $estado)
                	->where('asiento',1)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }

        $view = View::make('Treasury.view_solicituds_tes')->with('solicituds', $solicituds);
        return $view;
    }

    public function depositSolicitudeTes()
    {
        $deposit     = Input::get('data');
        $depositJSON = json_decode($deposit);

        try {
            DB::transaction (function() use ($depositJSON) {
                $solicitude = Solicitude::where('token',$depositJSON->token)->firstOrFail();
                $deposit    = new Deposit;
                $deposit->iddeposito        = $deposit->lastId()+1;
                $deposit->total             = $solicitude->monto;
                $deposit->num_transferencia = $depositJSON->op_number;
                $deposit->idsolicitud       = $solicitude->idsolicitud;
                
                if($deposit->save())
                {
                    $solicitude         = Solicitude::where('token',$depositJSON->token);
                    $solicitude->estado = DEPOSITADO;
                    $data               = $this->objectToArray($solicitude);
                    $solicitude->update($data);
                }
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
}
