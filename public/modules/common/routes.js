angular.module('uxbert')

.config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
    function($stateProvider, $urlRouterProvider, $locationProvider)
{
    $locationProvider.html5Mode(true);

    // For any unmatched url, redirect to /state1
    $urlRouterProvider.otherwise('/');
    // Now set up the states

    $stateProvider

    //Homepage
    .state('home', {
        url: "/",
        templateUrl: "/modules/blog/index.html"
    })
    //New post
    .state('posts_create', {
        url: "/posts/create",
        templateUrl: "/modules/blog/create.html"
    })
    //show post
    .state('posts_show', {
        url: "/posts/:id",
        templateUrl: "/modules/blog/show.html"
    })
    //edit post
    .state('posts_edit', {
        url: "/posts/:id/edit",
        templateUrl: "/modules/blog/edit.html"
    })
    
    //Login
    .state('login', {
        url: "/login",
        templateUrl: "/modules/auth/login.html"
    })
    //Register
    .state('register', {
        url: "/register",
        templateUrl: "/modules/auth/register.html"
    })

}]);