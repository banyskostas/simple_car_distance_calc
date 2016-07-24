/**
 * Services
 */
angular.module('mainApp.services', [
    'dualmultiselect',
    'apiService',
    'helperService',
]);

/**
 * Controllers
 */
angular.module('mainApp.controllers', [
    'carDistanceCalculatorController'
]);

/**
 * Main App
 */
angular.module('mainApp', [
    'mainApp.services',
    'mainApp.controllers',
])
.run(function() {
    //...

})
.config(function() {
    //...
});


