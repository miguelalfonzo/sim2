<?php

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

    public function postman(){
        $data = array('firstname'=>'Manuel');
        Mail::send('emails.notification', $data, function($message){
            $message->to('manueltemple@gmail.com', 'Manuel Temple')->subject('Hello!');
        });
        // Mail::send('emails.welcome', $data, function($message)
        // {
        //     $message->to('manueltemple@gmail.com', 'Manuel Temple')->subject('Hello!');
        // });
    }

}
