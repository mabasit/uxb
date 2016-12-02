angular.module('uxbert')
	.service('DataService', DataService);

//Will help share the data between controllers
function DataService() {
	var service = this;
	return service;
}