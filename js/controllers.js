var userController = angular.module("userController",['ui.bootstrap', 'ngCookies']);

userController.controller('user', ['User', '$scope', '$rootScope', 
	function user(User, $scope, $rootScope){

	user.prototype.login = function() {
		User.login($scope.userName, $scope.password, function (result) {
			if(result.success){
				$rootScope.user = result.data;
			}else{
				$rootScope.user = null;
			}
		});
	};

	user.prototype.popUp = function() {
		User.popUp();
	};
}]);

var navController = angular.module('navController', []);

navController.controller('nav', ['$scope', '$location', function nav($scope, $location){
	$scope.isActive = function (viewLocation) { 
        return viewLocation === $location.path();
    };
}]);

var threadController = angular.module('threadController', ['ui.bootstrap', 'ngCookies']);

threadController.controller('thread', [
	'$scope',
	'$location',
	'$routeParams',
	'$http',
	'$route',
	'$rootScope',
	'$cookies',
	'User',
	'Thread',
	function thread($scope, $location, $routeParams, $http, $route, $rootScope, $cookies, User, Thread) {
		
		thread.prototype.getThread = function() {
			Thread.getThread(function(result){
				if(result.found){
					$scope.thread = result.data;
					$scope.found = result.found;
					$scope.thread.answer = false;
					$scope.thread.postTx = "Respond";
					angular.forEach($scope.thread.posts, function(post, key){
						post.isCollapsed = false;
						post.respond = false;
						post.comment = "Comment";
					});
				}else{
					$scope.thread = {};
					$scope.found = result.found;
					$scope.thread.content = "Unable to find thread";
				}
			});
		};
		thread.prototype.createComment = function(post){
			if($rootScope.user === null || $rootScope.user === undefined){
				User.popUp();
				return;
			}
			current = post.respond;
			post.isCollapsed = false;
			$scope.thread.answer = false;
			$scope.thread.postTx = "Respond";
			angular.forEach($scope.thread.posts, function(aPost, key){
				aPost.respond = false;
				aPost.comment = "Comment";
			});
			post.respond = !current;
			if(post.respond){
				post.comment = "Cancel";
			}else{
				post.comment = "Comment";
			}
		};
		thread.prototype.createPost = function(thread){
			if($rootScope.user === null || $rootScope.user === undefined){
				User.popUp();
				return;
			}
			angular.forEach($scope.thread.posts, function(aPost, key){
				aPost.respond = false;
				aPost.comment = "Comment";
			});
			thread.answer = !thread.answer;
			if(thread.answer){
				thread.postTx = "Cancel";
			}else{
				thread.postTx = "Respond";
			}
		};
		thread.prototype.submitComment = function(thread, postID, response, respond){
			Thread.submitComment(respond, response, postID, thread,function(result){
				alert(result.msg);
			});
		};
		thread.prototype.submitPost = function(thread, answer, respond){
			Thread.submitPost(thread, answer, respond, function(result){
				alert(result.msg);
			});
		};

		thread.prototype.collapse = function(post){
			post.respond = false;
			post.isCollapsed = !post.isCollapsed;
			post.comment = "Comment";
		};

		this.getThread($scope, $location, $routeParams, $http);
}]);
