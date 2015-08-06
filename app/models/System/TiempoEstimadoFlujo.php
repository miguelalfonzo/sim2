<?php

namespace System;

use \Eloquent;

class TiempoEstimadoFlujo extends Eloquent{

    protected $table = 'TIEMPO_ESTIMADO_FLUJO';
    protected $primaryKey = 'id';

    public function StatusId(){
        return $this->hasOne( 'Common\State' , 'id' , 'status_id' );
    }

    protected function toUserType()
    {
        return $this->hasOne( 'Common\TypeUser' , 'codigo' , 'to_user_type' );
    }
}