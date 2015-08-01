<?php

namespace Deposit;

use \BaseController;
use \View;
use \Session;
use \Auth;
use \User;
use \Common\State;
use \Common\Deposit;
use \Dmkt\Solicitud;
use \Input;
use \Redirect;
use \DB;
use \Exception;
use \Common\StateRange;
use \Dmkt\Account;
use \Expense\ChangeRate;
use \Expense\PlanCta;
use \Validator;
use \System\FondoHistory;
use \Fondo\FondoGerProd;
use \Fondo\FondoSupervisor;

class DepositController extends BaseController{

    private function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

    private function validateBalance( $solicitud , $detalle , $tc )
    {
        if ( $solicitud->idtiposolicitud == SOL_REP )
        {
            $fondoMkt = $solicitud->products[0]->thisSubFondo;
            $msg = 'El Saldo del Fondo: '. $fondoMkt->subCategoria->descripcion . ' | ' . $fondoMkt->marca->descripcion . ' S/.';
        }
        else
        {
            $fondoMkt = $solicitud->detalle->thisSubFondo;
            $msg = 'El Saldo del Fondo: '. $fondoMkt->subCategoria->descripcion . ' S/.';
        }
        $monto = $detalle->monto_actual;

        $msg = $msg . $fondoMkt->saldo . ' es insuficiente para completar la operación';
        if ( $detalle->id_moneda == SOLES )
            if ( $monto > $fondoMkt->saldo )
                return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
            else
            {
                $middleRpta = $this->decreaseBalance( $solicitud );
                if ( $middleRpta[ status ] == ok )
                    return $this->setRpta( $monto );
                else
                    return $middleRpta;
            }
        else
            if ( ( $monto * $tc->compra ) > $fondoMkt->saldo )
                return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
            else
            {
                $middleRpta = $this->decreaseBalance( $solicitud );
                if ( $middleRpta[ status ] == ok )
                    return $this->setRpta( $monto * $tc->compra );
                else
                    return $middleRpta;
            }
    }

    private function decreaseBalance( $solicitud )
    {
        return $this->decreaseFondo( $solicitud );
    }

    private function validateInpustDeposit( $inputs )
    {
        $rules = array( 'token'       => 'required|exists:solicitud,token,id_estado,' . DEPOSITO_HABILITADO ,
                        'num_cuenta'  => 'required|numeric|exists:b3o.plancta,ctactaextern',
                        'op_number'   => 'required|string|min:1' );
        $validator = Validator::make( $inputs , $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ), 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
            return $this->setRpta();
    }

    private function verifyMoneyType( $solIdMoneda , $bankIdMoneda , $monto , $tc , $jDetalle )
    {
        $jDetalle->tcc = $tc->compra;
        $jDetalle->tcv = $tc->venta;    
        if ( $solIdMoneda != $bankIdMoneda )
        {
            if ( $solIdMoneda == SOLES )
                $monto = $monto / $tc->venta;
            elseif ( $solIdMoneda == DOLARES )
                $monto = $monto * $tc->compra;
            else
                return $this->warningException( 'Tipo de Moneda no Registrada con Id: '.$solIdMoneda , __FUNCTION__ , __LINE__ , __FILE__ );
            return $this->setRpta( array( 'monto' => $monto , 'jDetalle' => $jDetalle ) );
        }
        else
            return $this->setRpta( array( 'monto' => $monto , 'jDetalle' => $jDetalle ) );
    }

    private function amountRate( $jDetalle , $tc , $type )
    {
        if ( $type == 1 )
            return ( ( ( $jDetalle->monto_aprobado / $tc->venta ) - $jDetalle->monto_retencion ) * $tc->venta );
        elseif ( $type == 2 )
            return ( ( ( $jDetalle->monto_aprobado * $tc->compra ) - $jDetalle->monto_retencion ) / $tc->compra );
    }

