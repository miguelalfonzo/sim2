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

    public function listDocuments()
    {
        $docs = ProofType::order();
        $view = View::make('Dmkt.Cont.list_documents')->with('docs',$docs);
        return $view;
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
                return $this->warningException( __FUNCTION__ , 'El representante Medico no esta registrado en el sistema Codigo de Representante: '.$codrepmed );
            else
            {
                if ( $repmed->idsup != $codsup )
                    return $this->warningException( __FUNCTION__ , 'El codigo del supervisor no coincide con el registro del Sistema: '.$repmed->idsup.'!='.$codsup );
                else
                {
                    $sup    =  Sup::find( $codsup );
                    if( count( $sup ) == 0 )
                        return $this->warningException( __FUNCTION__ , 'El supervisor del Representante Medico no esta registrado en el sistema' );
                    else
                        return $this->setRpta( $repmed->iduser );
                }
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }                   
    }

    private function verifyPeriodo( $periodo )
    {
        try
        {
            $periodos = Periodo::periodoInst( $periodo );
            if( count( $periodos ) == 0 )
            {
                $newPeriodo = new Periodo;
                $newPeriodo->id = $newPeriodo->searchId() + 1 ;
                $newPeriodo->periodo = $periodo ;
                $newPeriodo->status  = ACTIVE ;
                $newPeriodo->idtiposolicitud = SOL_INST ;
                if ( !$newPeriodo->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo registrar el nuevo periodo: '.$periodo );
                else
                    return $this->setRpta( $newPeriodo->id );
            }
            else
                if ( $periodos->status == BLOCKED )
                    return $this->warningException( __FUNCTION__ ,'El periodo ingresado ya ha sido terminado: '.$periodo );
                elseif( $periodos->status == INACTIVE )
                {
                    $periodos->status = ACTIVE ;
                    if ( !$periodos->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo reactivar el periodo: '.$periodo);
                    else
                        return $this->setRpta( $periodos->id );           
                }
                elseif ( $periodos->status == ACTIVE )
                    return $this->setRpta( $periodos->id );
                else
                    return $this->warningException( __FUNCTION__ , 'Estado: '.$periodos->status.' no registrado' );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }


    private function validateInputSolInst( $inputs )
    {
        try
        {
            $rules = array(
                'institucion' => 'required|min:3',
                'idetiqueta'  => 'required|numeric|min:1',
                'codrepmed'   => 'required|numeric|min:1',
                'supervisor'  => 'required|min:4',
                'codsup'      => 'required|numeric|min:0',
                'total'       => 'required|numeric|min:1',
                'cuenta'      => 'required|min:1',
                'idfondo'     => 'required|min:1',
                'mes'         => 'required|string|date_format:m-Y'         
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
                return $this->warningException( __FUNCTION__ , substr($this->msgValidator($validator), 0 , -1 ) );
            else
                return $this->setRpta();
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function setDetalleInst( $detalle , $inputs , $idPeriodo )
    {
        try
        {
            $jDetalle = array(
            'supervisor'     => $inputs['supervisor'] ,
            'codsup'         => $inputs['codsup'] ,
            'num_cuenta'     => $inputs['cuenta'] ,
            'monto_aprobado' => $inputs['total'] ,
            'idmoneda'       => 1
            );
            $detalle->idfondo   = $inputs['idfondo'] ;
            $detalle->idperiodo = $idPeriodo;   
            $detalle->detalle = json_encode($jDetalle);
            if ( !$detalle->save() )
                return $this->warningException( __FUNCTION__ , 'No se pudo procesar los detalles de la Solicitud' );
            else
                return $this->setRpta();
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
            $middleRpta = $this->ValidateInputSolInst( $inputs );
            if ( $middleRpta[status] == ok )
            {
                $periodo = $this->period( $inputs['mes'] );
                $middleRpta = $this->verifyPeriodo( $periodo );
                if( $middleRpta[status] == ok )
                {
                    $idPeriodo = $middleRpta[data];
                    $middleRpta = $this->validateRm( $inputs['codrepmed'] , $inputs['codsup'] );
                    if ( $middleRpta[status] == ok )
                    {
                        $solicitud                  = new Solicitude;
                        $solicitud->id              = $solicitud->searchId() + 1;
                        $solicitud->titulo          = $inputs['institucion'];
                        $solicitud->idestado        = PENDIENTE;
                        $solicitud->idetiqueta      = $inputs['idetiqueta'];
                        $solicitud->idtiposolicitud = SOL_INST;
                        $solicitud->iduserasigned   = $middleRpta[data];
                        $solicitud->token           = sha1(md5(uniqid($solicitud->id, true)));
                        
                        $detalle                    = new SolicitudeDetalle;
                        $detalle->id                = $detalle->searchId() + 1;

                        $solicitud->iddetalle       = $detalle->id;

                        if ( !$solicitud->save() )
                            return $this->warningException( __FUNCTION__ , 'No se pudo procesar la solicitud' );
                        else
                        {
                            $middleRpta = $this->setDetalleInst( $detalle , $inputs , $idPeriodo );
                            if ( $middleRpta[status] == ok )
                            {
                                $userid = Auth::user()->id;
                                $middleRpta = $this->setStatus( 0 , PENDIENTE, $userid , $userid, $solicitud->id );
                                if ($middleRpta[status] == ok)
                                {  
                                    DB::commit();
                                    return $middleRpta;
                                }
                            }
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
    }

    public function updateSolInst()
    {
        try
        {
            DB::beginTransaction();
            $inputs  = Input::all();
            $middleRpta = $this->validateInputSolInst( $inputs );
            if ( $middleRpta[status] == ok )
            {   
                $periodo = $this->period($inputs['mes']);
                $middleRpta = $this->verifyPeriodo( $periodo );
                if( $middleRpta[status] == ok )
                {
                    $idPeriodo = $middleRpta[data];
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
                                $solInst->titulo     = $inputs['institucion'];
                                $solInst->idetiqueta = $inputs['idetiqueta'];
                                $solInst->iduserasigned = $middleRpta[data];
                                if ( !$solInst->save() )
                                    return $this->warningException( __FUNCTION__ , 'No se pudo actualizar la solicitud: '.$inputs['idsolicitud'] );
                                else
                                {
                                    $detalle      = $solInst->detalle;
                                    $middleRpta   = $this->setDetalleInst( $detalle , $inputs , $idPeriodo );
                                    if ( $middleRpta[status] == ok )
                                    {
                                        $userid = Auth::user()->id;
                                        $middleRpta = $this->setStatus( PENDIENTE , PENDIENTE , $userid , $userid, $solInst->id );
                                        if ($middleRpta[status] == ok)
                                        {
                                            DB::commit();
                                            return $middleRpta;
                                        }
                                    }
                                }
                            }
                        }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
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

    function getLastDayOfMonth($month, $year)
    {
        return date('d', mktime(0, 0, 0, $month + 1, 1, $year) - 1);
    }

   /* public function getFondosContabilidad($start, $state)
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
    }*/
    
    

}