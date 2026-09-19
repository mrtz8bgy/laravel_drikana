<?php
/*
|--------------------------------------------------------------------------
| Update Routes
|--------------------------------------------------------------------------
|
| This route is responsible for handling the intallation process
|
|
|
*/
Route::get('/', 'UpdateController@step0');
Route::get('/step1', 'UpdateController@step1')->name('update.step1');
Route::get('/step2', 'UpdateController@step2')->name('update.step2');
