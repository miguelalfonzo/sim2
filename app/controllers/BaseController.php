<?php

use \System\SolicitudeHistory;
use \User;
use \Common\State;
use \Dmkt\Solicitude;
use \Swift_TransportException;

class BaseController extends Controller {

    /**
     * Setup the layout used by the controller.
     *
     * @return \BaseController
     */

    public function __construct()
    {
        // Perform CSRF check on all post/put/patch/delete requests
        $this->beforeFilter('csrf', array('on' => array('post', 'put', 'patch', 'delete')));
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

    protected function warningException($exception,$function,$description)
    {
        $rpta = array();
        $rpta[status] = warning;
        $rpta[description] = $description;
        Log::error($exception);
        Mail::send('soporte', array( 'msg' => $exception ), 
            function($message) use($function)
            {
                $message->to(SOPORTE_EMAIL);
                $message->subject(warning.'-Function: '.$function);      
            }
        );
        return $rpta;
    }

    protected function internalException($exception,$function)
    {
        $rpta = array();
        $rpta[status] = error;
        $rpta[description] = desc_error;
        Log::error($exception);
        Mail::send('soporte', array( 'msg' => $exception ), 
            function($message) use($function)
            {
                $message->to(SOPORTE_EMAIL);
                $message->subject(error.'-Function: '.$function);      
            }
        );
        return $rpta;
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

    public function postman($idsolicitud, $fromEstado, $toEstado, $toUser){
        try 
        {
            $solicitud = Solicitude::where('idsolicitud', $idsolicitud)->first();
            $msg        = '';
            $subject    = 'Solicitud N° '.$idsolicitud;
            $user_name  = $toUser != null ? $toUser->getName() : '';
            $user_email = $toUser != null ? $toUser->email : '';
            if($user_name != '' && $user_email != ''){
                $data = array(
                    'solicitud_id'          => $idsolicitud,
                    'msg'                   => $msg,
                    'solicitud_estado'      => $toEstado,
                    'solicitud_titulo'      => $solicitud->titulo,
                    'solicitud_descripcion' => $solicitud->descripcion,
                    'solicitud_tipo_moneda' => $solicitud->typemoney->simbolo,
                    'solicitud_monto'       => $solicitud->monto
                );

                Mail::send('emails.notification', $data, function($message) use ($subject, $user_name, $user_email){
                    $message->to($user_email, $user_name)->subject($subject);
                });
            }
            else
            {
                Mail::send('soporte', array( 'subject' => 'No se pudo enviar email, debido a que el usuario o password son Incorrectos' ), function($message) use ($subject)
                {
                    $message->to(SOPORTE_EMAIL)->subject('PostMan [BaseController:89]:');
                });
                Log::error("IDKC [BaseController:109]: No se pudo enviar email, debido a que el usuario o password son Incorrectos");
            }
            $rpta = $this->setRpta();
        }
        catch (Swift_TransportException $e)
        {
            $rpta = $this->warningException($e,__FUNCTION__,"Mail Host: Can't Establish a Conecction");
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function setStatus($description, $status_from, $status_to, $user_from_id, $user_to_id, $idsolicitude,$type){
        try
        {
            $fromStatus = State::where('idestado', $status_from)->first();
            $toStatus   = State::where('idestado', $status_to)->first();
            $fromUser   = User::where('id', $user_from_id)->first();
            $toUser     = User::where('id', $user_to_id)->first();
            $statusNameFrom = $fromStatus == null ? '' : $fromStatus->nombre;
            $statusNameTo   = $toStatus == null ? '' : $toStatus->nombre;
            // POSTMAN: send email
            $rpta = $this->postman($idsolicitude, $statusNameFrom, $statusNameTo, $toUser);
            if ($rpta[status] == ok || $rpta[status] == warning)
            {
                $idestadoFrom = $fromStatus == null ? null : $fromStatus->idestado;
                $idestadoTo = $toStatus == null ? null : $toStatus->idestado;
                $rpta = $this->updateStatusSolicitude($description, $idestadoFrom, $idestadoTo, $fromUser, $toUser, $idsolicitude, 0, $type);
            }
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function updateStatusSolicitude($description, $status_from, $status_to, $user_from, $user_to, $idsolicitude, $notified,$type)
    {   
        try
        {
            $fData = array(
                'status_to'   => $status_to,
                'idsolicitude'=> $idsolicitude,
                'type'        => $type
                );

            $vData = array(
                'description' => $description,
                'status_from' => $status_from,
                'user_from'   => $user_from->type,
                'user_to'     => $user_to->type,
                'iduser_from' => $user_from->id,
                'notified'    => $notified
                );
            $statusSolicitude = SolicitudeHistory::firstOrNew($fData);
            if (!isset($statusSolicitude->rn))
            {
                $statusSolicitude->id           = $statusSolicitude->lastId()+1;
                $statusSolicitude->status_from  = $status_from;
                $statusSolicitude->status_to    = $status_to;
                $statusSolicitude->user_from    = $user_from->type;
                $statusSolicitude->user_to      = $user_to->type;
                $statusSolicitude->iduser_from  = $user_from->id;
                $statusSolicitude->idsolicitude = $idsolicitude;
                $statusSolicitude->notified     = $notified;
                $statusSolicitude->type         = $type;
                $statusSolicitude->save();
            }
            else
            {
                $statusSolicitude->update($vData);
            }
            $rpta = $this->setRpta();
            /**/
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    protected function setRpta( $data='' )
    {
        $rpta = array(status => ok, 'Data' => $data);
        return $rpta;
    }

    protected function searchTransaction($estado,$idUser,$start,$end)
    {
        try
        {
            $solicituds = Solicitude::with(array('histories' => function($q)
            {
                $q->orderBy('created_at','DESC');  
            }));
            $rSolicituds = Solicitude::with(array('histories' => function($q)
            {
                $q->orderBy('created_at','DESC');  
            }));


            // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$

            /*$fondos = FondoInstitucional::where('idrm', Auth::user()->rm->idrm)
                        ->with(array('histories' => function($q)
                        {
                            $q->orderBy('created_at','DESC');  
                        }));
                        if ($estado != R_TODOS)
                        {
                            $fondos->whereHas('state', function ($q) use( $estado )
                            {
                                $q->whereHas('rangeState', function ($t) use( $estado )
                                {
                                    $t->where('id', $estado );
                                });
                            });
                        }
                        $fondos = $fondos->whereRaw("to_date(periodo,'YYYYMM') BETWEEN TRUNC(to_date('$start','DD-MM-YY'),'MM') and TRUNC(to_date('$end','DD-MM-YY'),'MM')")
                        ->orderBy('periodo')->get();
                        $rpta[data]['fondos'] = $fondos;
*/
            // $$$$$$$$$$$$$$$$$$$$$$$$

            if (Auth::user()->type == REP_MED)
            {
                $solicituds->whereIn('iduser', $idUser);
                $rSolicituds->whereNotIn('iduser', $idUser)
                ->where('idresponse',Auth::user()->id);
            }
            else if (Auth::user()->type == SUP)
            {
                $solicituds->whereIn('iduser', $idUser);
            } 
            else if ( Auth::user()->type == TESORERIA ) 
            {
                if ($estado != R_FINALIZADO)
                {
                    $solicituds->where('asiento',ENABLE_DEPOSIT)
                    ->whereNotNull('idresponse');
                }
                else
                {
                    $solicituds->whereNotNull('iddeposito');
                }
            }
            else if ( Auth::user()->type == ASIS_GER ) 
            {
                $solicituds->where('idresponse',$idUser);
            }
            else if ( !(Auth::user()->type == GER_COM || Auth::user()->type == CONT) )
            {
                $solicituds->whereIn('idsolicitud', $idUser);
            }
            if ($start != null && $end != null)
                $solicituds->whereRaw("created_at between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')+1");
                $rSolicituds->whereRaw("created_at between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')+1");

            if ($estado != R_TODOS) 
                $solicituds->whereHas('state', function ($q) use($estado)
                {
                    $q->whereHas('rangeState', function ($t) use($estado)
                    {
                        $t->where('id',$estado);
                    });
                });
                $rSolicituds->whereHas('state', function ($q) use($estado)
                {
                    $q->whereHas('rangeState', function ($t) use($estado)
                    {
                        $t->where('id',$estado);
                    });
                });
            $solicituds = $solicituds->orderBy('idsolicitud', 'ASC')->get();
            $rSolicituds = $rSolicituds->orderBy('idsolicitud', 'ASC')->get();
            if ( Auth::user()->type == REP_MED )
                $solicituds->merge($rSolicituds);
            $rpta = $this->setRpta(array('solicituds' => $solicituds));
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    protected function userType()
    {
        try
        {
            $user = Auth::user();
            if ($user->type == SUP)
            { 
                $reps = $user->Sup->Reps;
                $users_ids = array();
                foreach ($reps as $rm)
                    $users_ids[] = $rm->iduser;
                $users_ids[] = $user->id;
                $rpta = $this->setRpta($users_ids);
            }
            else if ($user->type == GER_PROD)
            {
                $solicitud_ids = [];
                $solicituds = $user->GerProd->solicituds;
                foreach ($solicituds as $sol)
                    $solicitud_ids[] = $sol->idsolicitud; // jalo los ids de las solicitudes pertenecientes al gerente de producto
                $solicitud_ids[] = 0; // el cero va para que tenga al menos con que comparar, para que no salga error
                $rpta = $this->setRpta($solicitud_ids);
            }
            else if ($user->type == REP_MED)
                $rpta = $this->setRpta(array($user->id));
            else if ($user->type == ASIS_GER)
                $rpta = $this->setRpta(array($user->id));    
            else if (in_array($user->type,array(GER_COM,CONT,TESORERIA)))
                $rpta = $this->setRpta(array());
            else
                $rpta = $this->warningException('Se ha solicitado buscar solicitudes por un usuario con rol no autorizado: '.$user->type,__FUNCTION__,'Unauthorized User');
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }
}
