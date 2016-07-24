<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('calculators.carDistanceCalculator');
});

/**
 * BEGIN AJAX
 */

Route::get('/getCars', [
    'as' => 'get_cars', 'uses' => 'CarsController@getCarsList'
]);

Route::post('/calcCarsTotalDistance/{dateFrom}/{dateTo}', [
    'as' => 'calc_cars_total_distance', 'uses' => 'CarsController@calcCarsTotalDistance'
]);

/**
 * END AJAX
 */
