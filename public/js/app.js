var app = angular.module('app', ['chart.js']);

app.config(['$interpolateProvider', function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{');
    $interpolateProvider.endSymbol('}]}');
}]);

app.controller('VoteController', ['$http', '$scope', 'ChartJs', function($http, $scope, ChartJs) {

    $scope.total = 0;
    $scope.results = {};
    $scope.chartLabels = [];
    $scope.chartData = [];

    // Load current statistics
    $http.get('/vote/statistics').then(function(result) {

        $scope.total = result.data.total;
        $scope.results = result.data.results;
        $scope.chartLabels = result.data.chart.labels;
        $scope.chartData = result.data.chart.data;

    }, function(error) {

    });

    // Vote function
    $scope.vote = function(id) {
        $http.get('/vote/' + id).then(function(result) {

            $scope.total = result.data.total;
            $scope.results = result.data.results;
            $scope.chartLabels = result.data.chart.labels;
            $scope.chartData = result.data.chart.data;

        }, function(error) {

        });
    };

}]);