<?php

namespace Common;

use \Eloquent;

class FileStorage extends Eloquent{

    protected $table = 'SIM_FILE_STORAGE';
    protected $primaryKey = 'id';

}