<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 13/11/2014
 * Time: 05:15 PM
 */
namespace Dmkt;

use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;
use \Excel;
use \Common\Fondo;
use \Expense\Entry;
use \Log;
use \Expense\ProofType;
use \Exception;
use Dmkt\Solicitud\Periodo;
use Common\TypeMoney;

class FondoController extends BaseController
{
    
    public function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data)
            $array[$member] = $data;
        return $array;
    }

    private function period($date)
    {
        $period = explode('-', $date);
        return $period[1].str_pad($period[0], 2, '0', STR_PAD_LEFT);
    }
    
    public function getRegister()
    {
        $fondos  = Fondo::where('idusertype', ASIS_GER )->get();
        return View::make('Dmkt.AsisGer.register_fondo')->with('fondos', $fondos);
    }

    public function getSolInst()
    {
        try
        {
            $inputs = Input::all();
            $rules = array( 'idsolicitud' => 'required|min:1' );
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() )
                return $this->warningException( __FUNCTION__ , substr( $this->msgValidator($validator) , 0 , -1 ) );
            else
            {
                $solInst = Solicitude::find( $inputs['idsolicitud']);
                $detalle = $solInst->detalle;
                $jDetalle = json_decode($detalle->detalle);
                $rm = $solInst->asignedTo->rm;
                $data = array(
                    'titulo'    => $solInst->titulo,
                    'idetiqueta'  => $solInst->idetiqueta,
                    'rm'            => $rm->nombres.' '.$rm->apellidos ,
                    'idrm'          => $rm->idrm ,
                    'supervisor'    => $jDetalle->supervisor ,
                    'codsup'        => $jDetalle->codsup ,
                    'monto'         => $jDetalle->monto_aprobado ,
                    'periodo'       => $detalle->periodo->periodo ,
                    'rep_cuenta'    => $jDetalle->num_cuenta ,
                    'idfondo'       => $detalle->idfondo
                );
                return $this->setRpta( $data );
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function sumSolicitudInst( $solicitud )
    {
        try
        {
            $monedas = TypeMoney::all();
            $totales = array();
            foreach ( $monedas as $moneda )
                $totales[$moneda->simbolo] = 0 ;
            foreach ( $solicitud as $sol )
            {
                $jDetalle = json_decode( $sol->detalle->detalle );
                $typeMoney = $sol->detalle->fondo->typeMoney;
                $totales[$typeMoney->simbolo] += $jDetalle->monto_aprobado ;
            }
            return $this->setRpta( $totales );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        } 
    }

    private function getStringTotal( $totales )
    {
        $rpta = 'Total: ' ;
        foreach ( $totales as $key => $total )
            if ( $total != 0 )
                $rpta .= $key.' '.$total.' , ';
        return substr( $rpta , 0 , -2 );
    }

    public function getFondos($start, $export = 0)
    {
        try
        {
            $periodo = $this->period($start);
            $periodos = Periodo::where('periodo', $periodo )->first();
            if ( count ( $periodos ) == 0 )
                return $this->setRpta( View::make('Dmkt.AsisGer.list_fondos')->with( 'total' , '' )->render() );
            else
            {
                $solicitud = Solicitude::solInst( $periodo );
                $middleRpta = $this->sumSolicitudInst( $solicitud );
                if ( $middleRpta[status] == ok )
                {   
                    $data = array( 
                        'solicituds' => $solicitud ,
                        'state'      => $periodos->status , 
                        'total'      => $this->getStringTotal( $middleRpta[data] )
                    );
                   return $this->setRpta( View::make('Dmkt.AsisGer.list_fondos')->with( $data )->render() );
                }
            }
            return $middleRpta;
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function endSolicituds( $solicituds )
    {
        try
        {
            foreach( $solicituds as $solicitud )
            {
                $oldIdestado = $solicitud->idestado;
                $solicitud->idestado = DEPOSITO_HABILITADO;
                if ( !$solicitud->save() )
                    return $this->warningException( __FUNCTION , 'No se pudo actualizar la solicitud: '.$solicitud->id );
                else
                {
                    $middleRpta = $this->setStatus( $oldIdestado , DEPOSITO_HABILITADO , Auth::user()->id , USER_TESORERIA , $solicitud->id );
                    if ( $middleRpta[status] != ok )
                        return $middleRpta;
                }
            }
            return $this->setRpta();
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }


    public function endFondos( $start )
    {
        try
        {
            DB::beginTransaction();
            $periodo = $this->period($start);
            $periodos = Periodo::where( 'periodo' , $periodo )->first();
            if ( count( $periodos ) == 0 )
                return $this->warningException( __FUNCTION__ , 'El periodo seleccionado no ha sido activado: '.$periodo );
            elseif ( $periodos->status == BLOCKED )
                return $this->warningException( __FUNCTION__ , 'El periodo ya se encuentra Terminado' );
            else
            {
                $solicituds = Solicitude::solInst( $periodo );
                if ( count( $solicituds ) == 0 )
                    return $this->warningException( __FUNCTION__ , 'No se encontro solicitudes para el periodo especificado: ' .$periodo);
                else
                {
                    $middleRpta = $this->endSolicituds( $solicituds );
                    if ( $middleRpta[status] == ok )
                    {
                        $periodos->status = BLOCKED ;
                        if ( !$periodos->save() )
                        {
                            DB::rollback();
                            return $this->warningException( __FUNCTION , 'No se pudo terminar el periodo');
                        }
                        else
                            DB::commit();
                    }
                }
            }
            return $middleRpta;   
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function solicitudToArray( $solicituds )
    {
        $rpta = array();
        foreach ( $solicituds as $solicitud )
        {
            $data     = array();
            $detalle  = $solicitud->detalle;
            $jDetalle = json_decode( $detalle->detalle );
            $data[]   = $solicitud->titulo;
            $data[]   = $solicitud->asignedTo->rm->nombres.' '.$solicitud->asignedTo->rm->apellidos;
            $data[]   = $jDetalle->num_cuenta;
            $data[]   = $jDetalle->supervisor;
            $data[]   = $detalle->fondo->typeMoney->simbolo;
            $data[]   = $jDetalle->monto_aprobado;
            $rpta[]   = $data ;
        }
        return $rpta;
    }

    public function exportExcelFondos( $start )
    {
        $solicituds = Solicitude::solInst( $this->period( $start ) );
        $data = $this->solicitudToArray( $solicituds );
        //$dato = $data->toArray();
        $sum  = $this->sumSolicitudInst( $solicituds );
        $mes  = array(
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'setiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre'
        );
        $st   = explode('-', $start);
        $mes  = $mes[str_pad($st[0], 2, '0', STR_PAD_LEFT)];
        $anio = $st[1];
        
        Excel::create('Fondo - '.str_pad($st[0], 2, '0', STR_PAD_LEFT).$anio , function($excel) use ( $data , $sum , $mes , $anio )
        {  
            $excel->sheet($mes, function($sheet) use ( $data , $sum , $mes , $anio )
            {
                $sheet->mergeCells('A1:F1');
                $sheet->row( 1 , array( 'FONDO INSTITUCIONAL ' . strtoupper($mes) . ' - ' . $anio ));
                $sheet->row( 1 , function( $row )
                {
                    $row->setAlignment('center');
                    $row->setFont( array(
                        'family' => 'Calibri',
                        'size' => '20',
                        'bold' => true
                    ) );
                    $row->setBackground('#339246'); 
                });
                $sheet->setHeight(1, 30);
                $count = count( $data ) + 2;
                $sheet->setBorder( 'A1:F' . $count, 'thin' );
                $sheet->setHeight( 2 , 20 );
                $sheet->row( 2 , array(
                    'SISOL  - Hospital',
                    'Depositar a:',
                    'Nº Cuenta Bagó Bco. Crédito',
                    'SUPERVISOR',
                    'Moneda',
                    'Monto Real'
                ) );
                $sheet->row(2, function($row)
                {
                    $row->setFont( array(
                        'family' => 'Calibri',
                        'size' => '15',
                        'bold' => true
                    ) );
                    
                    $row->setBackground('#D2E718');
                    $row->setAlignment('center');
                });      
                $sheet->fromArray( $data , null , 'A3' , false , false );
                $i= 0 ;
                foreach( $sum[data] as $moneda => $total )
                {
                    if ( $total != 0)
                    {
                        $i++;
                        $sheet->row( $count + $i , array( '' , '' , '' , '' , 'Total: '.$moneda , $total ) );
                    }   
                } 
            });  
        })->download('xls');
        
    }

    private function validateRm( $codrepmed , $codsup )
    {
        try
        {
            $repmed  = Rm::find( $codrepmed );
            if ( count( $repmed ) == 0 )
                return $this->warningException( __FUNCTION__ , 'El representante Medico no esta registrado en el sistema: '.$codrepmed );
            else
            {
                if ( $repmed->idsup != $codsup )
                    return $this->warningException( __FUNCTION__ , 'El codigo del supervisor no coincide con el registro del Sistema: '.$repmed->idsup.'!='.$codsup )
                else
                {
                    $sup    =  Sup::find( $codsup );
                    if( count( $sup ) == 0 )
                        return $this->warningException( __FUNCTION__ , 'El supervisor del Representante Medico no esta registrado en el sistema' );
                    else
                        return $this->setRpta( $repmed );
                }
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }                   
    }

    public function postRegister()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $periodo = $this->period( $inputs['mes'] );
            $verifyMonth = Periodo::where( 'periodo' , $periodo )->where( 'status' , BLOCKED )->first();
            if( count( $verifyMonth ) != 0 )
                return $this->warningException( __FUNCTION__ ,'El periodo ingresado ya ha sido terminado' );
            else
            {
                $verifyPeriodo = Periodo::where( 'periodo' , $periodo)->first();
                if ( count ( $verifyPeriodo ) == 0 )
                {
                    $newPeriodo = new Periodo;
                    $newPeriodo->id = $newPeriodo->searchId() + 1 ;
                    $newPeriodo->periodo = $periodo ;
                    $newPeriodo->status  = ACTIVE ;
                    if ( !$newPeriodo->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo registrar el nuevo periodo' );
                    $idPeriodo = $newPeriodo->id;
                }
                elseif ( $verifyPeriodo->status = 3 )
                {
                    $verifyPeriodo->status = ACTIVE ;
                    if ( !$verifyPeriodo->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo reactivar el periodo');
                    $idPeriodo = $verifyPeriodo->id;
                }
                else
                {
                    $idPeriodo = $verifyPeriodo->id;
                    $middleRpta = $this->validateRm( $inputs['codrep'] , $inputs['codsup'] )
                    if ( $middleRpta[status] == ok )
                    {
                        $solicitud                  = new Solicitude;
                        $solicitud->id              = $solicitud->searchId() + 1;
                        $solicitud->titulo          = $inputs['institucion'];
                        $solicitud->idestado        = PENDIENTE;
                        $solicitud->idetiqueta      = $inputs['idetiqueta'];
                        $solicitud->idtiposolicitud = SOL_INST;
                        $solicitud->iduserasigned   = $middleRpta[data]->iduser;
                        $solicitud->token           = sha1(md5(uniqid($solicitud->id, true)));
                        
                        $detalle                    = new SolicitudeDetalle;
                        $detalle->id                = $detalle->searchId() + 1;

                        $solicitud->iddetalle       = $detalle->id;

                        if ( !$solicitud->save() )
                            return $this->warningException( __FUNCTION__ , 'No se pudo procesar la solicitud' );
                        else
                        {
                            $jDetalle = array(
                            'supervisor'     => $inputs['supervisor'] ,
                            'codsup'         => $inputs['codsup'] ,
                            'num_cuenta'     => $inputs['cuenta'] ,
                            'monto_aprobado' => $inputs['total'] 
                            );
                            $detalle->idfondo   = $inputs['idfondo'] ;
                            $detalle->idperiodo = $idPeriodo;   
                            $detalle->detalle = json_encode($jDetalle);
                            if ( !$detalle->save() )
                                return $this->warningException( __FUNCTION__ , 'No se pudo procesar los detalles de la Solicitud' );
                            else
                            {
                                $userid = Auth::user()->id;
                                $middleRpta = $this->setStatus( 0 , PENDIENTE, $userid , $userid, $solicitud->id );
                                if ($middleRpta[status] == ok)
                                {     
                                    DB::commit();
                                    $middleRpta[data] = $this->getFondos( $inputs['mes'] );
                                }
                                else
                                    DB::rollback();
                                return $middleRpta;
                            }
                        }
                    }
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
        return $middleRpta;
    }

    public function updateSolInst()
    {
        try
        {
            DB::beginTransaction();
            $inputs  = Input::all();
            $periodo = $this->period($inputs['mes']);
            $verifyMonth = Periodo::where( 'periodo' , $periodo )->where( 'status' , BLOCKED )->get();
            if( count( $verifyMonth ) != 0 )
                return $this->warningException( __FUNCTION__ ,'El periodo ingresado ya ha sido terminado' );
            else
            {
                $solInst = Solicitude::find( $inputs['idsolicitud'] );
                if ( count( $solInst ) == 0 )
                    return $this->warningException( __FUNCTION__ , 'No se encontro la solicitud: #'.$inputs['idsolicitud'] );
                else
                    if ( $solInst->idtiposolicitud != SOL_INST )
                        if ( is_null( $solInst->idtiposolicitud ) )
                            return $this->warningException( __FUNCTION__ , 'La solicitud no tiene Id de Tipo: #'.$inputs['idsolicitud']);
                        else
                            return $this->warningException( __FUNCTION__ , 'La solicitud no es Institucional: #'.$inputs['idsolicitud'].'-'.$solInst->typeSolicitude->nombre);
                    elseif ( $solInst->idestado != PENDIENTE )
                        return $this->warningException( __FUNCTION__ , 'Esta solicitud ya ha sido procesada: #'.$inputs['idsolicitud'].'-'.$solInst->state->nombre);
                    else
                    {
                        $middleRpta = $this->validateRm( $inputs['codrepmed'] , $inputs['codsup'] );
                        if ( $middleRpta[status] == ok )
                        {
                            $solInst->titulo    = $inputs['institucion'];
                            $solInst->iduserasigned = $middleRpta[data]->iduser;
                            if ( !$solInst->save() )
                                return $this->warningException( __FUNCTION__ , 'No se pudo actualizar la solicitud: '.$inputs['idsolicitud'] );
                            else
                            {
                                $periodos = Periodo::where( 'periodo' , $periodo)->first();
                                $detalle      = $solInst->detalle;
                                $jDetalle     = json_decode( $detalle->detalle );
                                $jDetalle->supervisor = $inputs['supervisor'];
                                $jDetalle->codsup     = $inputs['codsup'];
                                $jDetalle->num_cuenta = $inputs['cuenta'];
                                $jDetalle->monto_aprobado = $inputs['monto'];
                                $jDetalle->idfondo        = $inputs['idfondo'];
                                $detalle->periodo     = $periodos->id;
                                $detalle->detalle     = json_encode($jDetalle);
                                if ( !$detalle->save() )
                                    return $this->warningException( __FUNCTION__ , 'No se pudo procesar los detalles de la Solicitud' );
                                else
                                {
                                    $userid = Auth::user()->id;
                                    $middleRpta = $this->setStatus( PENDIENTE , PENDIENTE , $userid , $userid, $solicitud->id );
                                    if ($middleRpta[status] == ok)
                                    {     
                                        DB::commit();
                                        $middleRpta[data] = $this->getFondos( $inputs['mes'] );
                                    }
                                    else
                                        DB::rollback();
                                    return $middleRpta;
                                }
                            }
                        }
                    }
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
    
    

    function getFondosTesoreria($start, $export = 0)
    {
        $periodo = $this->period($start);
        
        if ($export) {
            $fondos = FondoInstitucional::where("periodo", $periodo)->get(array(
                'institucion',
                'repmed',
                'cuenta',
                'supervisor',
                'total'
            ));
            return $fondos;
        } else {
            $fondos = FondoInstitucional::where("periodo", $periodo)->where('terminado', TERMINADO)->get();
            $estado = 1;
            foreach ($fondos as $fondo) {
                if($fondo->depositado == PDTE_DEPOSITO)
                    $estado = PDTE_DEPOSITO;
            }
            $view   = View::make('Treasury.list_fondos')->with('fondos', $fondos)->with('sum', $fondos->sum('total'))->with('estado', $estado);
            return $view;
        }
        
    }
    function getFondosContabilidad($start, $state)
    {
        $periodo = $this->period($start);
        
        if($state == '1') {
            $state = FONDO_DEPOSITADO;
            $fondos = FondoInstitucional::where("periodo", $periodo)->where('terminado', TERMINADO)->where('depositado', $state)->where('registrado','<>', FONDO_REGISTRADO)->get();
        }
        else {
            $state = FONDO_REGISTRADO;
            $fondos = FondoInstitucional::where("periodo", $periodo)->where('terminado', TERMINADO)->where('registrado', $state)->get();
        }
        $view   = View::make('Dmkt.Cont.list_fondos')->with('fondos', $fondos)->with('sum', $fondos->sum('total'));
        return $view;
    }
    
/*
    function getFondo($id)
    {
        $fondo = FondoInstitucional::find($id);
        return $fondo;
    }
    
    function delFondo()
    {
        $id    = Input::get('idfondo');
        $start = Input::get('start');
        $fondo = FondoInstitucional::find($id);
        $fondo->delete();
        $fondos = $this->getFondos($start);
        return $fondos;
    }
*/

    

    
    function getLastDayOfMonth($month, $year)
    {
        return date('d', mktime(0, 0, 0, $month + 1, 1, $year) - 1);
    }

  /*  public function viewGenerateSeatFondo($token)
    {
        try
        {
            $fondo = FondoInstitucional::where('token', $token)->firstOrFail();
            $date = $this->getDay();
            $cuenta = Fondo::where('cuenta_mkt', CTA_FONDO_INSTITUCIONAL)->get();
            
            if ($fondo->tipo_moneda == SOLES)
                $banco = Account::where('alias',BANCOS)->where('num_cuenta',CUENTA_SOLES)->get();
            elseif ($fondo->tipo_moneda == DOLARES)
                $banco = Account::where('alias',BANCOS)->where('num_cuenta',CUENTA_DOLARES)->get();
            if ( count($banco) == 1)
            {
                $data = array(
                    'type'   => FONDO,
                    'fondo'  => $fondo,
                    'date'   => $date,
                    'cuenta' => $cuenta,
                    'banco'  => $banco
                );
                return View::make('Dmkt.Cont.register_advance_seat')->with($data);
            //return View::make('Dmkt.Cont.register_seat_fondo')->with($data);
            }
            else
                return array ( status => warning , description => 'Existen varias cuentas de banco en '.$fondo->tipo_moneda);
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
            return $rpta;
        }
    }*/

 /*   public function generateSeatFondo()
    {   
        $middleRpta = array();
        $inputs  = Input::all();
        $middleRpta[status] = 1;
        foreach ($inputs['number_account'] as $account) {
            $fondo = Fondo::where('cuenta_mkt', $account)->get();
            if(count($fondo) == 0){
                $cuentaContable = Account::where('num_cuenta', $account)->get();
                if(count($cuentaContable) == 0){
                    $middleRpta[status] = error;
                    $middleRpta[description] = "La cuenta $account no se encuentra registrada en la Base de datos.";
                }
            }
        }
        try {
            DB::transaction (function() use ($inputs){
                for($i=0;$i<count($inputs['number_account']);$i++)
                {
                    $entry = new Entry;
                    $idasiento = $entry->searchId()+1;
                    $entry->idasiento = $idasiento;
                    $entry->num_cuenta = $inputs['number_account'][$i];
                    $entry->fec_origen = $this->getDay()['toDay'];
                    $entry->d_c = $inputs['dc'][$i];
                    $entry->importe = $inputs['total'][$i];
                    $entry->leyenda = $inputs['leyenda'];
                    $entry->idfondo = $inputs['idfondo'];
                    $entry->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
                    $entry->save();
                }
                FondoInstitucional::where('idfondo', $inputs['idfondo'])->update(array('asiento' => ASIENTO_FONDO));
            });
        } catch (Exception $e) {
            $middleRpta = $this->internalException($e,__FUNCTION__);
        }
        return json_encode($middleRpta);
    }

    

    public function listDocuments()
    {
        $docs = ProofType::order();
        $view = View::make('Dmkt.Cont.list_documents')->with('docs',$docs);
        return $view;
    }*/
}