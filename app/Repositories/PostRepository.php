<?php namespace Uxbert\Repositories;

use Uxbert\Models\Post;

/**
 * Class PostRepository
 * @package Uxbert\Repositories
 */
class PostRepository
{
	/*
     * Find all posts based on the input
     * @param $input
     * @return array
     */
    public function getAll($input)
	{
		//Default search options for getting the posts
		$sort_by 	= 'created_at';
		$sort_as 	= 'DESC';
		$paginate 	= env('PAGINATE', 10);
		$page 		= 0;

		//Check for sort (by column)
    	if(
    		isset($input['sort_by']) and 
    		!empty($input['sort_by']) and 
    		in_array($input['sort_by'], ['title', 'created_at'])
		)
    		$sort_by = $input['sort_by'];

    	//Check for sort (as ASC/DESC)
    	if(isset($input['sort_as']) and ! empty($input['sort_as']))
    		$sort_as = $input['sort_as'];

    	//FOR PAGINATION
		if(isset($input['paginate']) and ! empty(isset($input['paginate'])))
			$paginate = $input['paginate'];

		//FOR PAGE NUMBER
		if(isset($input['page']) and ! empty(isset($input['page'])))
			$page = ($input['page'] - 1);

		$offset = $page * $paginate;

		$posts = \DB::select("select slug, title, summary, p.created_at as created, u.name as user_name, u.id as user_id from posts p INNER JOIN users u ON p.user_id = u.id ORDER BY p.{$sort_by} {$sort_as} LIMIT {$paginate} OFFSET {$offset}");

		//Total number of posts
		$total = \DB::select("select count(*) as count from posts");
		$total = $total[0]->count;

		//Create a pagination array
		$pagination = [
			'current_page' 	=> $page + 1,
			'per_page' 		=> $paginate,
			'total'	  		=> $total,
			'last_page'  	=> ceil($total/$paginate)			
		];
		return ['posts' => $posts, 'paginate' => $pagination];		
	}

	/*
     * Create a new post from the input data
     * @param $data
     * @return static
     */
    public function createPost($data)
	{
		return Post::create([
			'title'		=> $data['title'],
			'slug'		=> str_slug($data['title'], '-') . '-' . time(),
			'summary'	=> $data['summary'],
			'body'		=> $data['body'],
			'user_id'	=> \Auth::user()->id
		]);
	}

	/*
     * Find post by slug
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findBySlug($slug)
	{
		return Post::with('user')->where('slug', $slug)->first();
	}

	/*
     * Update the post
     * @param $post
     * @param $data
     * @return mixed
     */
    public function updatePost($post, $data)
	{
		if(isset($data['title'])) $post->title = $data['title'];
		
		if(isset($data['body'])) 
		{
			$post->body = $data['body'];

			//If user has not entered summary, create it out of body
			if(! isset($data['summary']))
			{
	        	$data['summary'] = substr($data['body'], 0, 100);
			}
        	else
        	{
	            (strlen($data['summary']) > 255) ? substr($data['summary'], 0, 255) : '';
        	}
		}

		$post->save();

		return $post;
	}
}