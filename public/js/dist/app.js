var app=angular.module("app",["chart.js"]);app.config(["$interpolateProvider",function(t){t.startSymbol("{[{"),t.endSymbol("}]}")}]),app.controller("VoteController",["$http","$scope","$timeout","ChartJs",function(t,a,e,o){a.total=0,a.results={},a.chartLabels=[],a.chartData=[],a.state="vote",t.get("/vote/statistics").then(function(t){a.total=t.data.total,a.results=t.data.results,a.chartLabels=t.data.chart.labels,a.chartData=t.data.chart.data},function(t){}),a.vote=function(o){t.get("/vote/"+o+(""!==a.email?"/"+a.email:"")).then(function(t){a.state="statistics",a.total=t.data.total,a.results=t.data.results,a.chartLabels=t.data.chart.labels,a.chartData=t.data.chart.data,a.email="",e(function(){a.state="vote"},8e3)},function(t){})}}]);