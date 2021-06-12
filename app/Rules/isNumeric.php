<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class isNumeric implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $val) {

            $chk = array_filter($val, [$this, 'checkForNumber']);

            if (count($chk)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Checks if the param is an integer.
     * 
     * @param mixed $value
     * 
     * @return void|int
     */
    public function checkForNumber($value)
    {
        if (!(is_int($value))) {
            return $value;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
         return "The :attribute must only contain integers(whole numbers).";
    }
}
