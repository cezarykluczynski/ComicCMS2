"use strict";

admin
    .filter( "bold", function ( $sce ) {
        return function( text ) {
            return $sce.trustAsHtml( "<strong>" + text + "</strong>" );
        };
    });
