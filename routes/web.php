<?php

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
    return view('welcome');
});


Route::resource('tested','TestedController');#debug



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/admin/users', 'UserController@index')->name('user');
Route::get('/admin/users', 'UsersController@index')->middleware('role:users');
Route::get('/admin/editProfile', 'UsersController@editProfile')->name('userdata.editProfile');



Route::get('/admin/groups', 'GroupsController@index')->middleware('role:groups');
Route::get('/admin/roles', 'RoleController@index')->middleware('role:roles');
Route::get('/admin/ports/{id}', 'PortsController@index')->middleware('role:ports');
Route::get('/mikrotik', 'PortsController@tested')->middleware('role:ports');



Route::get('/getUserList','UsersController@anyData')->name('userdata.data')->middleware('role:users--readAcc');
Route::post('/userCreate','UsersController@create')->name('userdata.create')->middleware('role:users--createAcc');
Route::put('/API/userActive/{id}/{active}','UsersController@active')->name('userdata.active')->middleware('role:users--updateAcc');

Route::group(['prefix' => 'API/user'], function(){
	Route::get('{id}','UsersController@get')->name('userdata.get')->middleware('role:users--readAcc');
	Route::post('{id}','UsersController@update')->name('userdata.update')->middleware('role:users--updateAcc');
	Route::delete('{id}','UsersController@delete')->name('userdata.delete')->middleware('role:users--deleteAcc');
	Route::post('edit/profile','UsersController@updateProfile')->name('userdata.editProfilDe');
 });


Route::group(['prefix' => 'API/Groups'], function(){
	Route::get('list','GroupsController@anyData')->middleware('role:groups--readAcc');
	Route::post('','GroupsController@create')->middleware('role:groups--createAcc');
	Route::get('{id}','GroupsController@get')->middleware('role:groups--readAcc');
	Route::post('{id}','GroupsController@update')->middleware('role:groups--updateAcc');
	Route::delete('{id}','GroupsController@delete')->middleware('role:groups--deleteAcc');
 });

Route::group(['prefix' => 'API/Role'], function(){
	Route::get('list','RoleController@anyData')->middleware('role:roles--readAcc');
	Route::post('','RoleController@create')->middleware('role:roles--createAcc');
	Route::get('{id}','RoleController@get')->middleware('role:roles--readAcc');
	Route::post('Assign/{id}','RoleController@assign')->middleware('role:roles--createAcc');
	Route::put('{id}/{action}/{active}','RoleController@active')->middleware('role:roles--updateAcc');
	Route::delete('{id}','RoleController@delete')->middleware('role:roles--deleteAcc');
 });

Route::group(['prefix' => 'API/PortForwading'], function(){
	Route::get('list','PortsController@anyData')->middleware('role:ports--readAcc');
	Route::post('Create','PortsController@create')->middleware('role:ports--createAcc');
	Route::get('{id}','PortsController@get')->middleware('role:ports--readAcc');
	Route::post('{id}','PortsController@update')->middleware('role:ports--readAcc');
	Route::post('mikrotik/{id}','PortsController@updateMikrotik')->middleware('role:ports--readAcc');
	Route::get('mikrotik/{id}','PortsController@getMikrotik')->middleware('role:ports--readAcc');
	Route::get('listConfig/{id}','PortsController@listConfig')->middleware('role:ports--readAcc');
	Route::get('connection/mikrotik','PortsController@checkMikrotik')->middleware('role:ports--readAcc');
	Route::delete('{mikrotik_id}/{id}','PortsController@delete')->middleware('role:ports--deleteAcc');
 });
