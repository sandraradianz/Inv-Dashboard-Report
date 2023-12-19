<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('day_report/download/{dept}/{org_id}/{to_date}/{from_date}/{filter}','UserController@downloadDayReport');
Route::get('month_report/download/{dept}/{org_id}/{to_date}/{from_date}/{filter}','UserController@downloadMonthReport');
Route::get('year_report/download/{dept}/{org_id}/{to_date}/{from_date}/{filter}','UserController@downloadYearReport');
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login')->name("login");
    Route::post('register', 'AuthController@register');
	Route::post('send_recovery_email', 'UserController@sendRecoveryEmail');
	Route::post('check_token', 'UserController@checkResetToken');
	Route::post('password/reset', 'UserController@resetUserPassword');
});
Route::group([
      'middleware' => 'auth:api'
], function() {
	Route::get('inv_dashboard/report', 'InvReportController@InvReport');	
});
	
