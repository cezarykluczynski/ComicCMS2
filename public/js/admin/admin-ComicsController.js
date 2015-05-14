'use strict';

admin
    .controller('ComicsController', ['$scope', '$rootScope', 'ngDialog', function($scope, $rootScope, ngDialog) {
        $rootScope.$on( "openComicCreateDialog", function () {
            $scope.openComicCreateDialog();
        });

        $scope.openComicCreateDialog = function () {
            var dialog = ngDialog.open({
                template: 'admin-comics-new',
                controller: 'ComicsPopupController'
            });
        }
    }])
    .controller('ComicsPopupController', ['$scope', '$http', function ($scope, $http) {
        this.get = $http.get('/admin/comics/create')
            .then(function () {
                console.log('get');
            });
    }]);