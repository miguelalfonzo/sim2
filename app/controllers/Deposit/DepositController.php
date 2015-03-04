<?php

namespace Deposit;

use \BaseController;
use \Dmkt\FondoInstitucional;
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

            $solicituds = Solicitude::whereNotNull('idresponse')->where('estado', '=', APROBADO)->where('asiento','=',ENABLE_DEPOSIT)->get();
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

                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado',$estado)
                	->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            } else {
                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado', $estado)
                	->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        } else {
            if ($estado != 0) {
                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado', $estado)
                	->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {
                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado', $estado)
                	->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }

        $view = View::make('Treasury.view_solicituds_tes')->with('solicituds', $solicituds);
        return $view;
    }
    
    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        $deposit = Input::all();

        try {
            DB::transaction (function() use ($deposit) {
                $solicitude = Solicitude::where('token',$deposit['token'])->firstOrFail();
                $row_deposit = Deposit::where('idsolicitud',$solicitude->idsolicitud)->get();
                if(count($row_deposit)>0)
                {
                    return 0;
                }
                else
                {
                    $retencion = 0;
                    if (!$solicitude->retencion == null)
                    {
                        $retencion = $solicitude->retencion;
                    }
                    $newDeposit = new Deposit;
                    $id = $newDeposit->lastId()+1;
                    $newDeposit->iddeposito        = $id;
                    $newDeposit->total             = ($solicitude->monto - $retencion);
                    $newDeposit->num_transferencia = $deposit['op_number'];
                    $newDeposit->idsolicitud       = $solicitude->idsolicitud;  
                    
                    if($newDeposit->save())
                    {
                        $oldOolicitude      = Solicitude::where('token',$deposit['token'])->first();
                        $oldStatus          = $oldOolicitude->estado;
                        $idSol              = $oldOolicitude->idsolicitud;

                        $solicitudeUpd             = Solicitude::where('token',$deposit['token']);
                        $solicitudeUpd->estado     = DEPOSITADO;
                        $solicitudeUpd->iddeposito = $id;
                        $data                      = $this->objectToArray($solicitudeUpd);
                        $solicitudeUpd->update($data);
                        
                        $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $idSol);
                        //$this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DEPOSITADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);
                    }
                }
            });
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    public function depositFondoTes(){
        $deposit = Input::all();
        try {
            DB::transaction (function() use ($deposit) {
                $idfondo = $deposit['idfondo'];
                $row_deposit = Deposit::where('idfondo',$idfondo)->get();
                if(count($row_deposit)>0)
                {
                    return 0;
                }
                else
                {
                    //Update estado
                    $fondo = FondoInstitucional::find($idfondo);
                    $fondo->depositado = 1;
                    $fondo->save();
                    //New Deposit
                    $newDeposit = new Deposit;
                    $id = $newDeposit->lastId()+1;
                    $newDeposit->iddeposito        = $id;
                    $newDeposit->total             = $fondo->total;
                    $newDeposit->num_transferencia = $deposit['op_number'];
                    $newDeposit->idfondo       = $idfondo;
                    $newDeposit->save();
                }
            });
            return $this->getFondos($deposit['date_fondo']);
        } catch (Exception $e) {
            return 'error';
        }
    }
    public function getFondos($mes){
        $mes = explode('-', $mes);
        $periodo = $mes[1].str_pad($mes[0], 2, '0', STR_PAD_LEFT);
        $fondos = FondoInstitucional::where('terminado', TERMINADO)->where('periodo', $periodo)->get();
        $estado = 1;
        foreach ($fondos as $fondo) {
            if($fondo->depositado == PDTE_DEPOSITO)
            {
                $estado = PDTE_DEPOSITO;
            }
        }
        $view = View::make('Treasury.list_fondos')->with('fondos',$fondos)->with('estado', $estado);
        return $view;

    }
}