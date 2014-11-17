<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 13/11/2014
 * Time: 05:15 PM
 */
namespace Dmkt;

use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Auth;
use \Validator;


class FondoController extends BaseController{


    function getRegister(){

        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.register_fondo')->with('fondos',$fondos);
    }

    function postRegister(){

        $inputs = Input::all();
        $fondo = new FondoInstitucional;
        $fondo->idfondo = $fondo->searchId() +1;
        $fondo->institucion = $inputs['institucion'];
        $fondo->repmed = $inputs['repmed'];
        $fondo->supervisor = $inputs['supervisor'];
        $fondo->total = $inputs['total'];
        $fondo->cuenta = $inputs['cuenta'];
        $fondo->save();
        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.list_fondos')->with('fondos',$fondos);

    }
    function getFondos(){

        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.list_fondos')->with('fondos',$fondos);
    }

}