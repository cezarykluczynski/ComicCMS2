"use strict";

admin.config(["$translateProvider", function ($translateProvider) {
    $translateProvider.translations("en", {
        "cannotChangeComicEntityEditInProgress": "Active comic cannot be changed when strip is being edited. " +
            "Save or discard changes first.",
        "cannotChangeStripEntityEditInProgress": "Active strip cannot be changed when other strip is being edited. " +
            "Save or discard changes first.",
        "cannotChangePageEntityEditInProgress": "Page cannot be change when strip is being edited. " +
            "Save or discard changes first.",
    });

    $translateProvider.preferredLanguage( "en" );
    $translateProvider.useSanitizeValueStrategy( "escape" );
}]);