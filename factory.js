angular.module('apiFactory', [])
  .factory('apiFactory', function($http) {

    var factory = {};
    
    factory.getPlayer = function($scope, name) {
      $http.get('/uthgard/uthapi.php?player='+name)
          .success(function(response) {
             $scope.player = response;
             $scope.getPlayerError = null;         
        })
          .error(function(response) {
          $scope.getPlayerFeedError = "Unable to get Player Feed!";
        });
    };
    
    factory.getHistory = function($scope, name){
      $http.get('/uthgard/uthapi.php?history='+name)
          .success(function(response) {           
             $scope.history = response;
             $scope.getHistoryError = null;
             renderChart($scope.history);         
        })
          .error(function(response) {
          $scope.getHistoryError = "Unable to get History Feed!";
        });
    };
    
    factory.getTopRvR = function($scope) {
      $http.get('/uthgard/uthapi.php?top=rvr')
          .success(function(response) {
             $scope.topRvR = response;         
        })
          .error(function(response) {
          $scope.getTopError = "Unable to get Top RvR Feed!";
        });
    };
    
    factory.getTopPvE = function($scope) {
      $http.get('/uthgard/uthapi.php?top=pve')
          .success(function(response) {
             $scope.topPvE = response;         
        })
          .error(function(response) {
          $scope.getTopError = "Unable to get Top RvR Feed!";
        });
    };
    
    
 
    return factory;
  });
