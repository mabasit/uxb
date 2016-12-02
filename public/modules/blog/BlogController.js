angular.module('uxbert').controller('BlogController', BlogController);

BlogController.$inject = ['$state', 'DataService', 'BlogFactory'];

function BlogController ($state, DataService, BlogFactory) {
	
	var vm = this;

	//Default options for getting data
	vm.sort_label 	= 'Latest';
	vm.sort_by 		= 'created_at';
	vm.sort_as 		= 'desc';
	vm.page    		= 1;
	vm.total 		= 10;

	//Allowed sorts
	vm.sorts = [
		{sort_as: 'desc', 	sort_by: 'created_at', label: 'Latest'},
		{sort_as: 'asc', 	sort_by: 'created_at', label: 'Oldest'}
	];

	//Change the current page
	vm.changePage = function(page) {
		vm.page = page;
		return vm.index();
	}

	//Change the current total (total posts to be displayed)
	vm.changeTotal = function(total) {
		vm.total = total;
		return vm.index();
	}

	//Change the current sort
	vm.changeSort = function(sort) {
		vm.page 		= 1;
		vm.sort_by 		= sort.sort_by;
		vm.sort_as 		= sort.sort_as;
		vm.sort_label 	= sort.label;
		return vm.index();
	}

	//Get the posts
	vm.index = function() {

		vm.loading = true;

		var params = {
			sort_as: vm.sort_as,
			sort_by: vm.sort_by,
			page: vm.page,
			paginate: vm.total
		}

		BlogFactory.index(params)
			.success(function(data) {
				vm.loading 	= false;
				vm.paginate = data.data.paginate;
				vm.posts 	= data.data.posts;
			})
			.error(function(error) {
				vm.loading = false;
				toastr.error("Error loading the posts");
			});
	}

	//Show the post
	vm.show = function() {

		//If post exists in Service, get it from there 
		//(When post is created or updated, store in DataService and save an api call)
		if(DataService.post) {
			vm.post = DataService.post;
			return;
		}

		var id = $state.params.id;
		vm.loading = true;

		BlogFactory.show(id)
			.success(function(data) {
				vm.loading 	= false;
				vm.post 	= data.data;
			})
			.error(function(error) {
				vm.loading = false;
				toastr.error("Error loading the post");
				$state.go("home");
			});
	}

	//Create a new post
	vm.create = function(data) {
		//validation
		vm.errors = {};
		if(! data) {
			vm.errors.title = true;
			vm.errors.body = true;
			return false;
		}
		if(! data.title) {
			vm.errors.title = true;
			return false;
		}
		if(data.summary && data.summary.length > 256) {
			vm.errors.summary = true;
			return false;
		}
		if(! data.body) {
			vm.errors.body = true;
			return false;
		}

		vm.loading = true;

		BlogFactory.create(data)
			.success(function(data) {
				vm.loading = false;
				var post = data.data;
				//Save post
				DataService.post = post;
				//Notify
				toastr.success("The post was added successfully.");
				//Show post
				return $state.go('posts_show', {id: post.slug});				
			})
			.error(function(error) {
				vm.loading = false;
				if(error.message)
					toastr.error(error.message);
				else if(error.error)
					toastr.error(error.error);
				else toastr.error("An error occured adding the post");
				return false;
			});
	}

	//Update an existing post
	vm.update = function() {

		vm.errors = {};

		if(! vm.post) {
			vm.errors.title = true;
			vm.errors.body = true;
			return false;
		}
		if(! vm.post.title) {
			vm.errors.title = true;
			return false;
		}
		if(vm.post.summary && vm.post.summary.length > 256) {
			vm.errors.summary = true;
			return false;
		}
		if(! vm.post.body) {
			vm.errors.body = true;
			return false;
		}

		vm.loading = true;

		BlogFactory.update(vm.post.slug, vm.post)
			.success(function(data) {
				vm.loading = false;
				var post = data.data;
				//Save post
				DataService.post = post;
				//Notify
				toastr.success("The post was updated successfully.");
				//Show post
				return $state.go('posts_show', {id: post.slug});				
			})
			.error(function(error) {
				vm.loading = false;
				if(error.message)
					toastr.error(error.message);
				else if(error.error)
					toastr.error(error.error);
				else toastr.error("An error occured adding the post");
				return false;
			});
	}
}