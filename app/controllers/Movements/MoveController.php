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
        $solicituds = array();
        if (!empty($date))
        {
            $dates = $this->setDates($date);
            $rpta = $this->searchTransaction(R_FINALIZADO,array(Auth::user()->id),$dates['start'],$dates['end']);
            if ($rpta[status] == ok)
            {
                $view = View::make('template.list_estado_cuenta')->with($rpta[data])->render();
                $rpta = $this->setRpta($view);                
            }
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