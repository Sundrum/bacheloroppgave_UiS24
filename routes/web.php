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



/***
 * Admin pages 
***/
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'dashboard']);
Route::get('/admin/apibilling', [App\Http\Controllers\AdminController::class, 'apismartsensor'])->name('billing');
Route::get('/admin/billing/{customer}/{quarter}/{detailed}', [App\Http\Controllers\ApiSmartsensorController::class, 'getBilling']);
Route::get('/admin/billing/summary/{customer}/{quarter}/{detailed}', [App\Http\Controllers\ApiSmartsensorController::class, 'getSummary']);

Route::get('/admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('user');

Route::get('/admin/account/{id}', [App\Http\Controllers\Admin\UserController::class, 'get']);
Route::get('/admin/account', [App\Http\Controllers\Admin\UserController::class, 'new'])->name('newuser');
Route::post('/admin/user/update', [App\Http\Controllers\Admin\UserController::class, 'user'])->name('updateUser');
// Route::post('/admin/user/delete', 'Admin\UserController@delete');

// Admin sensorunit
Route::get('/admin/sensorunit', [App\Http\Controllers\Admin\SensorunitController::class, 'index'])->name('sensorunit');
Route::get('/admin/newsensorunit', [App\Http\Controllers\Admin\SensorunitController::class, 'new'])->name('adminaddunit');
Route::post('/admin/addsensorunit', [App\Http\Controllers\Admin\SensorunitController::class, 'add'])->name('adminnewunits');
Route::get('/admin/sensorunitall', [App\Http\Controllers\Admin\SensorunitController::class, 'all']);
Route::get('/admin/sensorunit/{id}', [App\Http\Controllers\Admin\SensorunitController::class, 'get']);
Route::get('/admin/customerunit/{id}', [App\Http\Controllers\Admin\SensorunitController::class, 'getCustomer']);

Route::post('/admin/sensorunit/update', [App\Http\Controllers\Admin\SensorunitController::class, 'update'])->name('updateSensorunit');
Route::post('/admin/sensorunit/updatecustomer', [App\Http\Controllers\Admin\SensorunitController::class, 'updateSensorunitCustomer']);
Route::post('/admin/sensorunit/delete', [App\Http\Controllers\Admin\SensorunitController::class, 'delete']);

Route::post('/admin/upload/firmware', [App\Http\Controllers\Admin\CommandController::class, 'uploadFirmware'])->name('uploadFirmware');
Route::get('/admin/queue', [App\Http\Controllers\Admin\CommandController::class, 'viewFirmware'])->name('showFirmware');
Route::get('/admin/firmware', [App\Http\Controllers\Admin\CommandController::class, 'showFirmware'])->name('firmwarelist');
Route::post('/admin/firmware/change', [App\Http\Controllers\Admin\CommandController::class, 'changeFirmware'])->name('firmwarechange');
Route::post('/admin/firmware/delete', [App\Http\Controllers\Admin\CommandController::class, 'deleteFirmware'])->name('firmwaredelete');

Route::post('/admin/connect/add', [App\Http\Controllers\Admin\AccessController::class, 'addAccess']);
Route::post('/admin/connect/delete', [App\Http\Controllers\Admin\AccessController::class, 'deleteAccess']);

Route::get('/admin/customer', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customeradmin');
Route::get('/admin/newcustomer', [App\Http\Controllers\Admin\CustomerController::class, 'newCustomer'])->name('newcustomer');
Route::post('/admin/customer', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('detailedcustomer');
Route::get('/admin/customer/edit/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'getCustomer']);
Route::get('/admin/customer/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'getOverview']);
Route::post('/admin/subscription', [App\Http\Controllers\Admin\CustomerController::class, 'customerSubscription']);

Route::get('/admin/unittypes', [App\Http\Controllers\AdminController::class, 'getUnittypes'])->name('unittype');
Route::get('/admin/newunittype', [App\Http\Controllers\UnittypeController::class, 'newUnittype'])->name('newunittype');
Route::get('/admin/unittype/{id}', [App\Http\Controllers\UnittypeController::class, 'edit']);
Route::post('/admin/unittype', [App\Http\Controllers\UnittypeController::class, 'update'])->name('updateunittype');
Route::get('/admin/unittypeslist', [App\Http\Controllers\Admin\UnittypeController::class, 'list']);
Route::post('/admin/sensorprobe/add', [App\Http\Controllers\Admin\UnittypeController::class, 'addSensorprobe']);
Route::post('/admin/sensorprobe/alert', [App\Http\Controllers\Admin\UnittypeController::class, 'changeAlert']);
Route::post('/admin/sensorprobe/hidden', [App\Http\Controllers\Admin\UnittypeController::class, 'changeHidden']);
Route::post('/admin/sensorprobe/delete', [App\Http\Controllers\Admin\UnittypeController::class, 'deleteSensorprobe']);

Route::get('/admin/probes', [App\Http\Controllers\AdminController::class, 'getProbes']);
Route::get('/admin/xyz', [App\Http\Controllers\Admin\SensorunitController::class, 'xyz'])->name('xyz');
Route::get('/admin/testxyz', [App\Http\Controllers\Admin\SensorunitController::class, 'testxyz'])->name('testxyz');

Route::get('/dev/compass', [App\Http\Controllers\Admin\DevelopmentController::class, 'compass'])->name('compass');
Route::get('/dev/woodmoisture', [App\Http\Controllers\Admin\DevelopmentController::class, 'woodMoisture']);
Route::get('/dev/calculate/woodmoisture/{id}/{temperature}/{ohm}', [App\Http\Controllers\Admin\DevelopmentController::class, 'developmentWoodMoisture']);
Route::get('/dev/flowrate', [App\Http\Controllers\Admin\DevelopmentController::class, 'flowrate']);
Route::get('/dev/calculaterun/{serial}/{run}', [App\Http\Controllers\Admin\DevelopmentController::class, 'devIrrigationRun']);
Route::get('/log/irrigation/{serial}/{run}', [App\Http\Controllers\Admin\DevelopmentController::class, 'logIrrigationRun']);


Route::get('/admin/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('product');
Route::get('/admin/product/{id}', [App\Http\Controllers\Admin\ProductController::class, 'product']);
Route::get('/admin/newproduct', [App\Http\Controllers\Admin\ProductController::class, 'new'])->name('newproduct');
Route::post('/admin/update/product', [App\Http\Controllers\Admin\ProductController::class, 'update']);

Route::get('/admin/map/irrigationstatus', [App\Http\Controllers\Admin\IrrigationController::class, 'map']);
Route::get('/admin/irrigationstatus/{serial}', [App\Http\Controllers\Admin\IrrigationController::class, 'get']);
Route::get('/admin/irrigationstatus/update', [App\Http\Controllers\Admin\IrrigationController::class, 'updateStatusPage']);
Route::get('/admin/irrigationdebug/{serial}', [App\Http\Controllers\Admin\IrrigationController::class, 'debug']);
Route::get('/admin/irrigationstatus', [App\Http\Controllers\AdminController::class, 'irrigationStatus']);
Route::get('/admin/irrigation/fota', [App\Http\Controllers\Admin\CommandController::class, 'irrigationFota']);
Route::post('/admin/queue/delete', [App\Http\Controllers\Admin\CommandController::class, 'deleteQueue']);

Route::get('/admin/farmfield/{serial}', [App\Http\Controllers\Admin\DevelopmentController::class, 'farmfield']);

Route::get('/select', [App\Http\Controllers\AdminController::class, 'select']);
Route::get('/select/{userid}/{customernumber}', [App\Http\Controllers\AdminController::class, 'setUser']);

/***
 * Development pages 
***/
Route::get('/admin/dev/copy', [App\Http\Controllers\Development\MapController::class, 'showCopyRun']);
Route::post('/admin/dev/data', [App\Http\Controllers\Development\MapController::class, 'getMidMarker']);

/***
 * Dashboard routes
***/
Route::get('/', [App\Http\Controllers\Controller::class, 'dashboard']);
Route::get('/dashboard', [App\Http\Controllers\Controller::class, 'dashboard']);

Route::post('/setorder', [App\Http\Controllers\DashboardController::class, 'setOrder'])->name('setorder');
Route::post('/addgroup', [App\Http\Controllers\DashboardController::class, 'addGroup'])->name('addgroup');
Route::post('/deletegroup', [App\Http\Controllers\DashboardController::class, 'deleteGroup'])->name('deletegroup');
Route::post('/updategroup', [App\Http\Controllers\DashboardController::class, 'updateGroup'])->name('updategroup');
Route::post('/startirrigation', [App\Http\Controllers\DashboardController::class, 'startIrrigation'])->name('startirrigation');
Route::post('/timezone', [App\Http\Controllers\DashboardController::class, 'setTimezone']);


/***
 * Settings routes 
***/
Route::get('/settings', [App\Http\Controllers\Controller::class, 'settings']);
Route::get('/settings/{id}', [App\Http\Controllers\Controller::class, 'settingsid']);
Route::get('/myaccount', [App\Http\Controllers\Controller::class, 'myaccount']);
Route::post('/myaccount', [App\Http\Controllers\SettingsController::class, 'changeaccount'])->name('changeaccount');
Route::post('/connect', [App\Http\Controllers\SettingsController::class, 'shareunit'])->name('shareunit');
Route::post('/customersettings', [App\Http\Controllers\SettingsController::class, 'updateCustomerSettings'])->name('updatecustomersettings');
Route::post('/irrigationsettings', [App\Http\Controllers\SettingsController::class, 'irrigationsettings'])->name('irrigationsettings');
Route::post('/sensorsettings', [App\Http\Controllers\SettingsController::class, 'updateSensorSettings'])->name('sensorsettings');

Route::post('/customeradmin/user/delete', [App\Http\Controllers\CustomerAdminController::class, 'deleteUser']);
Route::post('/customeradmin/user/update', [App\Http\Controllers\CustomerAdminController::class, 'updateUser']);
Route::post('/customeradmin/access/update', [App\Http\Controllers\CustomerAdminController::class, 'deleteAccess']);

/***
 * Support routes 
***/
Route::get('/support', [App\Http\Controllers\Controller::class, 'support']);

/***
 * Detail view sensor pages 
***/
Route::get('/unit/{serial}', [App\Http\Controllers\Controller::class, 'sensorunit']);
Route::get('include/view_irrigation.php', [App\Http\Controllers\Controller::class, 'phoneEndpoint']);
Route::get('/irrigationrun/{serial}', [App\Http\Controllers\MapController::class, 'irrigationrun']);
Route::post('/updatePoint', [App\Http\Controllers\MapController::class, 'updatePoint']);
Route::get('/oldruns/{serial}/{days}', [App\Http\Controllers\MapController::class, 'oldIrrigaitonRun']);
Route::get('/run/{serial}', [App\Http\Controllers\MapController::class, 'oldRunMap']);
Route::get('/map', [App\Http\Controllers\Controller::class, 'testmap']);

/***
 * Messages pages 
***/
Route::get('/messages', [App\Http\Controllers\Controller::class, 'messages']);
Route::post('/deleteMessage', [App\Http\Controllers\MessagesController::class, 'deleteMessage'])->name('deleteMessage');

/***
 * Graph pages 
***/
Route::get('/graph', [App\Http\Controllers\Controller::class, 'graph']);
Route::get('/graph/units', [App\Http\Controllers\GraphController::class, 'getUnits'])->name('getUnits');
Route::get('/graph/getprobeinfo/{serialnumber}', [App\Http\Controllers\GraphController::class, 'getAllProbes'])->name('getAllProbes');
Route::get('/graph/getprobeinfo/{serialnumber}/{probetype}', [App\Http\Controllers\GraphController::class, 'getProbeInfo'])->name('graph');
Route::get('/graph/getsensordata/{serialnumber}/{days}/{probe}/{unittype_id}/{timestamp}', [App\Http\Controllers\GraphController::class, 'getSensorData'])->name('graphs');

Route::get('/connect/{id}', [App\Http\Controllers\SensorunitController::class, 'connect']);

//TEST

Route::get('/demo_norway', [App\Http\Controllers\TestDashboardController::class, 'norwayTest']);
Route::get('/demo_uk', [App\Http\Controllers\TestDashboardController::class, 'ukTest']);


Auth::routes();


/*** 
 * E-mail 
***/
//Route::get('/email', 'MailController@index');
//Route::post('/specific', 'MailController@specific');

// Route::get('/dbsensorunit', 'TestController@dbtest');
