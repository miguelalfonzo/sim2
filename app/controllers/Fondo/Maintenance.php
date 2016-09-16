<?php

namespace Fondo;

use \BaseController;
use \View;

class Maintenance extends BaseController
{

	public function subCategoryView()
	{
        return View::make( 'fondo.maintenance.view' );    
	}

}