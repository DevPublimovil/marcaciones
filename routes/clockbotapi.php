<?php
 use Illuminate\Support\Facades\Route;

Route::get('json/apiactions/pending', 'Resources\ActionsJsonController@showActionsPendings')->name('apiactions.pending');
Route::get('json/apiactions/approved', 'Resources\ActionsJsonController@showActionsApproved')->name('apiactions.approved');
Route::get('json/apiactions/notapproved', 'Resources\ActionsJsonController@showActionsNotApproved')->name('apiactions.notapproved');
Route::get('/apiactions/employee/{empoyee}', 'Resources\ActionsJsonController@show')->name('apiactions.show');
Route::get('/apitime', 'Resources\TimestableJsonController@index')->name('apitime.index');
Route::get('/markings-weekly/{id}', 'MyMarkingsController@showWeeklyDials')->name('markings.weekly');
Route::get('/markings/period/{id}', 'MyMarkingsController@showPeriod')->name('markings.period');
Route::get('/percent/{id}', 'MyMarkingsController@showPercent')->name('markings.percent');
Route::get('/apiassists/show/{id}', 'Resources\ReportsJsonController@showAssistsDetails')->name('apiassists.show');
Route::put('rh/actions/approve/{action}', 'Rh\PersonalActionRhController@approve')->name('rh.actions.approve');
Route::put('rh/actions/notapprove/{action}', 'Rh\PersonalActionRhController@notApprove')->name('rh.actions.notapprove');
