'use strict';

var admin = angular.module('admin', ['angularUtils.directives.dirPagination', 'ngDialog'])
    .controller('TabController', function () {
        this.tab = 'dashboard';

        this.selectTab = function (newTab) {
            this.tab = newTab;
        };

        this.isSelected = function(tabToCheck) {
            return this.tab === tabToCheck;
        };
    });

admin.config(['ngDialogProvider', function (ngDialogProvider) {
    ngDialogProvider.setDefaults({
        className: 'ngdialog-theme-default',
        plain: false,
        showClose: true,
        closeByDocument: true,
        closeByEscape: true
    });
}]);

var adminComic = angular.module('admin-comic', ['admin']);
var adminComments = angular.module('admin-comments', ['admin']);
var adminUsers = angular.module('admin-users', ['admin']);
