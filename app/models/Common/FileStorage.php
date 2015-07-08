<?php

namespace Common;

use \Eloquent;

class FileStorage extends Eloquent{

    protected $table = 'FILE_STORAGE';
    protected $primaryKey = 'id';
    public $incrementing  = false;

}