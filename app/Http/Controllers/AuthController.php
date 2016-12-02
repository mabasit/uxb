<?php namespace Uxbert\Http\Controllers;

use Auth;
use \Illuminate\Http\Request;
use Uxbert\Repositories\UserRepository;
use Uxbert\Validators\AuthValidator as Validator;

/**
 * Class AuthController
 * @package Uxbert\Http\Controllers
 */
class AuthController extends Controller{

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
	{
		$this->repository 	= $repository;
	}

    /**
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
       //Grab the input
        $input = $request->input();
        
        //Validate input
        $errors = Validator::register($input);

        //if errors, return
        if(count($errors)) return $this->setStatusCode(422)->respond($errors);

        //Try to find the user based on its user_id
        $user = $this->repository->findByEmail($input['email']);

        //If the user does not exist, return 
        if($user) return $this->respondNotFound('The user with this email is already registered.');

        //Create a user
        $user = $this->repository->createUser($input);

        //Create token
        $token = \JWTAuth::attempt(['email' => $input['email'], 'password' => $input['password'] ]);

        //Respond
        return $this->setStatusCode(201)->respond([
            'user' => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email
                ],
            'token' => $token,
            'success' => true
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
	{
		//Grab the input
		$input = $request->input();
        
        //Validate Input
        $errors = Validator::login($input);

        //if errors, return
        if(count($errors)) return $this->setStatusCode(422)->respond($errors);

		//Try to find the user based on its user_id
		$user = $this->repository->findByEmail($input['email']);

		//If the user does not exist, return 
		if(! $user) return $this->respondNotFound('The user with this email is not registered.');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = \JWTAuth::attempt(['email' => $input['email'], 'password' => $input['password'] ])) 
        	{
                return response()->json(
                	[
	                	'error'   => true,
			            'type'    => 'credentials',
			            'message' => 'Invalid credentials.'
	                ], 
                401);
            }
        } catch (\JWTException $e) 
        {
            // something went wrong whilst attempting to encode the token
            return response()->json(
            	[
                	'error'   => true,
		            'type'    => 'server',
		            'message' => 'Could not create token. Try again.'
                ]
            , 500);
        }

        $user = Auth::user();
    	//Respond
        return response()->json([
        	'user' => [
                    'id'        => $user->id,
					'name'		=> $user->name,
					'email'		=> $user->email
				],
			'token' => $token,
			'success' => true
		], 200);
	}

    /**
     * @return mixed
     */
    public function logout()
	{
        //If user is not authenticated, return
		if(! Auth::check()) return $this->respond('You have been logged out.');

		Auth::logout();

        //Return
        return $this->respond('You have been logged out successfully.');
	}
}