<?php

namespace Expense;

use \Eloquent;

class ProofType extends Eloquent{
	protected $table = 'DMKT_RG_TIPO_COMPROBANTE';
	protected $primaryKey = 'idcomprobante';
}