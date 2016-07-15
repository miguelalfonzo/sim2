<?php

namespace Seat;

use \Dmkt\Solicitud;
use \Expense\ProofType;
use \BaseController;
use \Expense\Entry;
use \Dmkt\Account;
use \Expense\MarkProofAccounts;
use \Carbon\Carbon;
use \Session;
use \View;
use \Input;
use \Validator;
use \DB;
use \Auth;
use \stdClass;

class Generate extends BaseController
{
    public function viewGenerateEntryExpense( $token )
    {
        try
        {
            $solicitud = Solicitud::findByToken( $token );
            
            $clientes = array();
            foreach ( $solicitud->clients as $client ) 
            {
                if ($client->from_table == TB_DOCTOR) 
                {
                    $doctors = $client->doctors;
                    $nom = $doctors->pefnombres . ' ' . $doctors->pefpaterno . ' ' . $doctors->pefmaterno;
                } 
                else if ($client->from_table == TB_FARMACIA)
                {
                    $nom = $client->institutes->pejrazon;
                }
                else
                {
                    $nom = 'No encontrado';
                }
                $clientes[] = $nom;
            }
            $clientes = implode( ',' , $clientes );
            
            $typeProof = ProofType::all();
            
            $resultSeats = $this->generateRegularizationSeatData( $solicitud ); 
            $resultSeats = json_decode(json_encode( $resultSeats ) );
            
            $data = array(
                'solicitud'   => $solicitud,
                'expenseItem' => $solicitud->expenses,
                'typeProof'   => $typeProof,
                'seats'       => $resultSeats
            );

            if( isset( $resultSeats[ 'error' ] ) )
            {
                $tempArray = array( 'error' => $resultSeats[ 'error' ] );
                $data = array_merge( $data , $tempArray );
            }
            Session::put( 'state' , R_GASTO );
            return View::make( 'Dmkt.Cont.expense_seat' , $data );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function generateRegularizationSeatData( $solicitud )
    {
        $expenses = $solicitud->expenses;
        foreach( $expenses as $expense ) 
        {
            $expense->itemList = $expense->items;
            $expense->count    = count($expense->itemList);
        }
        $solicitud->documentList = $expenses;
        return $this->generateSeatExpenseData( $solicitud );
    }

    private function generateSeatExpenseData( $solicitud )
    {
        $now = Carbon::now();
        $result = array();
        $seatList = array();
        $detalle = $solicitud->detalle;
        $middleRpta = $this->searchFundAccount($solicitud);
        if( $middleRpta[ status ] == ok ) 
        {
            $fondo = $middleRpta[data];
            $cuentaExpense = '';
            $marcaNumber = '';
            $cuentaMkt = '';
            if ( ! is_null( $fondo ) ) 
            {
                $cuentaMkt = $fondo->num_cuenta;

                $cuentaExpense = Account::getExpenseAccount( $cuentaMkt );

                if ( ! is_null( $cuentaExpense[0]->num_cuenta ) ) 
                {
                    $cuentaExpense = $cuentaExpense[0]->num_cuenta;
                    $marcaNumber = MarkProofAccounts::getMarks( $cuentaMkt , $cuentaExpense );
                    $marcaNumber = $marcaNumber[0]->marca_codigo;
                } 
                else
                    $result['error'][] = $accountResult['error'];
            }

            if( $solicitud->documentList->count() == 0 ) 
            {
                return $seatList();
            }
            else 
            {
                $tempId = 1;
                $total_percepciones = 0;

                $userElement = $solicitud->assignedTo;
                $tipo_responsable = $userElement->tipo_responsable;
                $userType = $userElement->type;
                $username = $userElement->personal->seat_name;

                $firstSolicitudClient = $solicitud->client;
                $clientName           = $firstSolicitudClient->{ $firstSolicitudClient->clientType->relacion }->full_name;                          

                $nro_origen_sufix = 'S';

                $nro_origen_middle = str_pad( substr( $solicitud->id , -5 ) , 5 , 0 , STR_PAD_LEFT );

                $nro_origen_pre = $nro_origen_sufix . $nro_origen_middle;
                $i = 1;

                foreach( $solicitud->documentList as $expense ) 
                {
                    if( $expense->igv != 0 )
                    {
                        $nro_origen = $nro_origen_pre . str_pad( substr( $i , -2 ) , 2 , 0 , STR_PAD_LEFT );
                        ++$i;
                    }
                    else
                    {
                        $nro_origen = '';
                    }

                    if( isset( $oldExpense ) )
                    {
                        if( ! $oldExpense->updated_at->gt( $expense->updated_at ) )
                        {
                            $oldExpense = $expense;
                        }
                    }
                    else
                    {
                        $oldExpense = $expense;
                    }

                    $comprobante = $expense->proof;
                    
                    $desc = substr($comprobante->descripcion, 0, 1 ) . '/' . $expense->num_prefijo . '-' . $expense->num_serie . ' ' . $expense->razon;
                    $description_seat_repair_deposit = strtoupper('REPARO IGV MKT ' . $desc);
                    $description_seat_retencion_base = strtoupper('ENTREGAS A RENDIR CTA A TERCER ' . $desc);
                    $description_seat_retencion_deposit = strtoupper('RETENCION ' . $desc);
                    $description_seat_detraccion_deposit = strtoupper('DETRACCION ' . $desc);

                    $tasaCompra = $this->getExpenseChangeRate( $solicitud , $expense->updated_at );
                    
                    $description_detraccion_reembolso = 'VARIOS ' . $desc;
                    $comprobante->marcaArray = explode(',', $comprobante->marca);
                    $marca = '';
                 
                    if ($marcaNumber == '') 
                    {
                        $errorTemp = array( 'error' => ERROR_NOT_FOUND_MARCA,
                                            'msg' => MESSAGE_NOT_FOUND_MARCA );
                        if ( ! isset( $result['error'] ) || ! in_array( $errorTemp , $result['error'] ) )
                            $result['error'][] = $errorTemp;
                    } 
                    else
                        if (count($comprobante->marcaArray) == 2 && (boolean)$comprobante->igv == true)
                            if ( $expense->igv > 0 )
                                $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[1];
                            else
                                $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[0];
                        else
                            $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[0];

                    $fecha_origen = date( 'd/m/Y' , strtotime( $expense->fecha_movimiento ) );
                    // COMPROBANTES CON IGV
                    if ( ( boolean ) $comprobante->igv === true ) 
                    {
                        $itemLength = count( $expense->itemList ) - 1;
                        $total_neto = 0;
                        foreach ( $expense->itemList as $itemKey => $itemElement )
                        {
                            $sufix_description_seat_item = $username . ' ' . $itemElement->descripcion . ' '; 
                            $avalaibleLength = 50 - strlen( $sufix_description_seat_item );
                            $clientName = $this->trim_text( $clientName , $avalaibleLength );

                            $description_seat_item = strtoupper( $sufix_description_seat_item . $clientName );
                            $description_seat_igv = strtoupper($expense->razon);
                            $description_seat_repair_base = strtoupper($username . ' ' . $expense->descripcion . '-REP ' . $desc);
                            

                            // ASIENTO ITEM
                            $seatList[] = $this->createSeatElement($cuentaMkt,  $cuentaExpense, $comprobante->cta_sunat, $fecha_origen,
                                ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV,
                                $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, 
                                round( $itemElement->importe * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_item, $tipo_responsable, $nro_origen ,'');

                            $total_neto += $itemElement->importe;
                        }

                        //ASIENTO DE IGV
                        if ( $expense->igv != 0 )
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt,  CUENTA_REPARO_GOBIERNO, $comprobante->cta_sunat, $fecha_origen, 
                                ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, 
                                $expense->num_serie, ASIENTO_GASTO_BASE, round( $expense->igv * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , 
                                $description_seat_igv, $tipo_responsable, $nro_origen , 'IGV' );
                        }

                        //ASIENTO IMPUESTO SERVICIO
                        if ( ! ( $expense->imp_serv == null || $expense->imp_serv == 0 || $expense->imp_serv == '') )
                        {
                            $porcentaje = $total_neto / $expense->imp_serv;


                            $description_seat_tax_service = strtoupper('SERVICIO ' . $porcentaje . '% ' . $expense->descripcion);
                            $seatList[] = $this->createSeatElement($cuentaMkt,  $cuentaExpense, '', $fecha_origen , 
                                '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, round( $expense->imp_serv * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , 
                                $marca, $description_seat_tax_service, '', '' , 'SER' );
                        }

                        //ASIENTO REPARO
                        if ( $expense->reparo == 1 ) 
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt,  CUENTA_REPARO_COMPRAS, '', $fecha_origen , '' , '' , '' , '' , '' , '' , '' , 
                                ASIENTO_GASTO_BASE, round( $expense->igv  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_repair_base, '', '' , 'REP');
                            $seatList[] = $this->createSeatElement($cuentaMkt,  CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', 
                                ASIENTO_GASTO_DEPOSITO, round( $expense->igv  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_repair_deposit, '', '' , 'REP');
                        }

                        //ASIENTO RETENCION
                        if ($expense->idtipotributo == REGIMEN_RETENCION )
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt,  CUENTA_RETENCION_DEBE, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, 
                                $expense->monto_tributo  * $tasaCompra , '' , $description_seat_retencion_base, '', '', 'RET');
                            $seatList[] = $this->createSeatElement($cuentaMkt,  CUENTA_RETENCION_HABER, '', $fecha_origen, '', '', '', '', '', '', '', 
                                ASIENTO_GASTO_DEPOSITO, round( $expense->monto_tributo  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , 
                                $description_seat_retencion_deposit, '', '' , 'RET');
                        }

                        //ASIENTO DETRACCION
                        if ($expense->idtipotributo == REGIMEN_DETRACCION )
                        {
                            $total_percepciones += $expense->monto_tributo;
                            $seatList[] = $this->createSeatElement( $cuentaMkt,  CUENTA_DETRACCION_HABER, '', $fecha_origen, '', '', '', '', '', '', '', 
                                ASIENTO_GASTO_DEPOSITO, round( $expense->monto_tributo * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , 
                                $description_seat_detraccion_deposit, '', '', 'DET');
                        }
                    }
                    else //TODOS LOS OTROS DOCUMENTOS
                    {
                        $description_seat_renta4ta_deposit = strtoupper('RENTA 4TA CATEGORIA ' . $desc);

                        //ASIENTO DOCUMENTO OTROS - UN SOLO ASIENTO POR TODOS LOS ITEMS QUE TENGA
                        $description_seat_other_doc = strtoupper( $username .' '. $expense->razon );
                        if ( $expense->idcomprobante == DOC_NO_SUSTENTABLE )
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, '' , 
                                ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, 
                                round( $expense->monto  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_other_doc, $tipo_responsable, '' , ''); 
                        }
                        else if ( $expense->idcomprobante == DOC_RECIBO_HONORARIO  )
                        {
                            $descripcion_rh = $description_seat_other_doc . ' ' . 'RH/'.$expense->num_prefijo . '-' . $expense->num_serie;
                            if ( $solicitud->id_inversion == 17 ) //Inversion Micromarketing y tipo de documento recibo x honorario
                            {
                                $cuentaExpenseDinamic = 6329200;
                            }
                            else
                            {
                                $cuentaExpenseDinamic = $cuentaExpense;
                            }

                            $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , $cuentaExpenseDinamic , '' , $fecha_origen , '' , ASIENTO_GASTO_COD_PROV , '' , ASIENTO_GASTO_COD , '' , '' , '' , ASIENTO_GASTO_BASE, 
                                round( $expense->monto  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $descripcion_rh , $tipo_responsable, '' , ''); 
                            
                        }
                        else
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, 
                                ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, 
                                round( $expense->monto  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_other_doc, $tipo_responsable, '' , '' ); 
                        }

                        //ASIENTO IMPUESTO A LA RENTA
                        if ( $expense->idtipotributo == REGIMEN_RETENCION && $expense->idcomprobante == DOC_RECIBO_HONORARIO ) 
                        {
                            $total_percepciones += $expense->monto_tributo;
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RENTA_4TA_HABER, '', $fecha_origen, '', '', '', '', '', '', '', 
                            ASIENTO_GASTO_DEPOSITO, round( $expense->monto_tributo  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_renta4ta_deposit, '', '' , 'RENTA' );
                        }
                    }
                }

                foreach( $solicitud->devolutions()->where( 'id_tipo_devolucion' , DEVOLUCION_INMEDIATA )->get() as $devolution )
                {
                    $tasaCompra = $this->getExpenseChangeRate( $solicitud , $devolution->updated_at );
                    $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_SOLES , '' , date( 'd/m/Y' , strtotime( $devolution->updated_at ) ) , '' , '' , '' ,
                        '' , '' , '' , '' , ASIENTO_GASTO_BASE , $devolution->monto  * $tasaCompra , '' , 
                        'DEVOLUCION ' . $devolution->type->descripcion . ' - ' . $devolution->numero_operacion . ' - ' . strtoupper( $solicitud->assignedTo->personal->full_name ) , 
                        ' ' , '' , 'DEVOLUCION' );
                }

                // CONTRAPARTE ASIENTO DE ANTICIPO
                $tasaCompra = $this->getExpenseChangeRate( $solicitud , $now );

                $description_seat_back = strtoupper($username . ' ' . $solicitud->titulo);
                if( $solicitud->idtiposolicitud == REEMBOLSO )
                {
                    $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_HABER_REEMBOLSO , '' , $now->format( 'd/m/Y' ) , '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO,
                    round( ( $solicitud->detalle->monto_aprobado - $total_percepciones )  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '', $description_seat_back, '', '' , 'CAN');
                }
                else
                {
                    if ( $solicitud->id_inversion == 36 && $detalle->id_moneda == SOLES )
                    {
                        $cuentaMkt = 1893000;
                    }
                    elseif( $solicitud->id_inversion == 36 && $detalle->id_moneda == DOLARES )
                    {
                        $cuentaMkt = 1894000;
                    }
                    $seatList[] = $this->createSeatElement( $cuentaMkt , $cuentaMkt, '', $oldExpense->updated_at->format( 'd/m/Y' ) , '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO,
                    round( ( $solicitud->detalle->monto_aprobado - $total_percepciones )  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '', $solicitud->solicitud_caption, '', '' , 'CAN');
                }
                return $seatList;
            }
        }
        return $middleRpta;
    }

