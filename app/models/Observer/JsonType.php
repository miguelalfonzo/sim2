<?php

namespace Observer;

use \Eloquent;

class JsonType 
{
    public function loading(Eloquent $model)
    {
        use \Eloquent\Dialect\Json;
    }

}