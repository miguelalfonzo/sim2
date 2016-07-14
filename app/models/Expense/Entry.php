<?php

namespace Expense;

use \Eloquent;
use \stdClass;
use \Carbon\Carbon;

class Entry extends Eloquent
{
    protected $table= 'ASIENTO';
    protected $primaryKey = 'id';
    protected $dates = ['fec_origen'] ;
    
    public function nextId()
    {
        $lastId = Entry::orderBy( 'id' , 'DESC' )->first();
        if( is_null( $lastId ) )
        {
            return 1;
        }
        else
        {
            return $lastId->id + 1;
        }
    }

    protected function account()
    {
        return $this->hasOne( 'Dmkt\Account' , 'num_cuenta' , 'num_cuenta');
    }

    public function insertAdvanceEntry( stdClass $entry , $solicitud_id )
    {
        $this->id           = $this->nextId();
        $this->num_cuenta   = $entry->account_number;
        $this->fec_origen   = Carbon::createFromFormat( 'd/m/Y' , $entry->origin );
        $this->d_c          = $entry->d_c;
        $this->importe      = $entry->import;
        $this->leyenda      = $entry->caption;
        $this->id_solicitud = $solicitud_id;
        $this->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
        $this->save();
    }
}