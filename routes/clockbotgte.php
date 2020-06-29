<?php

use Illuminate\Support\Facades\Route;

//rutas para gerentes
Route::get('gte/actions', 'Gte\PersonalActionGteController@index')->name('gte.actions.index');
Route::get('gte/actions/approved' , 'Gte\PersonalActionGteController@showApproved')->name('gte.actions.approved');
Route::get('gte/actions/notapproved' , 'Gte\PersonalActionGteController@showNotApproved')->name('gte.actions.notapproved');
Route::get('gte/actions/pendings' , 'Gte\PersonalActionGteController@showPendings')->name('gte.actions.pendings');
Route::put('gte/actions/approve/{action}', 'Gte\PersonalActionGteController@approveAction')->name('gte.aprove.actions');
Route::put('gte/actions/notapprove/{action}', 'Gte\PersonalActionGteController@notApproveAction')->name('gte.notaprove.actions');
Route::get('gte/actions/create', 'Gte\PersonalActionGteController@create')->name('gte.actions.create');