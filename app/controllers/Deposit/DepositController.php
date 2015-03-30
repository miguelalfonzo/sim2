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
use \Common\StateRange;
use \Log;

class DepositController extends BaseController{

    private function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

    public function viewSolicitudeTes($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        return View::make('Treasury.view_solicitude_tes')->with('solicitude', $solicitude);
    }

    public function viewFondoTes($token)
    {
        $solicitude = FondoInstitucional::where('token', $token)->firstOrFail();
        return View::make('Treasury.view_solicitude_tes')->with('solicitude', $solicitude);        
    }
    
    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        try
        {
            DB::beginTransaction();
            $deposit = Input::all();
            Log::error($deposit);
            $retencion = 0;
            $estado = null;
            $titulo = '';
            if ($deposit['type_deposit'] == SOLIC )
            {
                $solicitude   = Solicitude::where('token',$deposit['token'])->firstOrFail();
                $row_deposit  = Deposit::where('idsolicitud',$solicitude->idsolicitud)->get();
                $estado = $solicitude->estado;       
                $titulo = $solicitude->titulo.' '.$solicitude->descripcion;
                $idSol = $solicitude->idsolicitud;
                if (!$solicitude->retencion == null)
                    $retencion = $solicitude->retencion;
                
            }
            elseif ($deposit['type_deposit'] == FONDO )
            {
                $fondo = FondoInstitucional::where('token',$deposit['token'])->firstOrFail();
                $row_deposit = Deposit::where('idfondo',$fondo->idfondo)->get();
                $estado = $fondo->estado;
                $titulo = $fondo->institucion;
                $idSol = $fondo->idfondo;
            }
            if(count($row_deposit)>0)
            {
                DB::rollback();
                return array(status => warning , description => 'El deposito ya ha sido registrado');
            }
            else
            {
                $newDeposit = new Deposit;
                $id = $newDeposit->lastId()+1;
                $newDeposit->iddeposito         = $id;
                $newDeposit->num_transferencia  = $deposit['op_number'];
                if ($deposit['type_deposit'] == SOLIC )
                {
                    $newDeposit->idsolicitud    = $solicitude->idsolicitud;  
                    $newDeposit->total              = ($solicitude->monto - $retencion);
                }
                elseif ($deposit['type_deposit'] == FONDO )
                {
                    $newDeposit->idfondo        = $fondo->idfondo;
                    $newDeposit->total          = $fondo->total;
                }
                if($newDeposit->save())
                {
                    if ($deposit['type_deposit'] == SOLIC )
                    {
                        $solicitudeUpd             = Solicitude::where('token',$deposit['token']);
                        $solicitudeUpd->estado     = DEPOSITADO;
                        $solicitudeUpd->iddeposito = $id;
                        $data                      = $this->objectToArray($solicitudeUpd);
                        $solicitudeUpd->update($data);
                    }
                    else if ($deposit['type_deposit'] == FONDO )
                    {
                        $fondo          = FondoInstitucional::where('token',$deposit['token']);
                        $fondo->estado  = DEPOSITADO;
                        $data           = $this->objectToArray($fondo);
                        $fondo->update($data);
                    }
                    $rpta = $this->setStatus($titulo , $estado, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $idSol, $deposit['type_deposit']);
                    if ( $rpta[status] == ok )
                    {
                        DB::commit();
                        return $this->setRpta();
                    }
                    else
                        DB::rollback();
                }
            }
        }
        catch (Exception $e) 
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }
    
    /*public function depositFondoTes()
    {
        try 
        {
            DB::transaction();
            $deposit = Input::all();
            $idfondo = $deposit['idfondo'];
            $row_deposit = Deposit::where('idfondo',$idfondo)->get();
            if(count($row_deposit)>0)
                return 0;
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
            DB::commit();
            return $this->getFondos($deposit['date_fondo']);
        } 
        catch (Exception $e) 
        {
            DB::rollback();
            $this->internalException($e,__FUNCTION__);
        }
    }*/
    
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

    /*public function show_tes()
    {
        $state = Session::get('state');
        $states = StateRange::order();
        $data = array(
            'states' => $states,
            'state' => $state
        );
        return View::make('Treasury.show_tes',$data);
    }*/    
    /*public function listSolicitudeTes($id)
    {
        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {

            $solicituds = Solicitude::whereNotNull('idresponse')->where('estado', '=', APROBADO)->where('asiento','=',ENABLE_DEPOSIT)->get();
        }
        $view = View::make('Treasury.view_solicituds_tes')->with('solicituds', $solicituds);
        return $view;
    }*/
}