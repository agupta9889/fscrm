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


Route::get('/', 'AdminController@login');
Route::get('/clear', function() {
   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');
   return "Cleared!";
});

Route::group(['middleware' => 'auth'], function () {
   
   Route::get('/dashboard', 'AdminController@dashboard');
   Route::get('/adduser', 'AdminController@addRegistration');
   Route::post('/adduser', 'AdminController@registration');
   Route::get('/userlist', 'AdminController@userDetails');
   Route::get('/edituser/{id}', 'AdminController@updShowUser');
   Route::post('/updateuser', 'AdminController@updateUserRecord');
   Route::get('delete/{id}','AdminController@destroy');
   Route::post('/addrotator', 'AdminController@insertRotator');
   Route::post('/rotatoredit', 'AdminController@rotatorDataEdit');
   Route::post('/addphonesetting', 'AdminController@addPhone');
   Route::post('/rotatorlist/{id}', 'AdminController@editphone');
   Route::get('/deletephone/{id}', 'AdminController@deletePhoneRecord');
   Route::get('deleterotator/{id}','AdminController@deleteRotatorRecord');
   Route::get('/unexportedlead/{id}', 'AdminController@unexpLead');
   Route::get('/exportlead/{id}', 'AdminController@exportsLead');
   Route::get('/report/{id}', 'AdminController@leadReport');
   Route::get('/assignednumber', 'AdminController@assignedNumber');
   Route::get('/addintegration', 'AdminController@integration');
   Route::post('/insertintegaration', 'AdminController@addRegIntegration');
   Route::get('/integrationdoc', 'AdminController@integrationDoc');
   Route::post('/editintegrationdoc/{id}', 'AdminController@updateIntegrationDoc');
   Route::get('/deleteintegration/{id}', 'AdminController@deleteIntegrationUser');
   Route::post('/updateExportCount','AdminController@updateExportCount');
   Route::get('/sendmail','AdminController@sendmail');
   Route::post('/filterdate', 'AdminController@filterByDate');
   Route::resource('roles', RoleController::class);

   
});