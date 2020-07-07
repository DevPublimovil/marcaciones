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
    
    return redirect()->route('login');
})->name('inicio');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::post('/iclock/upload', 'AdminController@uploadData')->name('admin.upload');
    Route::get('/iclock', 'AdminController@index')->name('admin.index');
});

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function () {

    //Rutas para el actualizar información del usuario
    Route::get('/myaccount', 'MyAccountController@index')->name('myaccount.index');
    Route::put('/myaccount/{user}', 'MyAccountController@update')->name('myaccount.update');
    Route::get('/myaccount/employee', 'MyAccountController@show')->name('myaccount.show');
    Route::get('/employees/firm/{employee}', 'EmployeeController@editFirm')->name('employees.editfirm');
    Route::put('/employees/firm/{employee}', 'EmployeeController@updateFirm')->name('employees.updatefirm');

    //rutas para acciones de personal
    Route::resource('/actions', 'ActionController');
    Route::put('/actions/noapproved/{action}', 'ActionController@noApproved')->name('actions.noapproved');
    Route::put('/actions/approved/{action}', 'ActionController@approved')->name('actions.approved');
    Route::get('/create/action/employee/{employee}', 'ActionController@createForEmployee')->name('actions.create.employee');

    //rutas para horarios
    Route::resource('/timestables', 'TimestableController'); 
    Route::post('/timestables/change', 'TimestableController@changeTimesEmployee')->name('timestable.change'); //ruta para cambiar empleados y horarios

    Route::resource('/employees', 'EmployeeController'); //rutas para administracion de empleados








    Route::get('/apiemployees', 'Resources\EmployeeJsonController@index')->name('apiemployees.index');
    Route::get('/apiemployees/show', 'Resources\EmployeeJsonController@showEmployees')->name('apiemployees.show');

    Route::get('/reports/create', 'ReportsController@createAll')->name('reports.createall');
    Route::get('/reports', 'ReportsController@index')->name('reports.index');
    Route::post('/reports/notification', 'ReportsController@sendNotification')->name('reports.notification');

    Route::post('/home/company/', 'HomeController@selectCompany')->name('home.company');
    Route::get('/inicio/clockbot', 'HomeController@homeclockbot')->name('home.clockbot');
    Route::get('/myactions', 'ActionController@myactions')->name('actions.myactions');
    Route::get('constancia/salarial', 'ReportsController@constancia')->name('solicitar.constancia');
    Route::get('action/create/date', 'ActionController@createWithDate')->name('actions.create.date');


    Route::get('/marcaciones/employees', 'MarkingsController@showEmployees')->name('marcaciones.show');
});