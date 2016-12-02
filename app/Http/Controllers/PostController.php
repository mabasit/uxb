<?php namespace Uxbert\Http\Controllers;

use Illuminate\Http\Request;
use Uxbert\Repositories\PostRepository;
use Uxbert\Validators\PostValidator as Validator;

/**
 * Class PostController
 * @package Uxbert\Http\Controllers
 */
class PostController extends Controller
{
    /**
     * @var PostRepository
     */
    protected $repository;

    /**
     * @param PostRepository $repository
     */
    public function __construct(PostRepository $repository)
	{
		$this->repository 	= $repository;
	}

    /**
     * Show all posts based on the request
     */
    public function index(Request $request)
    {
    	//Grab the input
    	$input = $request->all();

        //Get the posts from repository
    	$posts = $this->repository->getAll($input);

        //Return posts
    	return $this->respond($posts);
    }

    /**
     * Create a new post
     */
    public function store(Request $request)
    {
		//Grab the input
		$input = $request->all();

        //Validate input
		$errors = Validator::create($input);
        
        //If errors, return
        if(count($errors)) return $this->setStatusCode(422)->respond($errors);

        //if user has not entered the summary, add it manually
        if(! isset($input['summary']))
        {
            $input['summary'] = substr($input['body'], 0, 100);
        }
        else
        {
            (strlen($input['summary']) > 255) ? substr($input['summary'], 0, 255) : '';
        }
        
        //Create post
        $post = $this->repository->createPost($input);
        //Get post, with user
    	$post = $this->repository->findBySlug($post->slug);
        //Transform it
        $post = $this->transformPost($post);

        return $this->setStatusCode(201)->respond($post);
    }

    /**
     * Show a post based on the $slug
     */
    public function show($slug)
    {
        //Try to find the post
        $post = $this->repository->findBySlug($slug);

        //if no post, return
    	if(! $post) return $this->respondNotFound('The post does not exist');

        //Transform the post
    	$post = $this->transformPost($post);

        //return post
    	return $this->respond($post);
    }

    /**
     * Update the post
     */
    public function update(Request $request, $slug)
    {
        //Grab input from request
    	$input = $request->all();

        //Find the post
    	$post = $this->repository->findBySlug($slug);

        //If no post is found, return
    	if(! $post) return $this->respondNotFound('The post does not exist');

        //Check if post belongs to logged in user. If not, return
    	if(\Auth::user()->id != $post->user_id) return $this->respondRestricted();

        //Update the post
    	$post = $this->repository->updatePost($post, $input);

    	$post = [
    		'title'	=> $post->title,
    		'slug'	=> $post->slug,
    		'body'	=> $post->body,
    		'user'	=> [
    			'id'	=> $post->user->id,
    			'name'	=> $post->user->name
    		]
    	];

    	return $this->respond($post);
    }

    /**
     * Delete the post based on slug
     */
    public function destroy($slug)
    {
        //Get the post from the repo
		$post = $this->repository->findBySlug($slug);

        //If none, return
    	if(! $post) return $this->respondNotFound('The post does not exist');

        //Check if post belongs to the logged in user. If not, return
    	if(Auth::user()->id != $post->user_id) return $this->respondRestricted();

        //Delete the post
    	$post->delete();
        
        //Return
    	return $this->respond('The post has been deleted successfully.');
    }

    /**
     * Helper function, which helps to transform
     * the post, before sending it back.
     */
    private function transformPost($post) 
    {
        return [
            'title'     => $post->title,
            'slug'      => $post->slug,
            'body'      => $post->body,
            'summary'   => $post->summary,
            'created'   => $post->created_at->diffForHumans(),
            'user'      => [
                'id'    => $post->user->id,
                'name'  => $post->user->name
            ]
        ];
    }
}
