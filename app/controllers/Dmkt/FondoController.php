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
use \Excel;


class FondoController extends BaseController
{


    public function test()

    {


        //$data = DB::table('FONDOINSTITUCIONAL')->select('repmed','institucion','cuenta','supervisor','total')->toJson();
        /*$dato = FondoInstitucional::all();


        Excel::create('Filename4', function($excel) use($dato) {

            $excel->sheet('Sheetname', function($sheet) use($dato) {

                $sheet->fromArray($dato);
                $sheet->row(1, array(
                    'test1', 'test2', 'test4' , 'test5','test3'
                ));

            });

        })->export('xls');*/
        Excel::load('testfondo.xlsx', function ($reader) {

            // Getting all results
            //$reader->skip(1);
            //$results = $reader->get();

            // ->all() is a wrapper for ->get() and will work the same
            $results = $reader->all();
            echo json_encode($results);
        });


        // Demo::display();


    }

    function getRegister()
    {

        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.register_fondo')->with('fondos', $fondos);
    }

    function postRegister()
    {

        $inputs = Input::all();
        $fondo = new FondoInstitucional;
        $fondo->idfondo = $fondo->searchId() + 1;
        $fondo->institucion = $inputs['institucion'];
        $fondo->repmed = $inputs['repmed'];
        $fondo->supervisor = $inputs['supervisor'];
        $fondo->total = $inputs['total'];
        $fondo->cuenta = $inputs['cuenta'];
        $fondo->codrepmed = $inputs['codrepmed'];
        $fondo->save();
        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.list_fondos')->with('fondos', $fondos);

    }

    function getFondos()
    {

        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.list_fondos')->with('fondos', $fondos);
    }

    function getFondo($id)
    {
        $fondo = FondoInstitucional::find($id);
        return $fondo;
    }

    function delFondo($id)
    {
        $fondo = FondoInstitucional::find($id);
        $fondo->delete();
        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.list_fondos')->with('fondos', $fondos);
    }

    function updateFondo()
    {
        $inputs = Input::all();
        $fondo = FondoInstitucional::find($inputs['idfondo']);
        $fondo->institucion = $inputs['institucion'];
        $fondo->repmed = $inputs['repmed'];
        $fondo->supervisor = $inputs['supervisor'];
        $fondo->total = $inputs['total'];
        $fondo->cuenta = $inputs['cuenta'];
        $fondo->codrepmed = $inputs['codrepmed'];
        $fondo->save();
        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.list_fondos')->with('fondos', $fondos);

    }

    function getRepresentatives()
    {
        //$representatives = DB::table('FICPE.VISITADOR')
        //   ->select('visvisitador','vispaterno','vismaterno','visnombre','vislegajo')->where('VISACTIVO','S')->get();
        $representatives = DB::select("select visvisitador,vispaterno,vismaterno,visnombre,vislegajo from FICPE.VISITADOR where VISACTIVO = 'S' and LENGTH(VISLEGAJO) = 8 ");
        return json_encode($representatives);
    }

    function getCtaBanc($dni){
        $cta = CtaRm::where('codbeneficiario',$dni)->where('tipo','H')->first()->codbeneficiario;
        return $cta;
    }
}