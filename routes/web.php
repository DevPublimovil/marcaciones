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
    Route::post('/iclock/upload', 'AdminController@uploadData')->name('admin.upload');
    Route::get('/iclock', 'AdminController@index')->name('admin.index');
});

Route::group(['middleware' => ['auth']], function(){
    Route::resource('/actions', 'ActionController');
    Route::resource('/timestables', 'TimestableController');
    Route::post('/timestables/change', 'TimestableController@changeTimesEmployee')->name('timestable.change');
    Route::put('/actions/noapproved/{action}', 'ActionController@noApproved')->name('actions.noapproved');
    Route::put('/actions/approved/{action}', 'ActionController@approved')->name('actions.approved');
    Route::get('/apiactions/employee/{empoyee}', 'Resources\ActionsJsonController@show')->name('apiactions.show');
    Route::get('/apiactions/{type}', 'Resources\ActionsJsonController@showActions')->name('apiactions.showactions');
    Route::get('/markings-weekly/{id}', 'MyMarkingsController@showWeeklyDials')->name('markings.weekly');
    Route::get('/markings/period/{id}', 'MyMarkingsController@showPeriod')->name('markings.period');
    Route::get('/percent/{id}', 'MyMarkingsController@showPercent')->name('markings.percent');
    Route::resource('/employees', 'EmployeeController');
    Route::put('/employees/avatar/{employee}', 'EmployeeController@changeAvatar')->name('employees.avatar');
    Route::get('/employees/firm/{employee}', 'EmployeeController@editFirm')->name('employees.editfirm');
    Route::put('/employees/firm/{employee}', 'EmployeeController@updateFirm')->name('employees.updatefirm');
    Route::get('/employees/markings/{employee}', 'EmployeeController@markings')->name('employees.markings');
    Route::get('/apiemployees', 'Resources\EmployeeJsonController@index')->name('apiemployees.index');
    Route::get('/apiemployees/show', 'Resources\EmployeeJsonController@showEmployees')->name('apiemployees.show');
    Route::put('/apiemployees/markings/{id}', 'Resources\EmployeeJsonController@markings')->name('apiemployees.markings');
    Route::get('/marcaciones/index', 'MarkingsController@index')->name('marcaciones.index');
    Route::get('/marcaciones-all', 'MarkingsController@getAllMarkings')->name('marcaciones.getall');
    Route::get('/marcaciones/horas/semanales/{id}', 'MarkingsController@calcHoursWeekly')->name('marcaciones.horassemanales');
    Route::post('/reports/create', 'ReportsController@createAll')->name('reports.createall');
    Route::get('/reports', 'ReportsController@index')->name('reports.index');
    Route::get('/reports/create/{employee}', 'ReportsController@create')->name('reports.create');
    Route::get('/apiassists/show/{id}', 'Resources\ReportsJsonController@showAssistsDetails')->name('apiassists.show');
    Route::get('/apitime', 'Resources\TimestableJsonController@index')->name('apitime.index');
    Route::get('/myaccount', 'MyAccountController@index')->name('myaccount.index');
    Route::put('/myaccount/{user}', 'MyAccountController@update')->name('myaccount.update');
    Route::get('/myaccount/employee', 'MyAccountController@show')->name('myaccount.show');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/home/company/', 'HomeController@selectCompany')->name('home.company');
});



Auth::routes();

