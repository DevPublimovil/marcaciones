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
    Route::get('/markings-weekly/{id}', 'MyMarkingsController@showWeeklyDials')->name('markings.weekly');
    Route::get('/markings-month/{id}', 'MyMarkingsController@showMonthDials')->name('markings.month');
    Route::get('/percent/{id}', 'MyMarkingsController@showPercent')->name('markings.percent');
    Route::resource('/employees', 'EmployeeController');
    Route::get('/getemployees', 'EmployeeController@getEmployees')->name('employees.getall');
    Route::get('/getemployeesope', 'EmployeeController@getempOpe')->name('employees.ope');
    Route::get('/marcaciones/index', 'MarkingsController@index')->name('marcaciones.index');
    Route::get('/marcaciones-all', 'MarkingsController@getAllMarkings')->name('marcaciones.getall');
    Route::get('/marcaciones/horas/semanales/{id}', 'MarkingsController@calcHoursWeekly')->name('marcaciones.horassemanales');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
