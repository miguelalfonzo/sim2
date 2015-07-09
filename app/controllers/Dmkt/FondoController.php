<?php

namespace Dmkt;

use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;
use \Excel;
use \Expense\Entry;
use \Log;
use \Expense\ProofType;
use \Exception;
use \Users\Rm;
use \Users\Sup;
use \Common\TypeMoney;
use \yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class FondoController extends BaseController
{

    private function period( $date )
    {
        $period = explode('-', $date);
        return $period[1].str_pad($period[0], 2, '0', STR_PAD_LEFT);
    }

    public function getSolInst()
    {
        try
        {
            $inputs = Input::all();
            $rules = array( 'idsolicitud' => 'required|integer|min:1|exists:solicitud,id' );
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() )
                return $this->warningException( substr( $this->msgValidator($validator) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            $solInst = Solicitud::find( $inputs['idsolicitud']);
            $detalle = $solInst->detalle;
            $jDetalle = json_decode($detalle->detalle);
            $rm = $solInst->asignedTo->rm;
            $data = array( 'titulo'      => $solInst->titulo,
                           'idactividad' => $solInst->id_actividad,
                           'rm'          => $rm->nombres.' '.$rm->apellidos ,
                           'idrm'        => $rm->idrm ,
                           'supervisor'  => $detalle->supervisor ,
                           'codsup'      => $jDetalle->codsup ,
                           'monto'       => $jDetalle->monto_solicitado ,
                           'periodo'     => $detalle->periodo->aniomes ,
                           'rep_cuenta'  => $jDetalle->num_cuenta ,
                           'idfondo'     => $detalle->id_fondo );
            return $this->setRpta( $data );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function sumSolicitudInst( $solicitud )
    {
        $monedas = TypeMoney::all();
        $totales = array();
        foreach ( $monedas as $moneda )
            $totales[$moneda->simbolo] = 0 ;
        foreach ( $solicitud as $sol )
        {
            $detalle = $sol->detalle;
            $typeMoney = $detalle->fondo->typeMoney;
            $totales[$typeMoney->simbolo] += $detalle->monto_actual ;
        }
        return $this->setRpta( $totales );
    }

    private function getStringTotal( $totales )
    {
        $rpta = 'Total: ' ;
        foreach ( $totales as $key => $total )
            if ( $total != 0 )
                $rpta .= $key.' '.$total.' , ';
        return substr( $rpta , 0 , -2 );
    }

    private function endSolicituds( $solicituds )
    {
        $montos = array();
        foreach( $solicituds as $solicitud )
        {
            $detalle = $solicitud->detalle;
            $fondo = $detalle->fondo;
            if ( isset( $montos[ $fondo->id ] ) )
                $montos[ $fondo->id ] += $detalle->monto_solicitado;
            else
                $montos[ $fondo->id] = $detalle->monto_solicitado;
            if ( $montos[ $fondo->id ] > $fondo->saldo )
                return $this->warningException( 'No se cuenta con saldo en el fondo ' . $fondo->nombre . ' para terminar los Fondos Institucionales.'  , __FUNCTION__ , __LINE__ , __FILE__ );
            $jDetalle = json_decode( $detalle->detalle );
            $jDetalle->monto_aprobado = $jDetalle->monto_solicitado;
            $detalle->detalle = json_encode( $jDetalle );
            $detalle->save();
            $oldIdestado = $solicitud->id_estado;
            $solicitud->id_estado = DEPOSITO_HABILITADO;
            $solicitud->save();            
            $middleRpta = $this->setStatus( $oldIdestado , DEPOSITO_HABILITADO , Auth::user()->id , USER_TESORERIA , $solicitud->id );
            if ( $middleRpta[status] != ok )
                return $middleRpta;
        }
        return $this->setRpta();
    }

    public function endFondos( $start )
    {
        try
        {
            DB::beginTransaction();
            $periodo = $this->period( $start );
            $periodos = Periodo::where( 'aniomes' , $periodo )->first();
            if ( is_null( $periodos ) )
                return $this->warningException( 'El periodo seleccionado no ha sido activado: '.$periodo , __FUNCTION__ , __LINE__ , __FILE__ );
            elseif ( $periodos->status == BLOCKED )
                return $this->warningException( 'El periodo ya se encuentra Terminado' , __FUNCTION__ , __LINE__ , __FILE__ );
            
            $solicituds = Solicitud::solInst( $periodo );
            if ( $solicituds->count() === 0 )
                return $this->warningException( 'No se encontro solicitudes para el periodo especificado: ' .$periodo , __FUNCTION__ , __LINE__ , __FILE__);
        
            $middleRpta = $this->endSolicituds( $solicituds );
            if ( $middleRpta[status] == ok )
            {
                $periodos->status = BLOCKED ;
                $periodos->save();
                DB::commit();
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
        $view = View::make('Dmkt.Cont.list_documents_type')->with( 'docs' , $docs );
        return $view;
    }

    private function solicitudToArray( $solicituds )
    {
        $rpta = array();
        foreach ( $solicituds as $solicitud )
        {
            $detalle  = $solicitud->detalle;
            $data     = array();
            $data[]   = $solicitud->titulo;
            $data[]   = $solicitud->asignedTo->rm->full_name;
            $data[]   = $detalle->num_cuenta;
            $data[]   = $detalle->supervisor;
            $data[]   = $detalle->fondo->typeMoney->simbolo;
            $data[]   = $detalle->monto_actual;
            $rpta[]   = $data ;
        }
        return $rpta;
    }

    public function exportExcelFondos( $start )
    {
        $solicituds = Solicitud::solInst( $this->period( $start ) );
        $data = $this->solicitudToArray( $solicituds );
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
        $repmed = Rm::find( $codrepmed );
        if ( is_null( $repmed ) )
            return $this->warningException( 'El representante Medico no esta registrado en el sistema. Codigo de Representante: '.$codrepmed , __FUNCTION__ , __LINE__ , __FILE__ );
        
        $sup = Sup::find( $codsup );
        if ( is_null( $sup) )
            return $this->warningException( 'El Supervisor no esta registrado en el sistema. Codigo de Supervisor: '.$codsup , __FUNCTION__ , __LINE__ , __FILE__ );
            
        if ( $repmed->idsup != $codsup )
            return $this->warningException( 'El sistema no tiene la relacion Representate-Supervisor actualizada. Se esperaba al Supervisor : ' .$repmed->rmSup->full_name . ' con codigo:' . $codsup , __FUNCTION__ , __LINE__ , __FILE__ );
        
        return $this->setRpta( array( 'rm' => $repmed->iduser , 'sup' => $sup->iduser ) );
    }

    private function verifyPeriodo( $periodo )
    {
        $periodos = Periodo::periodoInst( $periodo );
        if( count( $periodos ) == 0 )
        {
            $newPeriodo = new Periodo;
            $newPeriodo->id = $newPeriodo->lastId() + 1 ;
            $newPeriodo->aniomes = $periodo ;
            $newPeriodo->status  = ACTIVE ;
            $newPeriodo->idtiposolicitud = SOL_INST ;
            $newPeriodo->save();
            return $this->setRpta( $newPeriodo->id );
        }

        if ( $periodos->status == BLOCKED )
            return $this->warningException( 'El periodo ingresado ya ha sido terminado: '.$periodo , __FUNCTION__ , __LINE__ , __FILE__ );
        elseif( $periodos->status == INACTIVE )
        {
            $periodos->status = ACTIVE ;
            $periodos->save();
            return $this->setRpta( $periodos->id );           
        }
        elseif ( $periodos->status == ACTIVE )
            return $this->setRpta( $periodos->id );
        else
            return $this->warningException( 'Estado: '.$periodos->status.' no registrado' , __FUNCTION__ , __LINE__ , __FILE__ );
    }


    private function validateInputSolInst( $inputs )
    {
        $rules = array( 'idsolicitud' => 'sometimes|integer|min:1|exists:solicitud,id,id_estado,1,idtiposolicitud,'.SOL_INST ,
                        'institucion' => 'required|string|min:3',
                        'codrepmed'   => 'required|integer|min:1|exists:ficpe.visitador,visvisitador',
                        'codsup'      => 'required|integer|min:1|exists:ficpe.supervisor,supsupervisor',
                        'actividad'   => 'required|integer|min:1|exists:tipo_actividad,id',
                        'total'       => 'required|numeric|min:1',
                        'cuenta'      => 'required|alpha_dash|min:1',
                        'idfondo'     => 'required|integer|min:1|exists:fondo,id,idusertype,AG',
                        'mes'         => 'required|string|date_format:m-Y|after:'.date("Y-m") );
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) 
            return $this->warningException( substr($this->msgValidator($validator), 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );

        return $this->setRpta();
    }

    private function setDetalleInst( $detalle , $inputs , $idPeriodo )
    {
        $jDetalle = array( 'supervisor'      => $inputs[ 'supervisor' ] ,
                           'codsup'          => $inputs[ 'codsup' ] ,
                           'num_cuenta'      => $inputs[ 'cuenta' ] ,
                           'monto_solicitado'=> $inputs[ 'total' ]       );
        $detalle->id_fondo   = $inputs[ 'idfondo' ] ;
        $detalle->id_periodo = $idPeriodo;
        $detalle->id_moneda  = SOLES;
        $detalle->detalle = json_encode($jDetalle);
        $detalle->save();
        return $this->setRpta();                            
    }

    public function registerInstitutionalApplication()
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

                    if( $middleRpta[status] == ok )
                    {
                        if ( isset( $inputs[ 'idsolicitud' ] ) )
                        {
                            $solicitud = Solicitud::find( $inputs['idsolicitud'] );
                            $detalle = $solicitud->detalle;
                        }
                        else
                        {
                            $solicitud             = new Solicitud;
                            $solicitud->id         = $solicitud->lastId() + 1;
                            $solicitud->token      = sha1( md5( uniqid( $solicitud->id , true ) ) );
                            $detalle               = new SolicitudDetalle;
                            $detalle->id           = $detalle->lastId() + 1;
                            $solicitud->id_detalle = $detalle->id;
                        }
                        $inputs['supervisor']       = $middleRpta[ data ]['sup'];
                        $solicitud->titulo          = $inputs['institucion'];
                        $solicitud->id_estado       = PENDIENTE;
                        $solicitud->id_actividad    = $inputs['actividad'];
                        $solicitud->idtiposolicitud = SOL_INST;
                        $solicitud->id_user_assign  = $middleRpta[data]['rm'];
                        $solicitud->save();
                        $middleRpta   = $this->setDetalleInst( $detalle , $inputs , $idPeriodo );
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
            DB::rollback();
            return $middleRpta;
        }
        catch( Oci8Exception $e )
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ , DB );
        }
        catch( Exception $e )
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }        
    }

    public function listInstitutionalSolicitud( $start )
    {
        try
        {
            $periodo = $this->period($start);
            $periodos = Periodo::where('aniomes', $periodo )->first();
            if ( is_null( $periodos ) )
                return $this->setRpta( View::make('Tables.solicitud_institucional')->with( 'total' , '' )->render() );
            
            $solicitud = Solicitud::solInst( $periodo );
            $middleRpta = $this->sumSolicitudInst( $solicitud );
            if ( $middleRpta[status] == ok )
            {   
                $data = array( 'solicituds' => $solicitud ,
                               'state'      => $periodos->status , 
                               'total'      => $this->getStringTotal( $middleRpta[data] ) );
               return $this->setRpta( array( 'View' => View::make('Tables.solicitud_institucional' )->with( $data )->render() ) );
            }
            return $middleRpta;
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }
}