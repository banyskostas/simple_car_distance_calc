var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass([
        'app.scss',
        'carDistanceCalculator.scss',
        '../../../bower_components/angular/angular-csp.css',
        '../../../bower_components/bootstrap/dist/css/bootstrap.css',
        '../../../bower_components/angular-dual-multiselect-directive/dualmultiselect.css',
        '../../../bower_components/font-awesome/css/font-awesome.css',
        '../../../bower_components/bootstrap-daterangepicker/daterangepicker.css',
        '../../../bower_components/angular-bootstrap-datetimepicker/src/css/datetimepicker.css',
    ], 'public/assets/css');
});

elixir(function(mix) {
    mix.scripts([
        '../../../bower_components/angular/angular.js',
        '../../../bower_components/jquery/dist/jquery.js',
        '../../../bower_components/bootstrap/dist/js/bootstrap.js',
        '../../../bower_components/angular-dual-multiselect-directive/dualmultiselect.js',
        '../../../bower_components/moment/moment.js',
        '../../../bower_components/bootstrap-daterangepicker/daterangepicker.js',
        '../../../bower_components/angular-daterangepicker/js/angular-daterangepicker.js',
        '../../../bower_components/angular-bootstrap-datetimepicker/src/js/datetimepicker.js',
        '../../../bower_components/angular-bootstrap-datetimepicker/src/js/datetimepicker.templates.js',
        'angular/controllers/**',
        'angular/services/**',
        'angular/app.js',
        'main.js',
    ], 'public/js/main.js');
});

elixir(function(mix) {
    mix.copy('bower_components/font-awesome/fonts', 'public/assets/fonts');
    mix.copy('bower_components/glyphicons/fonts', 'public/assets/fonts');
});