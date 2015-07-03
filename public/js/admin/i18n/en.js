"use strict";

admin.config(["$translateProvider", function ($translateProvider) {
    $translateProvider.translations("en", {
        "cannotChangeComicEntityEditInProgress": "Active comic cannot be change if entity is edited. " +
            "Save or discard changes first.",
        "cannotChangeStripEntityEditInProgress": "Active strip cannot be change if other strip is edited. " +
            "Save or discard changes first.",
    });

    $translateProvider.preferredLanguage( "en" );
    $translateProvider.useSanitizeValueStrategy( "escape" );
}]);