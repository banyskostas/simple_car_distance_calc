/**
 * Services
 */
angular.module('mainApp.services', [
    'dualmultiselect',
    'ui.bootstrap.datetimepicker',
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
]);

