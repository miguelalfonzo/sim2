<?php

namespace Devolution;

use \BaseController;
use \Input;
use \Exception;
use \Parameter\Parameter;

class DevolutionController extends BaseController
{  

    public function setDevolucion( $solicitud_id , $numero_operacion_devolucion , $monto_devolucion , $id_estado_devolucion , $id_tipo_devolucion )
	{
		$devolution = new Devolution;
		$devolution->id = $devolution->nextId();
		$devolution->id_solicitud = $solicitud_id;
		$devolution->numero_operacion = $numero_operacion_devolucion;
		$devolution->monto = $monto_devolucion;
		$devolution->id_estado_devolucion = $id_estado_devolucion;
		$devolution->id_tipo_devolucion = $id_tipo_devolucion;
		$devolution->save();
	}

}