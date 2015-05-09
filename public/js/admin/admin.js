var admin = angular.module('admin', ['angularUtils.directives.dirPagination'])
    .controller('TabController', function () {
        this.tab = 'dashboard';

        this.selectTab = function (newTab) {
            this.tab = newTab;
        };

        this.isSelected = function(tabToCheck) {
            return this.tab === tabToCheck;
        };
    });

var adminComic = angular.module('admin-comic', []);
var adminComments = angular.module('admin-comments', []);
var adminUsers = angular.module('admin-users', []);