    private function searchFundAccount($solicitud)
    {
        $fondo = $solicitud->investment->accountFund;
        if ( is_null( $fondo ) )
        {
            return $this->warningException('No se encontro el Fondo asignado a la solicitud', __FUNCTION__, __LINE__, __FILE__);
        }
        else
        {
            return $this->setRpta( $fondo );
        }
    }

    private function createSeatElement( $cuentaMkt , $account_number , $cod_snt , $fecha_origen , $iva , $cod_prov , $nom_prov, $cod, $ruc, $prefijo, $numero, $dc, $monto, $marca, $descripcion, $tipo_responsable, $origin , $type)
    {
        return 
            array(
                'cuentaMkt'        => $cuentaMkt,
                'numero_cuenta'    => $account_number,
                'codigo_sunat'     => $cod_snt,
                'nro_origen'       => $origin,
                'fec_origen'       => $fecha_origen,
                'iva'              => $iva,
                'cod_prov'         => $cod_prov,
                'nombre_proveedor' => $nom_prov,
                'cod'              => $cod,
                'ruc'              => $ruc,
                'prefijo'          => $prefijo,
                'cbte_proveedor'   => $numero,
                'dc'               => $dc,
                'importe'          => $monto,
                'leyenda'          => $marca,
                'leyenda_variable' => $descripcion,
                'tipo_responsable' => $tipo_responsable,
                'type'             => $type
            );
    }


