var adminSignIn = angular.module('admin-signin', [])
    .config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }])
    .controller('SigninController', ['$scope', '$http', '$window', function($scope, $http, $window) {
        $scope.submit = function() {
            if (!$scope.signin.$valid || !$scope.user) {
                return;
            }

            $scope.signin.error = false;
            $scope.pending = true;

            $http({
                    url: "",
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: $.param($scope.user)
                })
                .success(function(response) {
                    $scope.signin.success = true;
                    $window.location.href = response.url;
                })
                .error(function(response, status) {
                    $scope.pending = false;
                    $scope.signin.error = true;
                });
        };
    }]);

var admin = angular.module('admin', [])
    .controller('TabController', function (){
        this.tab = 'comic';

        this.selectTab = function (newTab){
            this.tab = newTab;
        };

        this.isSelected = function(tabToCheck) {
            return this.tab === tabToCheck;
        };
    });

var adminComic = angular.module('admin-comic', []);
var adminComments = angular.module('admin-comments', []);
var adminUsers = angular.module('admin-users', []);
