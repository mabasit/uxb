angular.module('uxbert')
	.service('Storage', Storage);

//Service that interacts with the brower's localstorage
function Storage($window) {
	var service = this;

	//Get the item from localStorage
	service.get = function(key) {
		return $window.localStorage.getItem(key);
	}

	//Save the item in localStorage
	service.set = function(key, value) {
		return $window.localStorage.setItem(key, value);
	}

	//Remove the item from localStorage
	service.remove = function(key) {
		return $window.localStorage.removeItem(key);
	}

	return service;
}