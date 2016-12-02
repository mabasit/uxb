<?php namespace Uxbert\Validators;

/**
 * Class PostValidator
 * @package Uxbert\Validators
 */
class PostValidator
{
    /*
     * Check if the input contains title and body
     * @param $input
     * @return array
     */
    public static function create($input)
    {
        //Store errors here
        $errors = [];
        
        //Title validation
        if(! isset($input['title']) || (isset($input['title']) and empty($input['title'])))
        {
            $errors['title'] = 'The title is required.';
        }
        //Body validation
        if(! isset($input['body']) || (isset($input['body']) and empty($input['body'])))
        {
            $errors['body'] = 'The body is required.';
        }

        return $errors;
    }
}