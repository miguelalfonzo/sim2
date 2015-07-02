<?php

namespace Maintenance;

use \DB;
use \Log;
use \View;
use \Input;
use \Exception;
use \BaseController;
use \Parameter\Parameter;
use \Maintenance\Fondos;
use \Maintenance\FondosCategorias;
use \Maintenance\FondosSubCategorias;

class MaintenanceController extends BaseController
{
	public function fondoView(){
		return View::make('Maintenance.Fondo.fondonew');
	}

} 
