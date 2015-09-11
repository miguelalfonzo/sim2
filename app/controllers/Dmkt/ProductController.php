<?php

namespace Dmkt;

use \BaseController;
use \Input;
use \Exception;

class ProductController extends BaseController
{

    public function deleteSolicitudProduct()
    {
    	$inputs = Input::all();
    	SolicitudProduct::find( $inputs[ 'id_solicitud_producto' ] )->delete();
    }

    public function addSolicitudProduct()
    {
    	$inpurs = Input::all();
    	$this->newSolicitudProduct( $inputs );
    }

    public function newSolicitudProduct( $inputs )
    {
		$solProduct               = new SolicitudProduct;
		$solProduct->id           = $solProduct->lastId() + 1;
		$solProduct->id_solicitud = $inputs[ 'id_solicitud' ];
		$solProduct->id_producto  = $inputs[ 'id_producto' ];
		$solProduct->save();
    }

}