<?php

use \System\SolicitudeHistory;
use \User;
use \Common\State;

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

    protected function internalException($exception)
    {
        $rpta = array();
        $rpta[status] = error;
        $rpta[description] = desc_error;
        Log::error($exception);
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

    public function postman($name, $idsolicitud, $description, $fromEstado, $toEstado, $fromName){
        $data = array(
            'firstname'     => $name,
            'fromUser'      => $fromName,
            'description'   => $description,
            'estados'       => '| '.  $fromEstado .' - '. $toEstado .' |',
            'idsolicitude'   => $idsolicitud
        );
        $subject = 'Solicitud N° '.$idsolicitud;
        Mail::send('emails.notification', $data, function($message) use ($subject){
            $message->to('jatuncar@bagoperu.com.pe', 'José Atuncar')->subject($subject);
        });
    }

    public function setStatus($description, $status_from, $status_to, $user_from_id, $user_to_id, $idsolicitude){
        $fromStatus = State::where('idestado', $status_from)->first();
        $toStatus = State::where('idestado', $status_to)->first();

        $fromUser = User::where('id', $user_from_id)->first();
        $toUser = User::where('id', $user_to_id)->first();

        $toName = $toUser->getName();
        $fromName = $fromUser != null ? $fromUser->getName() : '';
        $statusNameFrom = $fromStatus == null ? '' : $fromStatus->nombre;
        $statusNameTo = $toStatus == null ? '' : $toStatus->nombre;
        $this->postman($toName, $idsolicitude, $description, $statusNameFrom, $statusNameTo, $fromName);
        
        $idestadoFrom = $fromStatus == null ? null : $fromStatus->idestado;
        $idestadoTo = $toStatus == null ? null : $toStatus->idestado;

        $this->updateStatusSolicitude($description, $idestadoFrom, $idestadoTo, $fromUser->type, $toUser->type, $idsolicitude, 0);
    }

    public function updateStatusSolicitude($description, $status_from, $status_to, $user_from, $user_to, $idsolicitude, $notified){
        $statusSolicitude               = new SolicitudeHistory;
        $statusSolicitude->id           = $statusSolicitude->lastId()+1;
        $statusSolicitude->description  = $description;
        $statusSolicitude->status_from  = $status_from;
        $statusSolicitude->status_to    = $status_to;
        $statusSolicitude->user_from    = $user_from;
        $statusSolicitude->user_to      = $user_to;
        $statusSolicitude->idsolicitude = $idsolicitude;
        $statusSolicitude->notified     = $notified;
        return $statusSolicitude->save();
    }

}
