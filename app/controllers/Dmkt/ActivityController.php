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


class ActivityController extends BaseController
{


    function objectToArray($object)
    {
        $array=array();
        foreach($object as $member=>$data)
        {
            $array[$member]=$data;
        }
        return $array;
    }

    public function newActivity()
    {

        return View::make('Dmkt.Rm.new_activity');
    }

    public function registerActivity()
    {


        $inputs = Input::all();
        $idSol =  $inputs['idsolicitude'];
        $activity = new Activity;
        $solicitude = Solicitude::where('idsolicitud',$idSol) ;
        $activity->idactividad = $activity->searchId() + 1;
        $activity->estado = 1;//pending
        $activity->idsolicitud = $idSol;
        $solicitude->monto = $inputs['monto'];
        $solicitude->estado = 4; //approved
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        $activity->save();

        return Redirect::to('show_sup');

    }

    public function listActivitiesSup($id)
    {

        if ($id == 0) {
            $activities = Activity::all();
        } else {
            $activities = Activity::where('estado', $id)->get();
        }
        $view = view::make('Dmkt.Sup.view_activities_sup')->with('activities', $activities);
        return $view;
    }

    public function listActivitiesRM($id)
    {

        if ($id == 0) {
            $activities = Activity::all();
        } else {
            $activities = Activity::where('estado', $id)->get();
        }
        $view = view::make('Dmkt.Rm.view_activities_rm')->with('activities', $activities);
        return $view;
    }
}