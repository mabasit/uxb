angular.module('uxbert')
	.factory('AuthFactory', AuthFactory);

AuthFactory.$inject = ['$http'];

//Factory to interact with authentication apis
function AuthFactory ($http) {
	return {
		login: function(data) {
			return $http.post('/api/auth/login', data);
		},
		register: function(data) {
			return $http.post('/api/auth/register', data);
		},
		logout: function(token) {
			return $http.post('/api/auth/logout?token=' + token);
		}
	}
};