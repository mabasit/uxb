angular.module('uxbert')
	.factory('BlogFactory', BlogFactory);

BlogFactory.$inject = ['$http'];

//Factory that interacts with blog endpoints
function BlogFactory ($http) {
	return {
		//Get blog posts
		index: function(params) {
			return $http.get('/api/posts', {params})
		},
		//Get blog post
		show: function(id) {
			return $http.get('/api/posts/' + id);
		},
		//Create blog post
		create: function(data) {
			return $http.post('/api/posts', data);
		},
		//Update blog post
		update: function(id, data) {
			return $http.put('/api/posts/' + id , data)
		}
	}
};