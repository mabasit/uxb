<?php namespace Uxbert\Repositories;

use Uxbert\Models\User;

/**
 * Class UserRepository
 * @package Uxbert\Repositories
 */
class UserRepository
{
	/*
     * Find user by an email
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
	{
		return User::where('email', $email)->first();
	}

	/*
     * Create a user from the input
     * @param $input
     * @return static
     */
    public function createUser($input)
	{
		return User::create([
			'name'		=> $input['name'],
			'email'		=> $input['email'],
			'password'	=> bcrypt($input['password'])
		]);
	}
}