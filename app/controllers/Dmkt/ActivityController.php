<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 11/08/14
 * Time: 06:30 PM
 */

namespace Dmkt;
use \BaseController;
use \View;
use \DB;

class ActivityController extends BaseController{


    public function newActivity(){

        return View::make('Dmkt.new_activity');
    }


}