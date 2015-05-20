"use strict";

admin
    .controller("TabController", function () {
        this.tab = "dashboard";

        this.selectTab = function ( newTab ) {
            this.tab = newTab;
        };

        this.isSelected = function ( tabToCheck ) {
            return this.tab === tabToCheck;
        };
    });
