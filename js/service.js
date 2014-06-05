Forum.factory('User', [
	'$http', 
	'$modal', 
	'$cookies', 
	'$rootScope', 
	function($http, $modal, $cookies, $rootScope){
		var functions = {};
		functions.login = function(userName, password, callback){
			$http.post("php/getUser.php", {"userName": userName, 'password' : password})
			.success(function(result){
				console.log(result);
				callback(result);
			});
		};

		functions.popUp = function(){
			var modalInstance = $modal.open({
				templateUrl: 'partials/login.html'
			});
		};

		functions.logout = function(){

		};
		
		return functions;
}]);

Forum.factory('Thread',[
	'$http',
	'$route',
	'$rootScope', 
	'$location', 
	'$routeParams', 
	'User', 
	function($http, $route, $rootScope, $location, $routeParams, User){
		var functions = {};

		functions.getThread = function(callback){
			$http.post("php/getThread.php",{'threadID': $routeParams.threadID})
			.success( function ( result ){
				console.log(result);
				if(result.success){
					result.found = true;
					callback(result);
				}else{
					result.found = false;
					callback(result);
				}
			});
		};

		functions.submitComment = function(respond, response, postID, thread, callback){
			if(respond && response != undefined && response != ""){
				params = {'threadID': thread.threadID, 'postID': postID, 'authorID': thread.authorID, 'content': response};
				$http.post("php/createComment.php", params)
				.success( function (result){
					if(result.success){
						callback({'success': true, 'msg': 'Comment successfully saved.'});
					}else{
						callback({'success':false, 'msg': 'Failed to save comment'});
					}
					$route.reload();
				}).
				error( function (result){
					callback({'success':false, 'msg': 'Failed to save comment'});
				});
			}else{
				callback({'success':false, 'msg': 'Please input your comment.'});
			}
		};

		functions.submitPost = function(thread, answer, respond, callback){
			if(respond && answer != undefined && answer != ""){
				params = {'threadID': thread.threadID, 'authorID': thread.authorID, 'content': answer};
				$http.post("php/createPost.php", params)
				.success( function (result){
					if(result.success){
						callback({'success': true, 'msg': 'response successfully saved.'});
					}else{
						callback({'success':false, 'msg': 'Failed to save response'});
						console.log(result);
					}
					$route.reload();
				}).
				error( function (result){
					callback({'success':false, 'msg': 'Failed to save response'});
				});
			}else{
				callback({'success':false, 'msg': 'Please input your response'});
			}
		};

		return functions;
}]);