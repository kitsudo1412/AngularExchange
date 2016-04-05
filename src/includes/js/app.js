// MODULE
var app = angular.module('angularExchangeApp', ['ngRoute', 'ngResource', 'infinite-scroll']);

// ROUTES
app.config(function ($routeProvider, $locationProvider) {

    $routeProvider

        .when('/', {
            templateUrl: 'frontend/pages/category.html',
            controller: 'categoryController'
        })

        .when('/topic/:id/:slug?', {
            templateUrl: 'frontend/pages/topic.html',
            controller: 'topicController'
        })

        .when('/category/:id/:slug?', {
            templateUrl: 'frontend/pages/category.html',
            controller: 'categoryController'
        });

    $locationProvider.html5Mode(true);
});

// FILTERS
app.filter('spaceless', function () {
    return function (input) {
        if (input) {
            return input.replace(/\s+/g, '-');
        }
    }
});

app.filter('underscoreRemove', function () {
    return function (input) {
        if (input) {
            return input.replace('_', ' ');
        }
    }
});

app.filter('capitalize', function () {
    return function (input) {
        if (input) {
            return input.replace(/(?:^|\s)\S/g, function (a) {
                return a.toUpperCase();
            });
        }
    }
});

app.filter('firstChar', function () {
    return function (input) {
        if (input) {
            return input.charAt(0);
        }
    }
});

//SERVICES & FACTORIES
app.factory('Reddit', function($http) {
    var Reddit = function() {
        this.items = [];
        this.busy = false;
        this.after = '';
    };

    Reddit.prototype.nextPage = function() {
        if (this.busy) return;
        this.busy = true;

        var url = "https://api.reddit.com/hot?after=" + this.after + "&jsonp=JSON_CALLBACK";
        $http.jsonp(url).success(function(data) {
            var items = data.data.children;
            for (var i = 0; i < items.length; i++) {
                this.items.push(items[i].data);
            }
            this.after = "t3_" + this.items[this.items.length - 1].id;
            this.busy = false;
        }.bind(this));
    };

    return Reddit;
});

app.factory('LoadPosts', ['$http', function($http){
    var LoadPosts = function(topic_id) {
        this.topic_id = topic_id;
        this.items = [];
        this.busy = false;
        this.after = 0;
    };

    LoadPosts.prototype.nextPage = function() {
        if (this.busy) return;
        this.busy = true;

        var url = "json/q.php?type=topic&show_posts=1&id=" + this.topic_id + "&limit=3&offset=" + this.after;

        $http({method: 'GET', url: url}).success(function(data) {
            var items = data.posts;

            for (var i = 0; i < items.length; i++) {
                this.items.push(items[i]);
                console.log(items[i]);
            }
            this.after = this.items.length - 1;
            this.busy = false;
        }.bind(this));
    };

    return LoadPosts;
}]);

//CONTROLLERS
app.controller('topicController', ['$scope', '$resource', '$routeParams', '$http', 'LoadPosts', function ($scope, $resource, $routeParams, $http, LoadPosts) {
    $scope.topicId = ($routeParams.id) ? $routeParams.id : 0;

    $scope.api = $resource("json/q.php");

    $scope.getAllData = function() {
        $http({method: 'GET', url: "json/q.php", params: {type: 'topic', show_posts: 1, id: $scope.topicId} }).then(function (topic_data) {
            $scope.topic = topic_data.data;

            $scope.category = $scope.api.get({
                type: 'category',
                id: topic_data.data.category
            });
        }, function() {});

        $scope.posts = new LoadPosts($scope.topicId);
    }
}]);

app.controller('categoryController', ['$scope', '$resource', '$routeParams', function ($scope, $resource, $routeParams) {
    $scope.categoryId = ($routeParams.id) ? $routeParams.id : 0;

    $scope.categoryAPI = $resource("json/q.php");

    $scope.categoryJSON = $scope.categoryAPI.get({
        type: 'category',
        id: $scope.categoryId,
        show_topics: 1,
        show_posts: 1
    });

    $scope.categoryList = $scope.categoryAPI.get({
        type: 'category'
    });
}]);

