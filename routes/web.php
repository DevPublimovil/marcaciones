<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('home');
    }
    else
    {
        return redirect()->route('login');
    }
})->name('inicio');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(['middleware' => ['auth']], function(){
    Route::resource('/actions', 'ActionController');
    Route::get('/actions/noapproved/{action}', 'ActionController@noApproved')->name('actions.noapproved');
    Route::get('/apiactions/{type}', 'Resources\ActionsJsonController@showActions')->name('apiactions.show');
    Route::get('/markings-weekly/{id}', 'MyMarkingsController@showWeeklyDials')->name('markings.weekly');
    Route::get('/markings-month/{id}', 'MyMarkingsController@showMonthDials')->name('markings.month');
    Route::get('/percent/{id}', 'MyMarkingsController@showPercent')->name('markings.percent');
    Route::resource('/employees', 'EmployeeController');
    Route::get('/employees/markings/{employee}', 'EmployeeController@markings')->name('employees.markings');
    Route::get('/apiemployees', 'Resources\EmployeeJsonController@index')->name('apiemployees.index');
    Route::get('/apiemployees/show/{employee}', 'Resources\EmployeeJsonController@showEmployees')->name('apiemployees.show');
    Route::put('/apiemployees/markings/{id}', 'Resources\EmployeeJsonController@markings')->name('apiemployees.markings');
    Route::get('/marcaciones/index', 'MarkingsController@index')->name('marcaciones.index');
    Route::get('/marcaciones-all', 'MarkingsController@getAllMarkings')->name('marcaciones.getall');
    Route::get('/marcaciones/horas/semanales/{id}', 'MarkingsController@calcHoursWeekly')->name('marcaciones.horassemanales');
    Route::get('/reports', 'ReportsController@index')->name('reports.index');
    Route::get('/reports/create/{employee}', 'ReportsController@create')->name('reports.create');
    Route::get('/apiassists/show/{id}', 'Resources\ReportsJsonController@showAssistsDetails')->name('apiassists.show');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
