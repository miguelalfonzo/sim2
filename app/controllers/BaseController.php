<?php

use \Dmkt\Solicitud;
use \Common\State;
use \System\SolicitudHistory;

class BaseController extends Controller 
{
    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => array('post', 'put', 'patch', 'delete')));
    }

    protected function getDay( $type=1 )
    {
        $currentDate = getdate();
        $toDay = $currentDate['mday']."/".str_pad( $currentDate['mon'] , 2 , '0' , STR_PAD_LEFT )."/".$currentDate['year'];
        $lastDay = '06/'.str_pad( ( $currentDate['mon'] + 1 ) , 2 , '0' , STR_PAD_LEFT ).'/'.$currentDate['year'];
        
        if ( $type == 2 )
        {
            $toDay = $currentDate['year']."/".str_pad( $currentDate['mon'] , 2 , '0' , STR_PAD_LEFT )."/".$currentDate['mday'].' '.$currentDate['hours'].':'.$currentDate['minutes'].':'.$currentDate['seconds'];
            $lastDay = $currentDate['year']."/".str_pad( $currentDate['mon'] , 2 , '0' , STR_PAD_LEFT )."/".'06'.' '.$currentDate['hours'].':'.$currentDate['minutes'].':'.$currentDate['seconds'];   
        }

        $date = array( 'toDay' => $toDay , 'lastDay' => $lastDay );
        return $date;
    }

    protected function msgValidator($validator)
    {
        $rpta = '';
        foreach ($validator->messages()->all() as $msg)
            $rpta .= $msg.'-';
        return $rpta;
    }

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    protected function validateInput($data,$keys)
    {
        $rpta = array();
        $rpta[description] = "";
        $i = 0;
        $akeys = explode(',',$keys);
        foreach($akeys as $key)
        {
            if (!isset($data[$key]))
            {
                $rpta[status] = warning;
                $rpta[description] = $rpta[description].$key.", ";
                $i++;
            }
        }
        if ($i > 0)
        {
            $rpta[description] = substr($rpta[description],0,-1);
            $rpta[description] = $i.' Missing(s) Input(s): '.$rpta[description];
        }
        else
        {
            $rpta = [];
            $rpta[status] = ok;
        }
        return $rpta;
    }

    protected function warningException( $description , $function , $line , $file )
    {
        Mail::send( 'soporte' , array( 'description' => $description , 'function' => $function , 'line' => $line , 'file' => $file ) , 
        function( $message ) use( $function )
        {
            $message->to( SOPORTE_EMAIL );
            $message->subject( warning.' - Function: '.$function);      
        });
        return array( status => warning , description => $description );
    }

    protected function internalException( $exception , $function )
    {
        Log::error( $exception );
        Log::error( json_encode( oci_error() ) );
        Log::error( $exception->getMessage() );
        Mail::send('soporte', array( 'exception' => $exception ), function( $message ) use( $function )
        {
            $message->to(SOPORTE_EMAIL);
            $message->subject(error.' - Function: '.$function);      
        });
        return array( status => error , description => $exception );
    }

    private function validateJson()
    {
        $rpta = array();
        if (json_last_error() === 0)
        {
            $rpta[status] = ok;
        }
        else
        {
            $rpta[status] = warning;
            $rpta[description]='Error parseando Json: '.json_last_error_msg();
        }
        return $rpta;
    }

    public function postman($idSolicitud, $fromEstado, $toEstado, $toUser)
    {
        try 
        {
            $solicitud = Solicitud::find( $idSolicitud );
            $subject    = 'Solicitud N° '.$idSolicitud;
            
            $user_email = $toUser->lists('email');
            $user_name  = array();
            foreach ( $toUser as $user )
                $user_name[] = $user->getName();
            
            Log::error( $user_email );
            Log::error( $user_name );
            
            if( count( $user_name ) != 0 && count( $user_email ) != 0 )
            {
                $data = array(
                    'solicitud_id'          => $idSolicitud ,
                    'solicitud_estado'      => $toEstado ,
                    'solicitud_titulo'      => $solicitud->titulo ,
                    'solicitud_descripcion' => $solicitud->descripcion
                );

                Mail::send('emails.notification', $data, function($message) use ($subject, $user_name, $user_email)
                {
                    $message->to( $user_email , $user_name )->subject($subject);
                });
                return $this->setRpta();
            }
            else
            {
                Mail::send('soporte', array( 'subject' => 'No se pudo enviar email, debido a que el usuario o password son Incorrectos' ) , function($message) use ($subject)
                {
                    $message->to(SOPORTE_EMAIL)->subject('PostMan [BaseController:89]:');
                });
                Log::error("IDKC [BaseController:109]: No se pudo enviar email, debido a que el usuario o password son Incorrectos");
                return $this->warningException( 'No se encontro los usuarios para la solicitud n°: '.$idSolicitud , __FUNCTION__ , __LINE__ , __FILE__ );
            }
        }
        catch ( Swift_TransportException $e )
        {
            return $this->warningException( "Swift_TransportException: No se pudo enviar el correo al destinatario" , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }

    public function setStatus( $status_from , $status_to , $user_from_id , $user_to_id , $idSolicitud )
    {
        try
        {
            Log::error( $user_to_id );
            
            $fromStatus = State::where( 'id' , $status_from )->first();
            $toStatus   = State::where( 'id' , $status_to )->first();
            
            $statusNameFrom = $fromStatus == null ? '' : $fromStatus->nombre;
            $statusNameTo   = $toStatus == null ? '' : $toStatus->nombre;
            
            $fromUser   = User::where( 'id' , $user_from_id )->first();
            
            if ( is_array( $user_to_id ) )
                $toUser     = User::whereIn( 'id' , $user_to_id )->get();
            else
                $toUser     = User::where( 'id' , $user_to_id )->get();
            
            Log::error( json_encode( $toUser ));
            
            
            Log::error('status');
            Log::error( $statusNameFrom );
            Log::error( $statusNameTo );

            // POSTMAN: send email
            $rpta = $this->postman( $idSolicitud , $statusNameFrom , $statusNameTo , $toUser );
            Log::error( $rpta );
            if ( $rpta[status] == ok  )
                return $this->updateStatusSolicitude( $status_from , $status_to , $fromUser , $idSolicitud , 1 );
            else if ( $rpta[status] == warning )
                return $this->updateStatusSolicitude( $status_from , $status_to , $fromUser , $idSolicitud , 0 );
            else
                return $rpta;
        }
        catch (Exception $e)
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function updateStatusSolicitude( $status_from , $status_to , $user_from , $idSolicitud , $notified )
    {   
        try
        {         
            $fData = array(
                'status_to'   => $status_to,
                'id_solicitud'=> $idSolicitud,
                'user_to'     => Session::pull( 'usertype' )
            );
            $statusSolicitude = SolicitudHistory::firstOrNew($fData);
            if ( ! isset( $statusSolicitude->rn ) )
            {
                Log::error( json_encode($statusSolicitude));
                $statusSolicitude->id           = $statusSolicitude->lastId() + 1;
            }
            else
                $statusSolicitude = SolicitudHistory::find( $statusSolicitude->id );
            $statusSolicitude->status_from  = $status_from;
            $statusSolicitude->user_from    = $user_from->type;
            $statusSolicitude->notified     = $notified;
            $statusSolicitude->updated_at   = Carbon\Carbon::now();
            $statusSolicitude->save();
            Log::error( json_encode( $statusSolicitude ));
            return $this->setRpta();
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }

    protected function setRpta( $data='' )
    {
        return array( status => ok , 'Data' => $data );
    }

    protected function searchSolicituds($estado,$idUser,$start,$end)
    {
        try
        {
            $solicituds = Solicitud::whereRaw("created_at between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')+1");
            if ( in_array( Auth::user()->type , array ( REP_MED , SUP , GER_PROD , GER_PROM ) ) )
            {
                Log::error( Auth::user()->id );
                $solicituds->where( function ( $query )
                {
                    $query->where( function ( $query )
                    {
                        $query->where( 'created_by' , '<>' , Auth::user()->id )
                        ->where( function ( $query )
                        {
                            $query->where( 'id_user_assign' , '<>' , Auth::user()->id )->orWhereNull( 'id_user_assign' );
                        })->whereHas( 'gerente' , function( $query )
                        {
                            $query->where( 'id_gerprod' , Auth::user()->id );
                        });
                    })->orWhere( function ( $query )
                    {
                        $query->where( 'created_by' , Auth::user()->id )->orWhere( function ( $query )
                        {
                            $query->where( 'created_by' , '<>' , Auth::user()->id )->where( 'id_user_assign' , Auth::user()->id );
                        });
                    });
                });
            }
            if ( Auth::user()->type == TESORERIA ) 
            {
                if ($estado != R_FINALIZADO)
                {
                    $solicituds->whereIn( 'idestado' , array( DEPOSITO_HABILITADO , DEPOSITADO ) );
                }
                else
                {
                    $solicituds->whereHas( 'detalle' , function ($q)
                    {
                        $q->whereNotNull('iddeposito');
                    });
                }
            }
            else if ( Auth::user()->type == CONT )
                $solicituds->where( 'id_estado' , '<>' , ACEPTADO );

            if ( $estado != R_TODOS)
            { 
                Log::error( ' estado ');
                $solicituds->whereHas( 'state' , function ( $q ) use( $estado )
                {
                    $q->whereHas( 'rangeState' , function ( $t ) use( $estado )
                    {
                        $t->where( 'id' , $estado );
                    });
                });
                
            }
            
            $solicituds = $solicituds->orderBy('id', 'ASC')->get();
            
            return $this->setRpta($solicituds);
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e, __FUNCTION__ );
        }
    }

    protected function userType()
    {
        try
        {
            $user = Auth::user();
            if ( $user->type == SUP )
            { 
                $reps = $user->Sup->Reps;
                $users_ids = array();
                foreach ($reps as $rm)
                    $users_ids[] = $rm->iduser;
                $users_ids[] = $user->id;
                return $this->setRpta($users_ids);
            }
            else if ( $user->type == GER_PROD )
            {
                $solicitud_ids = [];
                $solicituds = $user->gerProd->solicituds;
                foreach ($solicituds as $sol)
                    $solicitud_ids[] = $sol->idsolicitud; // jalo los ids de las solicitudes pertenecientes al gerente de producto
                $solicitud_ids[] = 0; // el cero va para que tenga al menos con que comparar, para que no salga error
                return $this->setRpta( $solicitud_ids );
            }
            else if ( ! is_null( $user->simApp ) )
                return $this->setRpta( array( $user->id ) );
            else
                return $this->warningException( 'Se ha solicitado buscar solicitudes por un usuario no autorizado: '.$user->id , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        catch (Exception $e)
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function viewTestUploadImg(){
        return View::make('test.testUploadImg');
    }
    public function viewTestUploadImgSave() {

        $fileList = Input::file('image');

        // $input = array('image' => $file);
        // $rules = array(
        //     'image' => 'image'
        // );

        // $validator = Validator::make($input, $rules);
        if ( count($fileList) == 0 )
        {
            return Response::json(array(
                'success'   => false,
                'errors'    => 'No se pudo Cargar Archivo'
            ));
        }
        else {
            $resultFileList = array();
            
            foreach ($fileList as $fileKey => $fileItem) {
                
                $destinationPath    = FILESTORAGE_DIR;
                $fileName           = pathinfo($fileItem->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExt            = pathinfo($fileItem->getClientOriginalName(), PATHINFO_EXTENSION);
                $fileNameMD5        = md5(uniqid(rand(), true));

                $fileStorage                = new FileStorage;
                $fileStorage->id            = $fileNameMD5;
                $fileStorage->name          = pathinfo($fileItem->getClientOriginalName(), PATHINFO_FILENAME);
                $fileStorage->extension     = $fileExt;
                $fileStorage->directory     = $destinationPath;
                $fileStorage->app           = APP_ID;
                $fileStorage->save();

                $fileItem->move($destinationPath, $fileNameMD5.'.'.$fileExt);
                $resultFileList[] = array(
                    'id'   => $fileNameMD5,
                    'name'      => asset($destinationPath.$fileNameMD5.'.'.$fileExt)
                );

            }
            return Response::json(array(
                'success'   => true,
                'fileList'  => $resultFileList
            ));
        }
 
    }
}
