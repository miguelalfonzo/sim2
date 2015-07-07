<?php

class BagoUser extends Eloquent
{
    protected $table= 'D1J.USUARIO';
    protected $primaryKey = 'usucodusu';
    public $incrementing = false;


    function dni($usuario)
    {
        try
        {
            $rpta = array();
            $rpta['Status'] = 'Ok';
            $dni = BagoUser::where('usucodusu',strtoupper($usuario))->select('usutelefono')->first();
            if (empty($dni))
            {
                $rpta['Status'] = 'Warning';
                $rpta['Description'] = 'No se encontro el DNI del usuario en el Sistema'; 
            }                
            $rpta['Data'] = $dni->usutelefono;
        }
        catch (Exception $e)
        {
            $rpta['Status'] = 'Error';
            $rpta['Description'] = 'Error del Sistema';
            Log::error($e);
        }
        return $rpta;

    }

}