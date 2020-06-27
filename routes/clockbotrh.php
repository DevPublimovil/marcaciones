<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth']], function () {
    Route::get('rh/actions', 'Rh\PersonalActionRhController@index')->name('rh.actions.index');
    
});