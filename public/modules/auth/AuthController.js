angular.module('uxbert').controller('AuthController', AuthController);

AuthController.$inject = ['Storage', '$state', '$rootScope', 'AuthFactory'];

function AuthController (Storage, $state, $rootScope, AuthFactory) {
	var vm = this;

	vm.errors = {
		email: false,
		password: false
	}

	vm.login = function(data) {
		
		if(! handleLoginValidation(data)) return;

		vm.loading = true;

		AuthFactory.login(data)
			.success(function(data) {
				vm.loading = false;
				saveData(data);
				return $state.go('home');
			})
			.error(function(error) {
				vm.loading = false;
				toastr.error(error.message);
				return false;
			});
	}

	vm.register = function(data) {
		
		if(! handleRegisterValidation(data)) return;

		vm.loading = true;

		AuthFactory.register(data)
		.success(function(data) {
			vm.loading = false;
			if(data.success){
				saveData(data.data);
				return $state.go('home');
			}

			//Email already registered
			toastr.warn(data.message);
			if(data.data && data.data.email) vm.errors.message = data.data.email;
			return false;
		})
		.error(function(error) {
			vm.loading = false;
			toastr.error(error.message);
			return false;
		});
	}

	//Save data in the localStorage
	function saveData(data) {
		var token = data.token,
			user = data.user;

		Storage.set('token', token);
		Storage.set('user', JSON.stringify(user));

		if(! $rootScope.$apply) {
			$rootScope.$apply();
		}
	}

	function handleLoginValidation(data) {
		vm.errors.email = false;
		vm.errors.password = false;
		vm.errors.message = false;
		if(! data) {
			vm.errors.email = true;
			vm.errors.password = true;
			return false;
		}
		if(! data.email) {
			vm.errors.email = true;
			return false;
		}
		if(! data.password) {
			vm.errors.password = true;
			return false;
		}
		return true;
	}

	function handleRegisterValidation(data) {
		vm.errors.name = false;
		vm.errors.email = false;
		vm.errors.password = false;
		vm.errors.message = false;
		if(! data) {
			vm.errors.name = true;
			vm.errors.email = true;
			vm.errors.password = true;
			return false;
		}
		if(! data.name) {
			vm.errors.name = true;
			return false;
		}
		if(! data.email) {
			vm.errors.email = true;
			return false;
		}
		if(! data.password) {
			vm.errors.password = true;
			return false;
		}
		return true;
	}

	//Listen for the logout event and call the api
	$rootScope.$on('loggedOut', function(e, token) {
		return AuthFactory.logout(token).success(function(data) {}).error(function(data) {});
	})
} 