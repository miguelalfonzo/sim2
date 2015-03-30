<?php

namespace Movements;

use \BaseController;
use \Input;
use \Log;
use \DateTime;
use \Auth;
use \View;

class MoveController extends BaseController
{
	public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('active-user');
    }

    public function searchMove()
    {
    	$date = Input::get('date');
        if (!empty($date))
        {
            $dates = $this->setDates($date);
            $rpta = $this->userType();
            if ($rpta[status] == ok)
            {
                $rpta = $this->searchSolicituds(R_FINALIZADO,$rpta[data],$dates['start'],$dates['end']);
                if ($rpta[status] == ok)
                {
                    $view = View::make('template.list_estado_cuenta')->with( array( 'solicituds' => $rpta[data] ))->render();
                    $rpta = $this->setRpta($view);                
                }
            }
    	}
        else
        {
            $rpta = $this->warningException('El campo fecha se encuentra vacio',__FUNCTION__,'Empty Date');
        }
        return $rpta;
    }

    private function setDates($date)
    {
        $dates = array(
            'start' => (new DateTime('01-'.$date))->format('d/m/Y'),
            'end'   => (new DateTime('01-'.$date))->format('t/m/y')
        );
        return $dates;
    }
}