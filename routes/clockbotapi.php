<?php
 use Illuminate\Support\Facades\Route;

 Route::get('json/apiactions/pending', 'Resources\ActionsJsonController@showActionsPendings')->name('apiactions.pending');
 Route::get('json/apiactions/approved', 'Resources\ActionsJsonController@showActionsApproved')->name('apiactions.approved');
 Route::get('json/apiactions/notapproved', 'Resources\ActionsJsonController@showActionsNotApproved')->name('apiactions.notapproved');