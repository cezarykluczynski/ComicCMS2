admin
    .controller('DashboardController', ['$scope', '$http', '$sce', function($scope, $http, $sce) {
        $scope.loadWidgetContents = function(widget) {
            if (!widget.loading) {
                $http.get(widget.url)
                    .success(function (response) {
                        widget.contents = response;
                    });
            }
            widget.loading = true;

            return $sce.trustAsHtml(widget.contents || "Loading...");
        }
    }]);
