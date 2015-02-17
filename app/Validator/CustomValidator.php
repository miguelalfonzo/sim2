<?php

namespace Validator;

class CustomValidator extends Illuminate\Validation\Validator 
{

    public function validateEach($attribute, $value, $parameters)
    {
       
        $ruleName = array_shift($parameters);
        $rule = $ruleName.(count($parameters) > 0 ? ':'.implode(',', $parameters) : '');

        foreach ($value as $arrayKey => $arrayValue)
        {
            $this->validate($attribute.'.'.$arrayKey, $rule);
        }

       
        return true;
    }
}