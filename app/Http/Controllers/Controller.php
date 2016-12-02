<?php

namespace Uxbert\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use \Response;

/**
 * Class Controller
 * @package Uxbert\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var int
     */
    protected $status_code = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param $status_code
     * @return $this
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
        return $this;
    }
    
    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Requested resource was not found.')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondRestricted($message = 'The request did not have valid authorization credentials')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondWithError($message = 'An error occurred')
    {
        $data = [
            'error'         => true, 
            'status_code'   => $this->getStatusCode(),
            'message'       => $message
        ];

        return Response::json($data, $this->getStatusCode());
    }

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        return Response::json([
				'success' 		=> true,
				'status_code' 	=> $this->getStatusCode(),
				'data'			=> $data        	
        	], $this->getStatusCode(), $headers);
    }
}
