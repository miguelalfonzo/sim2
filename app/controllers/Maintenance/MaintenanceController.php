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
use \Dmkt\Marca;

class MaintenanceController extends BaseController
{
	public function fondoView(){
		dd(json_encode(FondosCategorias::with('FondosSubCategorias.Fondos')->get()));
		// dd(json_encode(Fondos::with('Marca', 'FondosSubCategorias.FondosCategorias')->get()));
		// dd(json_encode(Marca::with('Fondos.FondosSubCategorias.FondosCategorias')->get()));
		return View::make('Maintenance.Fondo.fondonew');
	}

} 
