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
        };

        $scope.users = {
            current: 1,
            perPage: 10,
            users: [],
            total: 0,
            state: "",
            pageChanged: function(newPage) {
                this.getResultsPage(newPage);
            },
            getResultsPage: function(pageNumber) {
                this.state = "loading";
                $http.get('/admin/user/widget/users?limit=' + $scope.users.perPage + '&page=' + pageNumber)
                    .then(function(response) {
                        $scope.users.state = "";
                        $scope.users.users = response.data.users;
                        $scope.users.total = response.data.total;
                    });
            }
        };

        $scope.users.getResultsPage(1);
    }]);
