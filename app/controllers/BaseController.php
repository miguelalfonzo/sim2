<?php

use \System\SolicitudeHistory;
use \User;
use \Common\State;
use \Dmkt\Solicitude;

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
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function setStatus($description, $status_from, $status_to, $user_from_id, $user_to_id, $idsolicitude){
        try
        {
            $fromStatus = State::where('idestado', $status_from)->first();
            $toStatus   = State::where('idestado', $status_to)->first();
            $fromUser   = User::where('id', $user_from_id)->first();
            $toUser     = User::where('id', $user_to_id)->first();
            /*dd($toUser);*/
            //$toUser->email
            //$toUser->getName()   
            //$toName         = $toUser->getName();
            //$fromName       = $fromUser != null ? $fromUser->getName() : '';
            $statusNameFrom = $fromStatus == null ? '' : $fromStatus->nombre;
            $statusNameTo   = $toStatus == null ? '' : $toStatus->nombre;
            // POSTMAN: send email
            $rpta = $this->postman($idsolicitude, $statusNameFrom, $statusNameTo, $toUser);
            if ($rpta[status] == ok)
            {
                $idestadoFrom = $fromStatus == null ? null : $fromStatus->idestado;
                $idestadoTo = $toStatus == null ? null : $toStatus->idestado;
                $rpta = $this->updateStatusSolicitude($description, $idestadoFrom, $idestadoTo, $fromUser->type, $toUser->type, $idsolicitude, 0);
             }
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;

    }

    public function updateStatusSolicitude($description, $status_from, $status_to, $user_from, $user_to, $idsolicitude, $notified)
    {   
        try
        {
            $statusSolicitude               = new SolicitudeHistory;
            $statusSolicitude->id           = $statusSolicitude->lastId()+1;
            $statusSolicitude->description  = $description;
            $statusSolicitude->status_from  = $status_from;
            $statusSolicitude->status_to    = $status_to;
            $statusSolicitude->user_from    = $user_from;
            $statusSolicitude->user_to      = $user_to;
            $statusSolicitude->idsolicitude = $idsolicitude;
            $statusSolicitude->notified     = $notified;
            $statusSolicitude->save();
            $rpta = $this->setRpta();
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

}
