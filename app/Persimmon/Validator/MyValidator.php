<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/11
 * Time: 11:26
 */

namespace Persimmon\Validator;


class MyValidator
{

    public function validateFlag($attribute, $value, $parameters, $validator)
    {
        return preg_match("/^[a-zA-Z0-9_-]+$/",$value);
    }

}