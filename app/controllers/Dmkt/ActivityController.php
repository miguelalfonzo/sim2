<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 11/08/14
 * Time: 06:30 PM
 */

namespace Dmkt;
use \Common\SubTypeActivity;

use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;


class ActivityController extends BaseController{


    public function newActivity(){

        return View::make('Dmkt.new_activity');
    }

    public function registerActivity(){


        $inputs = Input::all();
        $activity = new Activity;
        $activity->idactividad  = $activity->searchId() + 1;
        $activity->estado = 1;
        $activity->idsolicitud = $inputs['idsolicitude'];
        $activity->titulo = $inputs['titulo'];
        $activity->save();

        return Redirect::to('show_sup');


    }

}