var app = angular.module('mainApp', ['apiFactory'])
  .controller('mainCtrl', function($scope, $http, apiFactory) {

    $scope.player = [];
    $scope.playerRaw = [];
    $scope.uthgardURI = 'https://uthgard.org/herald/api';

    $scope.createWorker = function() {
      
      var getPlayer = function(name) {
        apiFactory.getPlayer($scope, name);
        apiFactory.getHistory($scope, name);
        
      };
      
      var getHistory = function(name) {
        apiFactory.getHistory($scope, name);
      };
      
      var getTopRvR = function() {
        apiFactory.getTopRvR($scope);
      };
      
      var getTopPvE = function() {
        apiFactory.getTopPvE($scope);
      };

      return {
        getPlayer: getPlayer,
        getHistory: getHistory,
        getTopRvR: getTopRvR,
        getTopPvE: getTopPvE
      };
    };

    $scope.worker = $scope.createWorker();

  }).filter("secsFromEpoch", function(){
     return function (x) {
        var d = new Date(0);
        d.setUTCSeconds(x);
        return d;
    };
  }).filter("formatRealmRank", function(){
     return function(x) {
        //Realm Ranks are stored total levels. We want to display as levels.
        //Realm rank 1 is 10. Realm Rank 1 level 1 is 11 and so forth.  
        // Realm Rank 1 Level 1 should be displayed 1L1 not 11.
        var val = round(x/10, 1).toString();       
        val = val.replace('.', 'L');
        if(!val.includes('L')){
          //Realm Rank is flat value (1, 2, 3 etc, so dispaly 1L0, or 2L0)
          val = val + 'L0';
        }
        
        return val;
        
     };
  }); 
  
function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + " " + strTime;
} 

function round(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}
 
  
function renderChart(data){
        var name = '';
        var label = [];
        var experience = [];
        var realmPoints = [];      
        for (var i = 0; i < data.length; i++) {
            experience.push(data[i].experience);
            realmPoints.push(data[i].realm_points);
            var date = new Date(0);
            date.setUTCSeconds(data[i].last_update);
            label.push(formatDate(date));
            name = data[i].name;
        }

        var ctx = document.getElementById("expChart");
        var xpChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: 'Experience Over Last 7 Updates for '+name,
                    data: experience,
                    backgroundColor:'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255,99,132,1)',
                    borderWidth: 1
                }]
            },
            options: {
                      global: {
                        responsive: true,
                        maintainAspectRatio: false
                      }
                    }
        });
        
        var ctx2 = document.getElementById("rpChart");
        var rpChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: 'Realm Points Over Last 7 Updates for '+name,
                    data: realmPoints,
                    backgroundColor:'rgba(255, 99, 132, 0.2)',
                    borderColor:'rgba(255,99,132,1)',
                    borderWidth: 1
                }]
            },
            options: {
                      global: {
                        responsive: true,
                        maintainAspectRatio: false
                      }
                    }
        });
 }