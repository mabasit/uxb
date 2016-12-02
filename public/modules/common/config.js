var app = angular.module('uxbert');

//Add authInterceptor to http interceptors
app.config(['$httpProvider', function($httpProvider) {
	$httpProvider.interceptors.push('authInterceptor');
}]);

//authInterceptor will be responsible for adding the token to the 
//every request for authorization
app.factory('authInterceptor', ['$q', '$location', 'Storage', 
	function($q, $location, Storage) {
		return {
			request: function(config) {
				// Add authorization token to headers
				config.headers = config.headers || {};
				var token = Storage.get('token');
				if (token) {
					config.headers.Authorization = 'Bearer ' + token;
				}
				return config;
			},
			// Intercept 401s and redirect you to login
			responseError: function(response) {
				if (response.status === 401) {
					Storage.remove('token');
					Storage.remove('user');
					$location.path('/login');
					return $q.reject(response);
				} else {
					return $q.reject(response);
				}
			}
		}
}]);