    // IDKC: CHANGE STATUS => GENERADO
    public function saveEntryExpense()
    {
        try 
        {
            $inputs = Input::all();

            $solicitud = Solicitud::findByToken( $inputs[ 'solicitud_token' ] );
            
            if( $solicitud->id_estado != REGISTRADO )
            {
                return $this->warningException( 'La solicitud no se encuentra en la etapa del asiento de la regularizacion' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            
            $entries = $this->generateRegularizationSeatData( $solicitud );

            if( empty( $entries ) )
            {
                return $this->warningException( 'No se pudo generar la informacion de los asientos' , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            unset( $solicitud->documentList );

            return $this->regularizationEntryTransaction( $solicitud , $entries );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function regularizationEntryTransaction( $solicitud , $entries )
    {
        DB::beginTransaction();
        $entryAgrupation = [];
        foreach( $entries as $entry )
        {
            $tbEntry = new Entry;
            $tbEntry->insertRegularizationEntry( $entry , $solicitud->id );
            $entryAgrupation[ $solicitud->id ][ TIPO_ASIENTO_GASTO ][] = $tbEntry;
        }

        $oldIdEstado = $solicitud->id_estado;
        if ($solicitud->idtiposolicitud == REEMBOLSO )
        {
            $solicitud->id_estado = DEPOSITO_HABILITADO;
        }
        else
        {
            $solicitud->id_estado = GENERADO;
        }

        $user = Auth::user();
        $solicitud->save();

        if ($solicitud->idtiposolicitud == REEMBOLSO )
        {
            $middleRpta = $this->setStatus($oldIdEstado, DEPOSITO_HABILITADO, $user->id, USER_TESORERIA, $solicitud->id);
        }
        else
        {
            $middleRpta = $this->setStatus($oldIdEstado, GENERADO, $user->id, $user->id, $solicitud->id);
        }

        if( $middleRpta[ status ] == ok ) 
        {
            if( $solicitud->idtiposolicitud == REEMBOLSO )
            {
                Session::put( 'state' , R_REVISADO );
            }
            else
            {
                Session::put( 'state' , R_FINALIZADO );
            }
            $this->generateBagoSeat( $entryAgrupation );
            DB::commit();
            return $middleRpta;
        }
        DB::rollback();
        return $middleRpta;
        
    }

    public function viewSeatExpense($token)
    {
        $solicitud = Solicitud::where('token', $token)->firstOrFail();
        $expense = Expense::where('idsolicitud', $solicitud->id_solicitud)->get();
        $data = array(
            'solicitude' => $solicitud,
            'expense' => $expense
        );
        return View::make('Dmkt.Cont.register_seat_expense', $data);
    }

    // IDKC: CHANGE STATUS => GASTO HABILITADO

    private function validateInputAdvanceEntry($inputs)
    {
        $rules = array( 'solicitud_token' => 'required|min:1|exists:solicitud,token,id_estado,' . DEPOSITADO );
        $messages = array( 'solicitud_token.exists' => 'La solicitud no se encuentra en la etapa de generacion del asiento de la transferencia' );
        $validator = Validator::make( $inputs , $rules , $messages );
        
        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        else
        {
            return $this->setRpta();
        }
    }

    private function generateBagoSeat( array $entries )
    {
            $migrateSeatController = new MigrateSeatController;
            $migrateSeatController->transactionGenerateSeat( $entries );
    }

    private function getExpenseChangeRate( $solicitud , $date )
    {        
        if( $solicitud->detalle->id_moneda == SOLES )
        {
            $tasaCompra = 1;
        }
        elseif( $solicitud->detalle->id_moneda == DOLARES )
        {
            $tc = ChangeRate::getDayTc( $date );
            if ( is_null( $tc ) )
            {
                $tasaCompra = ChangeRate::getTc()->venta;
            }
            else
            {
                $tasaCompra = $tc->venta;
            }
        }
        return $tasaCompra;
    }	

    public function generateAdvanceEntry()
    {
        try 
        {
            $middleRpta = array();
            $inputs = Input::all();
            $middleRpta = $this->validateInputAdvanceEntry( $inputs );
            if( $middleRpta[ status ] == ok ) 
            {
                $middleRpta = $this->advanceEntryOperation( $inputs[ 'solicitud_token' ] );    
            }
            return $middleRpta;
        }
        catch ( Exception $e )
        {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function advanceEntryOperation( $solicitud_token )
    {
        try
        {
            $solicitud = Solicitud::findByToken( $solicitud_token );
            $entries = $this->generateDepositEntryData( $solicitud );
            return $this->advanceEntryTransaction( $solicitud , $entries ); 
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function advanceEntryTransaction( $solicitud , array $entries )
    {
        DB::beginTransaction();

        $oldIdEstado = $solicitud->id_estado;

        if( $solicitud->idtiposolicitud == REEMBOLSO )
        {
            $solicitud->id_estado = GENERADO;
        }
        elseif( in_array( $solicitud->idtiposolicitud , array( SOL_REP , SOL_INST ) ) )
        {
            $solicitud->id_estado = GASTO_HABILITADO;    
        }
        else
        {
            return $this->warningException( 'No se pudo identificar el tipo de la solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        $solicitud->save();

        $entryAgrupation = [];

        foreach( $entries as $entry ) 
        {
            $tbEntry = new Entry;
            $tbEntry->insertAdvanceEntry( $entry , $solicitud->id );
            $entryAgrupation[ $tbEntry->id_solicitud ][ $tbEntry->tipo_asiento ][] = $tbEntry;
        }

        $toUser = $solicitud->id_user_assign;
        
        $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
        
        if( $middleRpta[ status ] === ok ) 
        {
            $this->generateBagoSeat( $entryAgrupation );
            $middleRpta[ 'asiento' ] = substr( Entry::where( 'tipo_asiento' , TIPO_ASIENTO_ANTICIPO )->where( 'id_solicitud' , $solicitud->id )->first()->penclave , 0 , 5 );
            Session::put( 'state' , R_REVISADO );
            DB::commit();
        }
        return $middleRpta;
        
        DB::rollback();
    }

    public function massApprovedSolicitudes()
    {
        try 
        {
            $inputs = Input::all();
            $rules = array( 'solicitudes' => 'required|array|min:1' );
            $validator = Validator::make( $inputs , $rules);
            if ( $validator->fails() )
            {
                return $this->warningException($this->msg2Validator($validator), __FUNCTION__, __LINE__, __FILE__);
            }
            else 
            {
                $status = array( ok => array() , error => array() );
                $message = '';
                foreach ( $inputs[ 'solicitudes' ] as $solicitud ) 
                {
                    $solicitud = Solicitud::where( 'token' , $solicitud[ 'token' ] )->first();
                    
                    if ( Auth::user()->type == GER_COM )
                    {
                        $solicitudProducts = $solicitud->orderProducts;
                        $fondo = array();

                        foreach ( $solicitudProducts as $solicitudProduct )
                            $fondo[] = $solicitudProduct->id_fondo_marketing . ',' . $solicitudProduct->id_tipo_fondo_marketing;

                        $inputs = array(
                            'idsolicitud'            => $solicitud->id,
                            'monto'                  => $solicitud->detalle->monto_actual ,
                            'producto'               => $solicitud->orderProducts()->lists('id') ,
                            'anotacion'              => $solicitud->anotacion ,
                            'fondo_producto'         => $fondo ,
                            'derivacion'             => 0 ,
                            'pago'                   => $solicitud->detalle->id_pago , 
                            'ruc'                    => $solicitud->detalle->num_ruc ,
                            'modificacion_productos' => 0 ,
                            'modificacion_clientes'  => 0 ); 

                        $solProducts = $solicitud->orderProducts();
                        if ($solicitud->id_estado == DERIVADO)
                            $inputs['monto_producto'] = array_fill(0, count($solProducts->get()), $inputs['monto'] / count($solProducts->get()));
                        else
                            $inputs['monto_producto'] = $solProducts->lists('monto_asignado');
                        $rpta = $this->acceptedSolicitudTransaction( $solicitud->id , $inputs );
                    }
                    elseif( Auth::user()->type == CONT )
                    {
                        $rpta = $this->checkSolicitudTransaction( $solicitud[ 'token' ] );
                    }
                    if ($rpta[status] != ok) {
                        $status[error][] = $solicitud['token'];
                        $message .= $message . 'No se pudo procesar la Solicitud N°: ' . $solicitud->id . ': ' . $rpta[description] . '. ';
                    } else
                        $status[ok][] = $solicitud['token'];
                }
                if ( empty( $status[ error ] ) )
                    return array( status => ok , 'token' => $status , description => 'Se aprobaron las solicitudes seleccionadas' );
                else if ( empty( $status[ ok ] ) )
                    return array( status => danger , 'token' => $status , description => substr( $message , 0, -1 ) );
                else
                    return array( status => warning , 'token' => $status , description => substr( $message , 0, -1 ) );
            }
        } 
        catch( Exception $e ) 
        {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function generateDepositEntryData( $solicitud )
    {
        $entries   = [];
        $entries[] = $this->generateDepositEntryDebitData( $solicitud );
        $entries[] = $this->generateDepositEntryCreditData( $solicitud );
        return $entries;
    }

    private function generateDepositEntryDebitData( $solicitud )
    {
        $detail               = $solicitud->detalle;
        $investment           = $solicitud->investment;
        $account              = $investment->accountFund;
        
        $entry                 = new stdClass;
        $entry->account_name   = $account->nombre;
        $entry->account_number = $account->num_cuenta;
        $entry->origin         = $detail->deposit->updated_at;
        $entry->d_c            = ASIENTO_GASTO_BASE;
        $entry->import         = $detail->soles_import;
        $entry->caption        = $solicitud->deposit_debit_caption;
        return $entry;
    }

    private function generateDepositEntryCreditData( $solicitud )
    {
        $detail                = $solicitud->detalle;
        $deposit               = $detail->deposit;
        $investment            = $solicitud->investment;
        $account               = $investment->accountFund;

        $entry                 = new stdClass;
        $entry->account_name   = $deposit->bagoAccount->ctanombrecta;
        $entry->account_number = $deposit->num_cuenta;
        $entry->origin         = $deposit->updated_at;
        $entry->d_c            = ASIENTO_GASTO_DEPOSITO;
        $entry->import         = $detail->soles_deposit_import;
        $entry->caption        = $solicitud->deposit_credit_caption;
        return $entry;
    }

}