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
Route::get('/login', 'AdminController@login');
Route::post('/login', 'AdminController@userPostLogin');
Route::get('/forgot', 'AdminController@forgotPassword');
//Route::post('forgot', 'AdminController@submitForgetPassword'); 
Route::get('/resetpassword', 'AdminController@showResetPasswordForm'); 
Route::post('resetpassword', 'AdminController@submitResetPasswordForm'); 

Route::group(['middleware' => 'userAuth'], function () {
   Route::get('/dashboard', 'AdminController@dashboard');
   Route::get('/logout', 'AdminController@doLogout');
   Route::post('/adduser', 'AdminController@registration');
   Route::get('/adduser', 'AdminController@addRegistration');
   Route::get('/userlist', 'AdminController@userDetails');
   Route::get('/edituser/{id}', 'AdminController@updShowUser');
   Route::post('/edituser/{id}', 'AdminController@editUser');
   Route::get('delete/{id}','AdminController@destroy');
   Route::get('/addrotator', 'AdminController@addRotator');
   Route::post('/addrotator', 'AdminController@insertRotator');
   Route::get('/rotatorlist', 'AdminController@rotatorDetails');
   Route::post('/rotatorlist', 'AdminController@addPhone');
   Route::get('deleterotator/{id}','AdminController@deleteRotatorRecord');
   //Route::post('/rotatorlist/{id}', 'AdminController@editRotator');
   Route::get('/unexportedlead', 'AdminController@unexpLead');
   Route::get('/exportlead', 'AdminController@exportsLead');
   Route::get('/report', 'AdminController@leadReport');
    
});