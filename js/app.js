var Forum = angular.module('Forum',[
	'ui.bootstrap', 
	'ngRoute',
	'userController',
	'navController',
	'threadController',
	'ngCookies'
]);

Forum.config(['$routeProvider', '$locationProvider', function ($routeProvider,$locationProvider){
	$routeProvider.
	when('/home', {
		templateUrl: 'partials/home.html'
	}).
	when('/thread/:threadID', {
		templateUrl: 'partials/thread.html',
		controller: 'thread',
		controllerAs : 'aThread'
	}).
	when('/login', {
		templateUrl: 'partials/login.html'
	}).
	otherwise({
		redirectTo: '/home'
	});
}]);

Forum.run(['$cookies', 'User','$rootScope', function ($cookies, User, $rootScope){
	User.login('', '', function(result){
		console.log(result);
	});
	console.log($cookies);
}]);