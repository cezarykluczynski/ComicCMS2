/**
 * Simplified version of directive found on Stack Overflow.
 *
 * @see http://stackoverflow.com/a/14837021/3807342
 */
admin.directive( "focusOnce", function($timeout, $parse) {
    return {
        link: function( scope, element, attrs ) {
        var model = $parse( attrs.focusOnce );
            scope.$watch( model, function( value ) {
                if( value === true ) {
                    $timeout( function() {
                        element[ 0 ].focus();
                    });
                }
            });
        }
    };
});