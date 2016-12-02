var app = angular.module('uxbert', ['ui.router']);

app.run(['$rootScope', 'Storage', '$state', function($rootScope, Storage, $state) {

	// Logout
	// Remove the token and user from the local storage
	// And redirect user to home if the current view 
	// needs user to be authenticated
	$rootScope.logout = function() {
		$rootScope.user = null;
		//Broadcast an event along with token
		//AuthController will listen to the evet and call the api
		$rootScope.$broadcast('loggedOut', Storage.get('token'));

		Storage.remove('user');
		Storage.remove('token');

		var currentState = $state.current.name;
		if(currentState == 'posts_create' || currentState == 'posts_edit'){
			$state.go('home');
		}

		if(! $rootScope.$apply) {
			$rootScope.$apply();
		}
	}
	
	$rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
		$rootScope.user = userDetails();
		//If is not authenticated
		if (! isAuthenticated()) {
			//User tries to go to the view that needs authentication
			if(toState.name == 'posts_edit' || toState.name == 'posts_create')
			{
				toastr.info("You need to be logged in to view this page.");
				return $state.go('login');
			}
		}
		//If user is already logged in and tries to go to login or reigster
		//redirect user to home.
		if (isAuthenticated() && (toState.name == 'register' || toState.name == 'login') ) {
			return $state.go('home');
		}
	});

	//As app bootstraps
	
	//get the user from localstorage and save it on $rootScope so that it can be used in frontend
	$rootScope.user = userDetails();

	//if user is not authenticated, go to login	
	if (!isAuthenticated() || ! $rootScope.user || $rootScope.user == null || $rootScope.user == undefined) {
		return $state.go('login');
	}

	//Check if user is logged in by verifing if the token exists
	function isAuthenticated() {
		var token = Storage.get('token');
		if(token) return true;
		return false;
	}

	//Get the user stored in local storage
	function userDetails() {
		var user = Storage.get('user');
		if(! user) return null;
		return JSON.parse(user);
	}
}]);