// DIRECTIVES
app.directive('categoryTopicItem', function () {
    return {
        templateUrl: 'frontend/directives/category-topic-item.html',
        replace: true,
        scope: {
            topic: '=',
            current: '=',
            getActivityDisplayDate: '&'
        },
        controller: ['$scope', '$resource', '$timeout', function ($scope, $resource, $timeout) {
            $scope.api = $resource("json/q.php");

            $scope.categoryId = $scope.topic.category;
            $scope.category = $scope.api.get({
                type: 'category',
                id: $scope.categoryId
            });

            $scope.userJSON = {};
            $scope.activeUsers = {};


            $scope.getUserJSON = function (user_id) {
                if (user_id == null)
                    return null;
                if ($scope.userJSON['user' + user_id] == null) {
                    $scope.userJSON['user' + user_id] = $scope.api.get({
                        type: 'user',
                        id: user_id
                    });
                }
                return $scope.userJSON['user' + user_id];
            };

            $scope.getActiveUsers = function (original_poster, frequent_poster_1, frequent_poster_2, most_recent_poster) {
                if (original_poster != null) $scope.activeUsers['original_poster'] = $scope.getUserJSON(original_poster);
                if (frequent_poster_1 != null) $scope.activeUsers['frequent_poster_1'] = $scope.getUserJSON(frequent_poster_1);
                if (frequent_poster_2 != null) $scope.activeUsers['frequent_poster_2'] = $scope.getUserJSON(frequent_poster_2);
                if (most_recent_poster != null) $scope.activeUsers['most_recent_poster'] = $scope.getUserJSON(most_recent_poster);
            };

            $scope.getColorFromString = function (v_string) {
                if (v_string == null || v_string.length == 0)
                    return "#000000";
                var correspond_num = parseInt(v_string);
                if (isNaN(correspond_num)) correspond_num = v_string.charCodeAt(0);

                correspond_num = parseInt(correspond_num * correspond_num * correspond_num * 8985362) % 16777216;
                correspond_num = correspond_num.toString(16);
                while (correspond_num.length < 6)
                    correspond_num += correspond_num;
                correspond_num = correspond_num.substr(0, 6);
                return '#' + correspond_num;
            };

            $scope.getActivityDisplayDate = function (current) {
                var previous = new Date($scope.lastActivity);
                current = new Date();
                if (previous == 'Invalid Date')
                    return $scope.lastActivity;

                var msPerMinute = 60 * 1000;
                var msPerHour = msPerMinute * 60;
                var msPerDay = msPerHour * 24;
                var msPerMonth = msPerDay * 30;

                var elapsed = current - previous;

                if (elapsed < msPerMinute) {
                    return '<1m';
                }

                else if (elapsed < msPerHour) {
                    return Math.round(elapsed / msPerMinute) + 'm';
                }

                else if (elapsed < msPerDay) {
                    return Math.round(elapsed / msPerHour) + 'h';
                }

                else if (elapsed < msPerMonth) {
                    return Math.round(elapsed / msPerDay) + 'd';
                }

                else {
                    return $scope.lastActivity;
                }
            };

            $scope.getActiveUsers($scope.topic.original_poster, $scope.topic.frequent_poster_1, $scope.topic.frequent_poster_2, $scope.topic.most_recent_poster);

            if ($scope.topic.posts.length > 0) {
                $scope.lastActivity = $scope.topic.posts[$scope.topic.posts.length - 1]['modified'];
            }
            else
                $scope.lastActivity = "Never";


            $scope.update = function () {
                $scope.current = new Date();
                $timeout($scope.update, 60000);
            };

            $scope.update();
        }],
        link: function (scope, elements, attrs, controller) {
        }
    }
});

$(document).ready(function () {
    longPollingMessages();
});

function longPollingMessages() {
    $.ajax({
        url: 'polling.php',
        dataType: 'json',
        success: function (data) {

        }
    }).always(function () {
        longPollingMessages();
    });
}