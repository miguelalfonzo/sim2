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
        Excel::load('testfondo.xlsx', function($reader)
        {
            
            // Getting all results
            //$reader->skip(1);
            //$results = $reader->get();
            
            // ->all() is a wrapper for ->get() and will work the same
            $results = $reader->all();
            echo json_encode($results);
        });
        
        // Demo::display();
        
    }
    
    function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }
    
    function getRegister()
    {
        
        $fondos = FondoInstitucional::all();
        return View::make('Dmkt.AsisGer.register_fondo')->with('fondos', $fondos);
    }
    
    function postRegister()
    {
        
        $inputs = Input::all();
        $mes = explode('-', $inputs['mes']);
        $periodo = $mes[1].$mes[0];
        $verifyMonth = FondoInstitucional::where('periodo', $periodo)->where('terminado', TERMINADO)->get();
        if(count($verifyMonth) != 0)
        {
            return 'blocked';
        }

        $fondo              = new FondoInstitucional;
        $fondo->idfondo     = $fondo->searchId() + 1;
        $fondo->institucion = $inputs['institucion'];
        $fondo->repmed      = $inputs['repmed'];
        $fondo->supervisor  = $inputs['supervisor'];
        $fondo->total       = $inputs['total'];
        $fondo->cuenta      = $inputs['cuenta'];
        $fondo->idrm        = $inputs['codrepmed'];
        $token              = sha1(md5(uniqid($fondo->idfondo, true)));
        $fondo->token       = $token;
        $fondo->periodo     = $periodo;
        $fondo->save();
        $start  = $inputs['start'];
        $fondos = $this->getFondos($start);
        return $fondos;
        
    }
    
    function getFondos($start, $export = 0)
    {
        $periodo = $this->periodo($start);
        if ($export) {
            $fondos = FondoInstitucional::where('periodo', $periodo)->get(array(
                'institucion',
                'repmed',
                'cuenta',
                'supervisor',
                'total'
            ));
            return $fondos;
        } else {
            $fondos = FondoInstitucional::where('periodo', $periodo)->get();
            $estado = 0;
            foreach ($fondos as $fondo) {
                if($fondo->terminado == TERMINADO)
                {
                    $estado = TERMINADO;
                }
            }
            return View::make('Dmkt.AsisGer.list_fondos')->with('fondos', $fondos)->with('sum', $fondos->sum('total'))->with('estado', $estado);
        }
        
    }
    function getFondosTesoreria($start, $export = 0)
    {
        $startArray = explode("-", $start);
        $endDay     = $this::getLastDayOfMonth($startArray[0], $startArray[1]);
        $st         = $start;
        $start      = '01-' . $st;
        $end        = $st[0] . $st[1];
        
        /*$mes   = array(
        
        '01' => 31,
        '02' => 28,
        '03' => 31,
        '04' => 30,
        '05' => 31,
        '06' => 30,
        '07' => 31,
        '08' => 31,
        '09' => 30,
        '10' => 31,
        '11' => 30,
        '12' => 31
        );*/
        
        //$end = $mes[$end] . '-' . $st;
        $end = $endDay . '-' . $st;
        
        if ($export) {
            $fondos = FondoInstitucional::whereRaw("created_at between to_date('$start' ,'DD-MM-YYYY') and to_date('$end' ,'DD-MM-YYYY')+1")->get(array(
                'institucion',
                'repmed',
                'cuenta',
                'supervisor',
                'total'
            ));
            return $fondos;
        } else {
            $fondos = FondoInstitucional::whereRaw("created_at between to_date('$start' ,'DD-MM-YYYY') and to_date('$end' ,'DD-MM-YYYY')+1")->get();
            $view   = View::make('Treasury.list_fondos')->with('fondos', $fondos)->with('sum', $fondos->sum('total'));
            $data   = array(
                'view' => $view,
                'total' => $fondos->sum('total')
            );
            return $view;
        }
        
    }
    function getFondosContabilidad($start, $export = 0)
    {
        $startArray = explode("-", $start);
        $endDay     = $this::getLastDayOfMonth($startArray[0], $startArray[1]);
        
        $st    = $start;
        $start = '01-' . $st;
        $end   = $st[0] . $st[1];
        /* $mes   = array(
        
        '01' => 31,
        '02' => 28,
        '03' => 31,
        '04' => 30,
        '05' => 31,
        '06' => 30,
        '07' => 31,
        '08' => 31,
        '09' => 30,
        '10' => 31,
        '11' => 30,
        '12' => 31
        );
        
        $end = $mes[$end] . '-' . $st;*/
        $end   = $endDay . "-" . $st;
        
        if ($export) {
            $fondos = FondoInstitucional::whereRaw("created_at between to_date('$start' ,'DD-MM-YYYY') and to_date('$end' ,'DD-MM-YYYY')+1")->get(array(
                'institucion',
                'repmed',
                'cuenta',
                'supervisor',
                'total'
            ));
            return $fondos;
        } else {
            $fondos = FondoInstitucional::whereRaw("created_at between to_date('$start' ,'DD-MM-YYYY') and to_date('$end' ,'DD-MM-YYYY')+1")->where('depositado', 1)->get();
            $view   = View::make('Dmkt.Cont.list_fondos')->with('fondos', $fondos)->with('sum', $fondos->sum('total'));
            $data   = array(
                'view' => $view,
                'total' => $fondos->sum('total')
            );
            return $view;
        }
        
    }
    function endFondos($start)
    {

        $startArray = explode("-", $start);
        $endDay     = $this::getLastDayOfMonth($startArray[0], $startArray[1]);
        $st         = $start;
        $start      = '01-' . $st;
        $end        = $st[0] . $st[1];
        /* $mes   = array(
        
        '01' => 31,
        '02' => 28,
        '03' => 31,
        '04' => 30,
        '05' => 31,
        '06' => 30,
        '07' => 31,
        '08' => 31,
        '09' => 30,
        '10' => 31,
        '11' => 30,
        '12' => 31
        );
        
        $end = $mes[$end] . '-' . $st; */
        $end        = $endDay . '-' . $st;
        FondoInstitucional::whereRaw("created_at between to_date('$start' ,'DD-MM-YYYY') and to_date('$end' ,'DD-MM-YYYY')+1")->update(array(
            'terminado' => 1
        ));
        return 'Fondos Terminados';
    }
    function getFondo($id)
    {
        $fondo = FondoInstitucional::find($id);
        return $fondo;
    }
    
    function delFondo()
    {
        $id    = Input::get('idfondo');
        $start = Input::get('start');
        $fondo = FondoInstitucional::find($id);
        $fondo->delete();
        $fondos = $this->getFondos($start);
        return $fondos;
    }
    
    function updateFondo()
    {
        $inputs             = Input::all();
        $fondo              = FondoInstitucional::find($inputs['idfondo']);
        $fondo->institucion = $inputs['institucion'];
        $fondo->repmed      = $inputs['repmed'];
        $fondo->supervisor  = $inputs['supervisor'];
        $fondo->total       = $inputs['total'];
        $fondo->cuenta      = $inputs['cuenta'];
        $fondo->idrm        = $inputs['codrepmed'];
        $fondo->save();
        $start = $inputs['start'];
        
        $fondos = $this->getFondos($start);
        return $fondos;
    }
    
    function getRepresentatives()
    {
        //$representatives = DB::table('FICPE.VISITADOR')
        //   ->select('visvisitador','vispaterno','vismaterno','visnombre','vislegajo')->where('VISACTIVO','S')->get();
        $representatives = DB::select("select visvisitador,vispaterno,vismaterno,visnombre,vislegajo from FICPE.VISITADOR where VISACTIVO = 'S' and LENGTH(VISLEGAJO) = 8 ");
        return json_encode($representatives);
    }
    
    function getCtaBanc($dni)
    {
        $cta = CtaRm::where('codbeneficiario', $dni)->where('tipo', 'H')->first();
        if ($cta) {
            return $cta->cuenta;
        } else {
            $cta = CtaRm::where('codbeneficiario', $dni)->where('tipo', 'B')->first();
            if ($cta)
                return $cta->cuenta;
            else
                return '---';
        }
        
    }
    function exportExcelFondos($start)
    {
        $data = $this->getFondos($start, 1);
        $dato = $data->toArray();
        $sum  = $data->sum('total');
        $mes  = array(
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'setiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre'
        );
        $st   = explode('-', $start);
        $mes  = $mes[str_pad($st[0], 2, '0', STR_PAD_LEFT)];
        $anio = $st[1];
        
        Excel::create('Fondo - '.str_pad($st[0], 2, '0', STR_PAD_LEFT).$anio , function($excel) use ($dato, $sum, $mes, $anio)
        {
            
            $excel->sheet($mes, function($sheet) use ($dato, $sum, $mes, $anio)
            {
                $sheet->mergeCells('A1:E1');
                $sheet->row(1, array(
                    'FONDO INSTITUCIONAL ' . strtoupper($mes) . ' - ' . $anio
                ));
                $sheet->row(1, function($row)
                {
                    $row->setAlignment('center');
                    $row->setFont(array(
                        'family' => 'Calibri',
                        'size' => '20',
                        'bold' => true
                    ));
                    $row->setBackground('#339246');
                    
                });
                $sheet->setHeight(1, 30);
                $count = count($dato) + 2;
                $sheet->setBorder('A1:E' . $count, 'thin');
                $sheet->setHeight(2, 20);
                $sheet->row(2, array(
                    'SISOL  - Hospital',
                    'Depositar a:',
                    'Nº Cuenta Bagó Bco. Crédito',
                    ' SUPERVISOR',
                    'Monto Real'
                ));
                $sheet->row(2, function($row)
                {
                    $row->setFont(array(
                        'family' => 'Calibri',
                        'size' => '15',
                        'bold' => true
                    ));
                    
                    $row->setBackground('#D2E718');
                    $row->setAlignment('center');
                });
                
                
                $sheet->fromArray($dato, null, 'A3', false, false);
                $sheet->row($count + 1, array(
                    '',
                    '',
                    '',
                    'Total:',
                    $sum
                ));
                $sheet->row($count + 1, function($row)
                {
                    $row->setAlignment('center');
                    $row->setFont(array(
                        'family' => 'Calibri',
                        'size' => '16',
                        'bold' => true
                    ));
                    
                });
                
            });
            
        })->download('xls');
        
    }
    function listFondosRep()
    {
        
        $fondos = FondoInstitucional::where('idrm', Auth::user()->rm->idrm)->where('depositado', 1)->get();
        return View::make('Dmkt.Rm.list_fondos_rm')->with('fondos', $fondos);
        
    }
    public function viewGenerateFondo($token)
    {
        $fondo   = FondoInstitucional::where('token', $token)->firstOrFail();
        $expense = Expense::where('idfondo', $fondo->idfondo)->get();
        $date    = $this->getDay();
        $data    = array(
            'fondo' => $fondo,
            'expense' => $expense,
            'date' => $date
        );
        return View::make('Dmkt.Cont.register_seat_fondo', $data);
    }
    function getLastDayOfMonth($month, $year)
    {
        return date('d', mktime(0, 0, 0, $month + 1, 1, $year) - 1);
    }

    private function periodo($date)
    {
        $periodo = explode('-', $date);
        return $periodo[1].str_pad($periodo[0], 2, '0', STR_PAD_LEFT);
    }
}