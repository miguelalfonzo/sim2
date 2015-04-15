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

class FondoController extends BaseController
{
    
    public function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }
    
    public function getRegister()
    {
        $fondos  = Fondo::where('idusertype', ASIS_GER )->get();
        return View::make('Dmkt.AsisGer.register_fondo')->with('fondos', $fondos);
    }

    public function getFondos($start, $export = 0)
    {
        $periodo = $this->period($start);
        $periodos = Periodo::where('periodo', $periodo )->first();
        if ( count ( $periodos ) == 0 )
            return $this->setRpta();
        else
        {
            $solicitud = Solicitude::orderBy('id','desc')->whereHas('detalle' , function ($q ) use ( $periodo )
            {
                $q->whereHas('periodo' , function ( $t ) use ( $periodo )
                {
                    $t->where('periodo',$periodo);
                });
            })->get();
            $total = 0 ;
            foreach ( $solicitud as $sol )
            {
                $jDetalle = json_decode($sol->detalle->detalle);
                $total += $jDetalle->monto_aprobado;
            }
            $data = array( 
                'solicituds' => $solicitud ,
                'state'      => $periodos->status , 
                'total'      => $total
            );
            return View::make('Dmkt.AsisGer.list_fondos')->with( $data );
        }
            /*$fondos = FondoInstitucional::where('periodo', $periodo)->get();
            $estado = 0;
            $export = 0;
            if(count($fondos) > 0)
            {
                $export = EXPORTAR;
                foreach ($fondos as $fondo) {
                    if($fondo->terminado == TERMINADO)
                    {
                        $estado = TERMINADO;
                    }
                }
            }
            */
    }
    public function postRegister()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $periodo = $this->period($inputs['mes']);
            $verifyMonth = Periodo::where( 'periodo' , $periodo )->where( 'status' , BLOCKED )->get();
            if( count( $verifyMonth ) != 0 )
                return $this->warningException( __FUNCTION__ ,'El periodo ingresado ya ha sido terminado' );
            else
            {
                Log::error('1');
                $verifyPeriodo = Periodo::where( 'periodo' , $periodo)->get();
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
                else
                    $idPeriodo = $verifyPeriodo[0]->id;
                    
                $repmed   = Rm::find( $inputs['codrepmed'] );
                if ( count( $repmed ) == 0 )
                    return $this->warningException( __FUNCTION__ , 'El representante Medico no esta registrado en el sistema' );
                else
                {
                    $sup    =  Sup::find( $inputs['codsup'] );
                    if( count( $sup ) == 0 )
                        return $this->warningException( __FUNCTION__ , 'El supervisor del Representante Medico no esta registrado en el sistema' );
                    else
                    {
                        $solicitud                  = new Solicitude;
                        $solicitud->id              = $solicitud->searchId() + 1;
                        $solicitud->titulo          = $inputs['institucion'];
                        $solicitud->idestado        = PENDIENTE;
                        $solicitud->idtiposolicitud = SOL_INST;
                        $solicitud->iduserasigned   = $repmed->iduser;
                        $solicitud->token           = sha1(md5(uniqid($solicitud->id, true)));
                        
                        //$solicitud->idetiqueta    = $inputs['idetiqueta'];

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
                            $detalle->idfondo = $inputs['idfondo'] ;
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

    public function listFondosRep()
    {
        /*$fondos = Solicitude::where('idtiposolicitud', SOL_INST )->whereHas('detalle', function ( $q) 
        {
            $q->where('')
        })->get();
        return View::make('Dmkt.Rm.list_fondos_rm')->with('fondos', $fondos);*/
    }

    public function updateFondo()
    {
        try
        {
            DB::beginTransaction();
            $inputs  = Input::all();
            $periodo = $this->period($inputs['mes']);
            $verifyMonth = FondoInstitucional::where('periodo', $periodo)->where('terminado', TERMINADO)->get();
            if(count($verifyMonth) != 0)
                return 'blocked';
            else
            {
                $cuenta = Fondo::where('cuenta_mkt',CTA_FONDO_INSTITUCIONAL)->get();
                if (count($cuenta) == 1 )
                {
                    $fondo              = FondoInstitucional::find($inputs['idfondo']);
                    $fondo->institucion = $inputs['institucion'];
                    $fondo->repmed      = $inputs['repmed'];
                    $fondo->idrm        = $inputs['codrepmed'];
                    $fondo->supervisor  = $inputs['supervisor'];
                    $fondo->idsup       = $inputs['codsup'];
                    $fondo->idcuenta    = $cuenta[0]->idfondo;
                    $fondo->monto       = $inputs['total'];
                    $fondo->rep_cuenta  = $inputs['cuenta'];
                    $fondo->periodo     = $periodo;
                    $fondo->iduser      = Auth::user()->id;
                    $fondo->save();
                    $fondos = $this->getFondos($inputs['mes']);
                    $userid = Auth::user()->id;
                    $middleRpta = $this->setStatus($inputs['institucion'], '', PENDIENTE, $userid, $userid, $inputs['idfondo'],FONDO);
                    if ($middleRpta[status] == ok)    
                    {    
                        DB::commit();
                        return $this->setRpta($this->getFondos($inputs['mes']));
                    }
                    else
                        DB::rollback();
                }
                else
                    return array ( status => warning, description => 'La cuenta especificada tiene registrada mas de una marca');
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
    function endFondos($start)
    {
        try
        {
            DB::beginTransaction();
            $periodo = $this->period($start);
            $update = array(
                'terminado'  => TERMINADO,
                'estado'     => DEPOSITO_HABILITADO
            );
            $fondos = FondoInstitucional::where("periodo", $periodo)->select('institucion','idrm','idfondo')->get();//->update(array('terminado' => TERMINADO));
            $aux = 0;
            foreach($fondos as $fondo)
            {
                $userTo = $fondo->iduser($fondo->idrm);
                if (is_null($userTo))
                    return array(status => warning , description => 'El Representante del fondo '.$fondo->idfondo.' '.$fondo->institucion.' no esta registrado en el sistema');
                $rpta = $this->setStatus($fondo->institucion, PENDIENTE, DEPOSITO_HABILITADO, Auth::user()->id, $fondo->iduser($fondo->idrm), $fondo->idfondo,FONDO);
                if ($rpta[status] != ok)
                    $aux=1;
            }
            if ($aux == 0)
            {
                FondoInstitucional::where("periodo", $periodo)->update($update);
                DB::commit();
                $rpta = $this->setRpta($this->getFondos($start));
            }
            else
            {
                $rpta = array( status => warning , description => 'No se pudo procesar los fondos');
                DB::rollback();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

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


    function exportExcelFondos($start)
    {
        $data = $this->getFondos($start, 1);
        $dato = $data->toArray();
        $sum  = $data->sum('total');
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
        
        Excel::create('Fondo - '.str_pad($st[0], 2, '0', STR_PAD_LEFT).$anio , function($excel) use ($dato, $sum, $mes, $anio)
        {
            
            $excel->sheet($mes, function($sheet) use ($dato, $sum, $mes, $anio)
            {
                $sheet->mergeCells('A1:E1');
                $sheet->row(1, array(
                    'FONDO INSTITUCIONAL ' . strtoupper($mes) . ' - ' . $anio
                ));
                $sheet->row(1, function($row)
                {
                    $row->setAlignment('center');
                    $row->setFont(array(
                        'family' => 'Calibri',
                        'size' => '20',
                        'bold' => true
                    ));
                    $row->setBackground('#339246');
                    
                });
                $sheet->setHeight(1, 30);
                $count = count($dato) + 2;
                $sheet->setBorder('A1:E' . $count, 'thin');
                $sheet->setHeight(2, 20);
                $sheet->row(2, array(
                    'SISOL  - Hospital',
                    'Depositar a:',
                    'Nº Cuenta Bagó Bco. Crédito',
                    ' SUPERVISOR',
                    'Monto Real'
                ));
                $sheet->row(2, function($row)
                {
                    $row->setFont(array(
                        'family' => 'Calibri',
                        'size' => '15',
                        'bold' => true
                    ));
                    
                    $row->setBackground('#D2E718');
                    $row->setAlignment('center');
                });
                
                
                $sheet->fromArray($dato, null, 'A3', false, false);
                $sheet->row($count + 1, array(
                    '',
                    '',
                    '',
                    'Total:',
                    $sum
                ));
                $sheet->row($count + 1, function($row)
                {
                    $row->setAlignment('center');
                    $row->setFont(array(
                        'family' => 'Calibri',
                        'size' => '16',
                        'bold' => true
                    ));
                    
                });
                
            });
            
        })->download('xls');
        
    }

    
    function getLastDayOfMonth($month, $year)
    {
        return date('d', mktime(0, 0, 0, $month + 1, 1, $year) - 1);
    }

    public function viewGenerateSeatFondo($token)
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
    }

    public function generateSeatFondo()
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

    private function period($date)
    {
        $period = explode('-', $date);
        return $period[1].str_pad($period[0], 2, '0', STR_PAD_LEFT);
    }

    public function listDocuments()
    {
        $docs = ProofType::order();
        $view = View::make('Dmkt.Cont.list_documents')->with('docs',$docs);
        return $view;
    }
}