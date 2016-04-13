// MODULE
var app = angular.module('angularExchangeApp', ['ngRoute', 'ngResource', 'infinite-scroll', 'ngSanitize', 'textAngular', 'ngMaterial']);

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
        })

        .when('/user/:id', {
            templateUrl: 'frontend/pages/user.html',
            controller: 'userController'
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

app.filter('relativeDate', function () {
    return function (dateTimeString) {
        var previous = new Date(dateTimeString);
        var current = new Date();
        if (previous == 'Invalid Date')
            return dateTimeString;

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
            var monthNames = ["Jan'", "Feb'", "Mar'", "Apr'", "May", "Jun'",
                "Jul'", "Aug'", "Sep'", "Oct'", "Nov'", "Dec'"
            ];

            return monthNames[previous.getMonth()] + " " + previous.getDay();
        }
    }
});

app.filter('textToColor', function () {
    return function (v_string) {
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
});

//SERVICES & FACTORIES
app.factory('Reddit', function ($http) {
    var Reddit = function () {
        this.items = [];
        this.busy = false;
        this.after = '';
    };

    Reddit.prototype.nextPage = function () {
        if (this.busy) return;
        this.busy = true;

        var url = "https://api.reddit.com/hot?after=" + this.after + "&jsonp=JSON_CALLBACK";
        $http.jsonp(url).success(function (data) {
            var items = data.data.children;
            for (var i = 0; i < items.length; i++) {
                items[i].data['a123'] = 44545;
                this.items.push(items[i].data);
            }
            this.after = "t3_" + this.items[this.items.length - 1].id;
            this.busy = false;
        }.bind(this));
    };

    return Reddit;
});

app.factory('LoadPosts', ['$http', 'TopicFactory', function ($http, TopicFactory) {
    var LoadPosts = function (topic_id) {
        this.topic_id = topic_id;
        this.items = [];
        this.busy = false;
        this.stop = false;
        this.after = 0;
    };

    LoadPosts.prototype.nextPage = function () {
        if (this.busy) return;
        this.busy = true;

        var url = "json/q.php?type=topic&show_posts=1&id=" + this.topic_id + "&limit=10&offset=" + this.after;

        $http({method: 'GET', url: url}).success(function (data) {
            var items = data.posts;

            for (var i = 0; i < items.length; i++) {
                var topicService = new TopicFactory(items[i]);
                this.items.push(topicService);
            }
            this.after = this.items.length;

            this.busy = false;

            this.stop = items.length == 0;
        }.bind(this));
    };

    LoadPosts.prototype.addCustomPost = function (custom_post) {

        this.items.push(new TopicFactory({
            id: custom_post.id,
            topic: custom_post.topic,
            author: custom_post.author,
            content: custom_post.content,
            created: custom_post.created,
            modified: custom_post.modified,
            like_count: custom_post.like_count
        }));

        console.log(this.items);
    };

    return LoadPosts;
}]);

app.factory('TopicFactory', ['$sce', '$http', function ($sce, $http) {
    function TopicFactory(json_attr_array) {
        for (var json_attr in json_attr_array) {

            if (json_attr_array.hasOwnProperty(json_attr))
                this[json_attr] = json_attr_array[json_attr];
        }

        if (this.hasOwnProperty('author')) {
            var factory_scope = this;
            $http({
                method: 'GET',
                url: "json/q.php",
                params: {type: 'user', id: this.author}
            }).then(function (response) {
                factory_scope.author = response.data;
            }, function () {
            });
        }
    }

    TopicFactory.prototype.Foo = function () {
        return this.author;
    };

    return TopicFactory;
}]);

app.factory('LoadTopics', ['$sce', '$http', 'CategoryFactory', function ($sce, $http, CategoryFactory) {
    var LoadTopics = function (category_id) {
        this.category_id = category_id;
        this.items = [];
        this.busy = false;
        this.stop = false;
        this.after = 0;
    };

    LoadTopics.prototype.nextPage = function () {
        if (this.busy) return LoadTopics;
        this.busy = true;

        var url = "json/q.php?type=category&show_topics=1&show_posts=1&id=" + this.category_id + "&limit=20&offset=" + this.after;

        $http({method: 'GET', url: url}).success(function (data) {
            var items = data.topics;

            for (var i = 0; i < items.length; i++) {
                var categoryService = new CategoryFactory(items[i]);
                this.items.push(categoryService);
            }
            this.after = this.items.length;

            this.busy = false;
            this.stop = items.length == 0;
        }.bind(this));
    };

    return LoadTopics;
}]);

app.factory('CategoryFactory', ['$http', function ($http) {
    function CategoryFactory(json_attr_array) {
        for (var json_attr in json_attr_array) {
            if (json_attr_array.hasOwnProperty(json_attr))
                this[json_attr] = json_attr_array[json_attr];
        }
    }

    return CategoryFactory;
}]);

app.factory('ComposerSharedData', function () {
    var data = {
        show: false,
        target_topic: 0,
        target_category: 0,
        is_reply: true
    };

    return {
        isComposerShown: function () {
            return data.show;
        },
        setComposerShown: function (show_composer) {
            data.show = show_composer;
        },

        getTargetTopic: function () {
            return data.target_topic;
        },

        setTargetTopic: function (topic_id) {
            data.target_topic = topic_id;
        },

        getTargetCategory: function () {
            return data.target_category;
        },

        setTargetCategory: function (target_category) {
            data.target_category = target_category;
        },

        isReply: function () {
            return data.is_reply;
        },

        setReply: function (is_reply) {
            data.is_reply = is_reply;
        }
    }
});

//CONTROLLERS
app.controller('topicController', ['$scope', '$resource', '$routeParams', '$http', 'LoadPosts', 'ComposerSharedData', function ($scope, $resource, $routeParams, $http, LoadPosts, ComposerSharedData) {
    $scope.topicId = ($routeParams.id) ? $routeParams.id : 0;

    $scope.api = $resource("json/q.php");

    $scope.getAllData = function () {
        $http({
            method: 'GET',
            url: "json/q.php",
            params: {type: 'topic', show_posts: 1, id: $scope.topicId}
        }).then(function (topic_data) {
            $scope.topic = topic_data.data;

            $scope.topicActiveUsers = {};

            if ($scope.topic.original_poster) {
                $http({
                    url: "json/q.php",
                    params: {type: 'user', id: $scope.original_poster}
                }).then(function (response) {
                    $scope.topicActiveUsers['original_poster'] = response.data;
                }, function () {
                });
            }
            if ($scope.topic.frequent_poster_1) {
                $http({
                    url: "json/q.php",
                    params: {type: 'user', id: $scope.frequent_poster_1}
                }).then(function (response) {
                    $scope.topicActiveUsers['frequent_poster_1'] = response.data;
                }, function () {
                });
            }
            if ($scope.topic.frequent_poster_2) {
                $http({
                    url: "json/q.php",
                    params: {type: 'user', id: $scope.frequent_poster_2}
                }).then(function (response) {
                    $scope.topicActiveUsers['frequent_poster_2'] = response.data;
                }, function () {
                });
            }
            if ($scope.topic.most_recent_poster) {
                $http({
                    url: "json/q.php",
                    params: {type: 'user', id: $scope.most_recent_poster}
                }).then(function (response) {
                    $scope.topicActiveUsers['most_recent_poster'] = response.data;
                }, function () {
                });
            }

            $scope.category = $scope.api.get({
                type: 'category',
                id: topic_data.data.category
            });
        }, function () {
        });

        $scope.posts = new LoadPosts($scope.topicId);
    };

    $scope.reply = function () {
        ComposerSharedData.setTargetTopic($scope.topic.id);
        ComposerSharedData.setComposerShown(true);
        ComposerSharedData.setReply(true);
    }
}]);

app.controller('categoryController', ['$scope', '$resource', '$routeParams', 'LoadTopics', 'ComposerSharedData', function ($scope, $resource, $routeParams, LoadTopics, ComposerSharedData) {
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

    $scope.topics = new LoadTopics($scope.categoryId);

    $scope.newTopic = function () {
        ComposerSharedData.setTargetCategory($scope.categoryId);
        ComposerSharedData.setComposerShown(true);
        ComposerSharedData.setReply(false);
    }
}]);

app.controller('composerController', ['$scope', 'ComposerSharedData', '$http', 'LoadPosts', function ($scope, ComposerSharedData, $http, LoadPosts) {
    $scope.is_open = ComposerSharedData.isComposerShown();
    $scope.target_topic = {};
    $scope.categories = {};
    $scope.target_id = 0;
    $scope.is_reply = ComposerSharedData.isReply();
    $scope.title = "";
    $scope.message = "";

    $scope.submit = function () {
        if ($scope.is_reply) {
            $http({
                method: 'POST',
                url: 'json/q.php',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({
                    type: "post",
                    topic: $scope.target_id,
                    content: $scope.message
                })
            });
        }
        else {
            $http({
                method: 'POST',
                url: 'json/q.php',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({
                    type: "topic",
                    category: $scope.target_id,
                    title: $scope.title,
                    content: $scope.message
                })
            });
        }


        $scope.message = "";
        ComposerSharedData.setComposerShown(false);
    };

    $http({
        method: 'get',
        url: 'json/q.php',
        params: {type: 'category'}
    }).then(function(response) {
            $scope.categories = response.data.categories;
    }, function() {});

    $scope.$watch(function () {
        return ComposerSharedData.isComposerShown();
    }, function (newValue, oldValue) {
        if (newValue !== oldValue) $scope.is_open = newValue;
    });

    $scope.$watch(function () {
        return ComposerSharedData.getTargetTopic();
    }, function (newValue, oldValue) {

        if (newValue !== oldValue) {
            $scope.target_id = newValue;

            $http({
                method: 'GET',
                url: 'json/q.php',
                params: {type: 'topic', id: $scope.target_id}
            }).then(function (response) {
                $scope.target_topic = response.data;
            }, function () {
            });
        }
    });

    $scope.$watch(function () {
        return ComposerSharedData.getTargetCategory();
    }, function (newValue, oldValue) {
        if (newValue !== oldValue) {
            $scope.target_id = newValue;
        }
    });

    $scope.$watch(function () {
        return ComposerSharedData.isReply();
    }, function (newValue, oldValue) {
        if (newValue !== oldValue) {
            $scope.is_reply = newValue;
        }
    });
}]);

app.controller('userController', ['$scope', '$routeParams', '$http', function ($scope, $routeParams, $http) {
    $scope.user_id = ($routeParams.id) | 0;

    $http({
        method: 'GET',
        url: 'json/q.php',
        params: {
            type: 'user',
            id: $scope.user_id
        }
    }).then(function (response) {
        $scope.user = response.data;
        console.log(response.data);
    }, function () {
    });
}]);

// DIRECTIVES
app.directive('aeComposer', ['ComposerSharedData', function (ComposerSharedData) {
    return {
        templateUrl: 'frontend/directives/composer.html',
        replace: true,
        controller: 'composerController',
        scope: {},
        link: function (scope, element, attrs, controller) {
            element.find('#composer-close').bind('click', function () {
                scope.is_open = false;
                scope.$apply();
            });

            scope.$watch('is_open', function (newValue, oldValue) {
                if (newValue != oldValue) {
                    ComposerSharedData.setComposerShown(newValue);
                    if (newValue) {
                        element.addClass('open');
                        $('body').addClass('composer-padding');
                    }
                    else {
                        element.removeClass('open');
                        $('body').removeClass('composer-padding');
                    }
                }
            }, true);
        }
    }
}]);

app.directive('postDetail', function () {
    return {
        templateUrl: 'frontend/directives/post-detail.html',
        replace: false,
        scope: {
            item: '=',
            topicActiveUsers: '=',
            category: '=',
            firstItem: '='
        }
    }
});

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