    private function getBankAmount( $detalle , $bank , $tc )
    {
        $jDetalle = json_decode( $detalle->detalle );
        return $this->verifyMoneyType( $detalle->id_moneda , $bank->idtipomoneda , $jDetalle->monto_aprobado , $tc , $jDetalle );
    }

    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        try
        {
            DB::beginTransaction();
            $inputs      = Input::all();
            $middleRpta  = $this->validateInpustDeposit( $inputs );
            if ( $middleRpta[status] == ok )
            {
                $solicitud   = Solicitud::where( 'token' , $inputs['token'] )->first();
                
                $oldIdestado  = $solicitud->id_estado;
                $detalle      = $solicitud->detalle;
                $tc           = ChangeRate::getTc();
                
                if ( ! is_null( $detalle->id_deposito )  )
                    return $this->warningException( 'Cancelado - El deposito ya ha sido registrado' , __FUNCTION__ , __LINE__ , __FILE__ );
                
                if ( $solicitud->idtiposolicitud == SOL_INST )
                {
                    $middleRpta = $this->validateBalance( $solicitud , $detalle , $tc );
                    if ( $middleRpta[status] != ok )
                        return $middleRpta;
                }

                    $bagoAccount = PlanCta::find( $inputs['num_cuenta'] );
                    if ( $bagoAccount->account->idtipocuenta != BANCO )
                        return $this->warningException( 'Cancelado - La cuenta N°: '.$inputs['num_cuenta'].' no ha sido registrada en el Sistema como Cuenta de Banco' , __FUNCTION__ , __LINE__ , __FILE__ );
                            
                    $middleRpta = $this->getBankAmount( $detalle , $bagoAccount->account , $tc );
                    if ( $middleRpta[status] == ok )
                    {    
                        $newDeposit                     = new Deposit;
                        $newDeposit->id                 = $newDeposit->lastId() + 1;
                        $newDeposit->num_transferencia  = $inputs['op_number'];
                        $newDeposit->num_cuenta         = $inputs['num_cuenta'];
                        $newDeposit->total              = round( $middleRpta[data]['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                        $newDeposit->save();
                        $detalle->id_deposito = $newDeposit->id;
                        $detalle->detalle = json_encode( $middleRpta[data]['jDetalle'] );
                        $detalle->save();
                        if ( $detalle->id_motivo == REEMBOLSO )
                            $solicitud->id_estado = GENERADO;
                        else
                            $solicitud->id_estado = DEPOSITADO;
                        
                        $solicitud->save();

                        if ( $detalle->id_motivo == REEMBOLSO )
                            $middleRpta = $this->setStatus( $oldIdestado, GENERADO , Auth::user()->id , USER_CONTABILIDAD , $solicitud->id );
                        else
                            $middleRpta = $this->setStatus( $oldIdestado, DEPOSITADO , Auth::user()->id , USER_CONTABILIDAD , $solicitud->id );

                        if ( $middleRpta[status] == ok )
                        {
                            if ( $solicitud->detalle->id_motivo == REEMBOLSO )
                                Session::put( 'state' , R_FINALIZADO );
                            else
                                Session::put( 'state' , R_REVISADO );
                            DB::commit();
                            return $middleRpta;
                        }
                        
                    
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch( Exception $e ) 
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function fondoDecrease( $solicitud )
    {
        $saldo_inicial = $fondo->saldo;
        if ( $fondo->id_moneda = $detalle->id_moneda )
            $fondo->saldo -= $detalle->monto_actual;
        else
        {
            $tc = ChangeRate::getTc();
            if ( $detalle->id_moneda == SOLES )
                $fondo->saldo -= ( $detalle->monto_actual / $tc->venta );
            elseif ( $detalle->id_moneda == DOLARES )
                $fondo->saldo -= ( $detalle->monto_actual * $tc->compra );
            else
                return $this->warningException( 'No existe el registro de la Moneda #: ' . $detalle->id_moneda , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        if ( $fondo->saldo < 0 )
            return $this->warningException( 'El fondo '. $fondo->nombre . 'solo cuenta con ' . $fondo->saldo . ' el cual es insuficiente para registrar la operacion' , __FUNCTION__ , __LINE__ , __FILE__ );
        $fondo->save(); 
        $fondoHistory = new FondoHistory;
        $fondoHistory->id = $fondoHistory->nextId();
        $fondoHistory->saldo_inicial = $saldo_inicial;
        $fondoHistory->saldo_final = $fondo->saldo;
        $fondoHistory->id_solicitud = $detalle->id;
        $fondoHistory->id_fondo = $fondo->id;
        $fondoHistory->save();
        return $this->setRpta();
    }

    private function decreaseFondoProduct( $solicitud )
    {
        $products = $solicitud->products;
        $detalle  = $solicitud->detalle;
        $tc       = ChangeRate::getTc();
        foreach( $products as $product )
        {
            $subFondo = $product->thisSubFondo;
            if ( $detalle->id_moneda == SOLES )
                $subFondo->saldo -= $product->monto_asignado ;
            elseif ( $detalle->id_moneda == DOLARES )
                $subFondo->saldo -= ( $product->monto_asignado * $tc->compra );
            if ( $subFondo->saldo < 0 )
                return $this->warningException( 'El fondo ' . $subFondo->subCategoria->descripcion . ' solo cuenta con Saldo de S/.' . ( $subFondo->saldo + $product->monto_asignado ) . ' se requiere ' . $solicitud->detalle->typeMoney->simbolo .  $product->monto_asignado . ' para registrar la operacion' , __FUNCTION__ , __LINE__ , __FILE__ );    
            $subFondo->save();
        }
        return $this->setRpta();
    }

    private function decreaseFondo( $solicitud )
    {
        $detalle = $solicitud->detalle;
        $fondo   = $detalle->thisSubFondo;
        $fondo->saldo -= $detalle->monto_aprobado;
        $fondo->save();
        return $this->setRpta();
    }

    public function confirmDevolution()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $solicitud = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
            if( $solicitud->id_estado != DEVOLUCION )
                return $this->warningException( 'Cancelado - La solicitud no se encuentra en la etapa de confirmacion de la devolucion' , __FUNCTION__ , __LINE__ , __FILE__ );
            elseif( $solicitud->detalle->id_motivo == REEMBOLSO )
                return $this->warningException( 'Cancelado - Los Reembolos no estan habilitados para la confirmacion de la devolucion' , __FUNCTION__ , __LINE__ , __FILE__ );
                
            $oldIdestado = $solicitud->id_estado;
            $solicitud->id_estado = REGISTRADO;
            $solicitud->save();
            $middleRpta = $this->setStatus( $oldIdestado, $solicitud->id_estado , Auth::user()->id , USER_CONTABILIDAD , $solicitud->id );
            if ( $middleRpta[ status ] == ok )
                DB::commit();
            else
                DB::rollback();
            return $middleRpta;
        }
        catch( Exception $e )
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

}