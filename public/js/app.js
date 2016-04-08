var app = angular.module('app', ['chart.js']);

app.config(['$interpolateProvider', function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{');
    $interpolateProvider.endSymbol('}]}');
}]);

app.controller('VoteController', ['$http', '$scope', '$timeout', 'ChartJs', function($http, $scope, $timeout, ChartJs) {

    $scope.total = 0;
    $scope.results = {};
    $scope.chartLabels = [];
    $scope.chartData = [];
    $scope.state = 'vote';

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
        $http.get('/vote/' + id + ($scope.email !== '' ? '/' + $scope.email : '')).then(function(result) {

            $scope.state = 'statistics';

            $scope.total = result.data.total;
            $scope.results = result.data.results;
            $scope.chartLabels = result.data.chart.labels;
            $scope.chartData = result.data.chart.data;

            $scope.email = '';

            $timeout(function() {
                $scope.state = 'vote';
            }, 8000);

        }, function(error) {

        });
    };

}]);