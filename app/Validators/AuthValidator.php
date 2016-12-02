<?php namespace Uxbert\Validators;

/**
 * Class AuthValidator
 * @package Uxbert\Validators
 */
class AuthValidator
{
    /*
     * Check if the input contains email and password
     * @param $input
     * @return array
     */
    public static function login($input)
	{
        //Store errors here
        $errors = [];
        
        //Email validation
        if(! isset($input['email']))
        {
            $errors['email'] = 'The email is required.';
        }
        //Password validation
        if(! isset($input['password']) || (isset($input['password']) and empty($input['password'])))
        {
            $errors['password'] = 'The password is required.';
        }
        return $errors;
	}

    /*
     * Check if the input contains email, name and password
     * @param $input
     * @return array
     */
    public static function register($input)
	{
        //Store errors here
        $errors = [];
        
        //Email validation
        if(! isset($input['email']) || (isset($input['email']) and (empty($input['email']) || ! filter_var($input['email'], FILTER_VALIDATE_EMAIL))))
        {
            $errors['email'] = 'The email is required.';
        }

        //Email validation
        if(! isset($input['name']) || (isset($input['name']) and empty($input['name'])))
        {
            $errors['name'] = 'The name is required.';
        }

        //Password validation
        if(! isset($input['password']) || (isset($input['password']) and empty($input['password'])))
        {
            $errors['password'] = 'The password is required.';
        }

        return $errors;
	}
}