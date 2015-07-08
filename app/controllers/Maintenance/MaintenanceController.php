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
		$result = array();
		// dd(json_encode(FondosSubCategorias::with('FondosCategorias', 'Fondos')->get()));
		// dd(json_encode(Fondos::with('Marca', 'FondosSubCategorias.FondosCategorias')->get()));
		// dd(json_encode(Marca::with('Fondos.FondosSubCategorias.FondosCategorias')->get()));
		// $result = Fondos::with('Marca', 'FondosSubCategorias.FondosCategorias')->get();
		$result['fondosCategorias'] = FondosCategorias::with(array('FondosSubCategorias' => function($query)
								{
								    $query->orderBy('position', 'asc');
								}))
								->select('id', 'descripcion')
								->orderBy('position', 'asc')
								->get();
		$result['fondos'] = DB::select(DB::raw('SELECT '.
									  'FC.DESCRIPCION AS CATEGORIA,'.
									  'FSC.DESCRIPCION AS SUBCATEGORIA,'.
									  'M.DESCRIPCION AS MARCA, '.
									  'F.SALDO AS SALDO, '.
									  'FC.ID AS CATEGORIA_ID, '.
									  'FSC.ID AS SUBCATEGORIA_ID, '.
									  'M.ID AS MARCA_ID '.
									'FROM '.
									  'FONDOS F '.
									  'LEFT JOIN OUTDVP.MARCAS M '.
									    'ON F.MARCA_ID = M.ID '.
									  'LEFT JOIN FONDOS_SUBCATEGORIAS FSC '.
									    'ON F.FONDOS_SUBCATEGORIA_ID = FSC.ID '.
									  'LEFT JOIN FONDOS_CATEGORIAS FC '.
									    'ON FSC.FONDOS_CATEGORIAS_ID = FC.ID '.
									'ORDER BY '.
									  'M.DESCRIPCION ASC, '.
									  'FC.POSITION ASC, '.
									  'FSC.POSITION ASC'));
		dd(json_encode($result));
		return View::make('Maintenance.Fondo.fondonew', $result);
	}

} 
