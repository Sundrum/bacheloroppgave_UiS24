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

// Sigurd og Kristian

Route::get('/api/create-payment', [App\Http\Controllers\PaymentController::class, 'createPayment']);// API
Route::get('/subscriptions', [App\Http\Controllers\SubscriptionsController::class, 'subscriptions'])->name('subscriptions');
Route::get('/subscriptionbilling', [App\Http\Controllers\SubscriptionBillingController::class, 'subscriptionbilling'])->name('subscriptionbilling');
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkoutsuccess', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkoutSuccess');
//Route::get('/checkout/{paymentId}', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/subscriptiondetails', [App\Http\Controllers\SubscriptionsController::class, 'subscriptionDetails'])->name('subscriptiondetails');
Route::post('/cancelSubscription', [App\Http\Controllers\SubscriptionsController::class, 'cancelSubscription'])->name('cancelSubscription');
Route::post('/reactivateSubscription', [App\Http\Controllers\SubscriptionsController::class, 'reactivateSubscription'])->name('reactivateSubscription');
Route::post('/manageSubscription', [App\Http\Controllers\SubscriptionsController::class, 'manageSubscription'])->name('manageSubscription');
Route::get('/updateUserData', [App\Http\Controllers\PaymentController::class, 'updateUserData'])->name('updateUserData');
Route::get('/retrievePayment', [App\Http\Controllers\retrievePaymentController::class, 'retrievePayment'])->name('retrievePayment');
Route::get('/paymenthistory', [App\Http\Controllers\PaymentHistoryController::class, 'paymentHistory'])->name('paymenthistory');
Route::get('/dboperations', [App\Http\Controllers\DbOperationsController::class, 'DbOperations'])->name('dboperations');
Route::post('/dboperationsdeleted', [App\Http\Controllers\DbOperationsController::class, 'delete'])->name('dboperationsdeleted');
Route::post('/dboperationsupdated', [App\Http\Controllers\DbOperationsController::class, 'update'])->name('dboperationsupdated');
Route::post('/dboperationsnewunit', [App\Http\Controllers\DbOperationsController::class, 'newSensorUnit'])->name('dboperationsnewunit');
Route::post('/dboperationsprice', [App\Http\Controllers\DbOperationsController::class, 'changePrice'])->name('dboperationsprice');
Route::post('/subscriptionpaymentdelete' , [App\Http\Controllers\DbOperationsController::class, 'deletesubpay'])->name('subscriptionpaymentdelete');
Route::post('/paymentsunitsdelete' , [App\Http\Controllers\DbOperationsController::class, 'deletepayunits'])->name('paymentsunitsdelete');
Route::post('/invoice', [App\Http\Controllers\InvoiceController::class, 'invoice'])->name('invoice');
Route::get('/shop', [App\Http\Controllers\ShopController::class, 'shop'])->name('shop');
Route::get('/managebilling', [App\Http\Controllers\ManageBillingController::class, 'managebilling'])->name('managebilling');



/***
 * Admin pages 
***/

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin');
Route::get('/admin/language', [App\Http\Controllers\AdminController::class, 'getLanguage']);

// Admin Proxy API
Route::get('/admin/apibilling', [App\Http\Controllers\AdminController::class, 'apismartsensor'])->name('billing');
Route::get('/admin/billing/{customer}/{quarter}/{detailed}', [App\Http\Controllers\ApiSmartsensorController::class, 'getBilling']);
Route::get('/admin/billing/summary/{customer}/{quarter}/{detailed}', [App\Http\Controllers\ApiSmartsensorController::class, 'getSummary']);
Route::get('/admin/proxy', [App\Http\Controllers\ApiSmartsensorController::class, 'proxyApi'])->name('proxy');
Route::get('/admin/proxy/variables', [App\Http\Controllers\ApiSmartsensorController::class, 'getProxyViewVariables'])->name('proxyvariables');
Route::post('/admin/proxy/getvariables', [App\Http\Controllers\ApiSmartsensorController::class, 'getProxyVariables']);
Route::post('/admin/proxy/setvariables', [App\Http\Controllers\ApiSmartsensorController::class, 'setProxyVariables']);
Route::post('/admin/proxy/fota', [App\Http\Controllers\ApiSmartsensorController::class, 'fotaQueue'])->name('fotaproxy');
Route::post('/admin/proxy/queue/delete',  [App\Http\Controllers\ApiSmartsensorController::class, 'deleteQueue'])->name('deletequeue');

Route::get('/admin/user', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('user');

Route::get('/admin/account/{id}', [App\Http\Controllers\Admin\UserController::class, 'get']);
Route::get('/admin/account', [App\Http\Controllers\Admin\UserController::class, 'new'])->name('newuser');
Route::post('/admin/user/update', [App\Http\Controllers\Admin\UserController::class, 'user'])->name('updateUser');
// Route::post('/admin/user/delete', 'Admin\UserController@delete');

Route::get('/admin/activity/daily', [App\Http\Controllers\ActivityController::class, 'daily']);

// Admin cases
Route::get('/admin/sensorunit/cases', [App\Http\Controllers\Admin\SensorunitController::class, 'casesIndex'])->name('cases');
Route::get('/admin/sensorunit/cases/new', [App\Http\Controllers\Admin\SensorunitController::class, 'newCase'])->name('newcase');
Route::post('/admin/sensorunit/cases/new', [App\Http\Controllers\Admin\SensorunitController::class, 'createCase']);
Route::post('/admin/sensorunit/casesdelete', [App\Http\Controllers\Admin\SensorunitController::class, 'deleteCase']);
Route::get('/admin/sensorunit/cases/{id}', [App\Http\Controllers\Admin\SensorunitController::class, 'oneCase']);
Route::post('/admin/sensorunit/cases/{id}', [App\Http\Controllers\Admin\SensorunitController::class, 'updateCase']);

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
Route::post('/admin/order/confirmation', [App\Http\Controllers\Admin\CustomerController::class, 'sendOrderInformation']);

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
Route::get('/dev/payment', [App\Http\Controllers\Admin\DevelopmentController::class, 'payment']);
Route::get('/dev/woodmoisture', [App\Http\Controllers\Admin\DevelopmentController::class, 'woodMoisture']);
Route::get('/dev/graph', [App\Http\Controllers\Admin\DevelopmentController::class, 'graph'])->name('devgraph');
Route::get('/dev/productionlog', [App\Http\Controllers\Admin\DevelopmentController::class, 'productionLog'])->name('devproductionLog');
Route::post('/dev/process/productionlog', [App\Http\Controllers\Admin\DevelopmentController::class, 'processLog']);
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
Route::get('/admin/irrigationstatus/irrigationrun/{id}', [App\Http\Controllers\Admin\IrrigationController::class, 'getRun']);
Route::get('/admin/irrigationstatus/irrigation/run/{id}', [App\Http\Controllers\Admin\IrrigationController::class, 'getIrrigationRun']);
Route::post('/admin/irrigationstatus/irrigationrun/cleandata', [App\Http\Controllers\Admin\IrrigationController::class, 'cleanData'])->name('cleandata');
Route::post('/admin/irrigationstatus/irrigationrun/update', [App\Http\Controllers\Admin\IrrigationController::class, 'updateRun'])->name('runupdate');

Route::post('/admin/irrigationstatus/autostart', [App\Http\Controllers\Admin\IrrigationController::class, 'autoStart']);

Route::get('/admin/irrigationrun', [App\Http\Controllers\Admin\IrrigationrunController::class, 'index']);
Route::get('/admin/irrigationrun/get/{id}', [App\Http\Controllers\Admin\IrrigationrunController::class, 'getRun']);
Route::post('/admin/irrigationrun/edit', [App\Http\Controllers\Admin\IrrigationrunController::class, 'editRun']);

Route::get('/admin/irrigationstatusupdate', [App\Http\Controllers\Admin\IrrigationController::class, 'updateStatusPage']);
Route::post('/admin/irrigation/removepoistion', [App\Http\Controllers\Admin\IrrigationController::class, 'removePosition']);
Route::post('/irrigation/deleterun', [App\Http\Controllers\SensorunitController::class, 'deleteRun']);
Route::get('/admin/irrigationdebug/{serial}', [App\Http\Controllers\Admin\IrrigationController::class, 'debug']);
Route::get('/admin/irrigationstatus', [App\Http\Controllers\AdminController::class, 'irrigationStatus'])->name('irrigationstatus');
Route::post('/admin/irrigation/fota', [App\Http\Controllers\Admin\CommandController::class, 'irrigationFota']);
Route::post('/admin/queue/delete', [App\Http\Controllers\Admin\CommandController::class, 'deleteQueue']);

Route::get('/admin/farmfield/{serial}', [App\Http\Controllers\Admin\DevelopmentController::class, 'farmfield']);

Route::get('/select', [App\Http\Controllers\AdminController::class, 'select'])->name('selectuser');
Route::get('/select/{userid}/{customernumber}', [App\Http\Controllers\AdminController::class, 'setUser']);

/***
 * Development pages 
***/
Route::get('/admin/dev/copy', [App\Http\Controllers\Development\MapController::class, 'showCopyRun']);
Route::post('/admin/dev/data', [App\Http\Controllers\Development\MapController::class, 'getMidMarker']);

/***
 * Dashboard routes
***/
Route::get('/', [App\Http\Controllers\Controller::class, 'dashboard'])->name('dashboard');
Route::get('/home', [App\Http\Controllers\Controller::class, 'dashboard']);
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
Route::get('/settings', [App\Http\Controllers\Controller::class, 'settings'])->name('settings');
Route::get('/settings/{id}', [App\Http\Controllers\Controller::class, 'settingsid']);
Route::get('/myaccount', [App\Http\Controllers\Controller::class, 'myaccount'])->name('myaccount');
Route::post('/myaccount', [App\Http\Controllers\SettingsController::class, 'changeaccount'])->name('changeaccount');
Route::post('/connect', [App\Http\Controllers\SettingsController::class, 'shareunit'])->name('shareunit');
Route::post('/customersettings', [App\Http\Controllers\SettingsController::class, 'updateCustomerSettings'])->name('updatecustomersettings');
Route::post('/irrigationsettings', [App\Http\Controllers\SettingsController::class, 'irrigationsettings'])->name('irrigationsettings');
Route::post('/sensorsettings', [App\Http\Controllers\SettingsController::class, 'updateSensorSettings'])->name('sensorsettings');

Route::post('/customeradmin/user/delete', [App\Http\Controllers\CustomerAdminController::class, 'deleteUser']);
Route::post('/customeradmin/user/update', [App\Http\Controllers\CustomerAdminController::class, 'updateUser']);
Route::post('/customeradmin/access/delete', [App\Http\Controllers\CustomerAdminController::class, 'deleteAccess']);

/***
 * Support routes 
***/
Route::get('/support', [App\Http\Controllers\Controller::class, 'support'])->name('support');

/***
 * Detail view sensor pages 
***/
Route::get('/unit/{serial}', [App\Http\Controllers\Controller::class, 'sensorunit']);
Route::get('/unit/notifications/{serial}', [App\Http\Controllers\SensorunitController::class, 'getNotification']);
Route::post('/unit/notification', [App\Http\Controllers\SensorunitController::class, 'setNotification']);
Route::get('include/view_irrigation.php', [App\Http\Controllers\Controller::class, 'phoneEndpoint']);
Route::get('/irrigationrun/{serial}', [App\Http\Controllers\MapController::class, 'irrigationrun']);
Route::post('/irrigationlog/update', [App\Http\Controllers\MapController::class, 'updateNotes']);
Route::post('/updatePoint', [App\Http\Controllers\MapController::class, 'updatePoint']);
Route::get('/oldruns/{serial}/{days}', [App\Http\Controllers\MapController::class, 'oldIrrigaitonRun']);
Route::get('/run/{serial}', [App\Http\Controllers\MapController::class, 'oldRunMap']);
Route::get('/map', [App\Http\Controllers\Controller::class, 'testmap']);
Route::get('/irrigation/log',  [App\Http\Controllers\Controller::class, 'irrigationRuns'])->name('irrigationLog');
Route::get('/irrigation/run',  [App\Http\Controllers\Controller::class, 'getIrrigationEvents']);
Route::post('/irrigation/flow',  [App\Http\Controllers\MapController::class, 'irrigationFlow']);
Route::get('/irrigation/runlog/{id}', [App\Http\Controllers\MapController::class, 'getRun']);
Route::get('/fleetmanagement', [App\Http\Controllers\MapController::class, 'fleetmanagement'])->name('fleetmanagment');


/***
 * Messages pages 
***/
Route::get('/messages', [App\Http\Controllers\Controller::class, 'messages'])->name('messages');
Route::post('/deleteMessage', [App\Http\Controllers\MessagesController::class, 'deleteMessage'])->name('deleteMessage');

/***
 * Graph pages 
***/
Route::get('/graph', [App\Http\Controllers\Controller::class, 'graph'])->name('getGraph');
Route::get('/graph/units', [App\Http\Controllers\GraphController::class, 'getUnits'])->name('getUnits');
Route::get('/graph/getprobeinfo/{serialnumber}', [App\Http\Controllers\GraphController::class, 'getAllProbes'])->name('getAllProbes');
Route::get('/graph/getprobeinfo/{serialnumber}/{probetype}', [App\Http\Controllers\GraphController::class, 'getProbeInfo'])->name('graph');
Route::get('/graph/getsensordata/{serialnumber}/{days}/{probe}/{unittype_id}/{timestamp}', [App\Http\Controllers\GraphController::class, 'getSensorData'])->name('graphs');

Route::get('/connect/{id}', [App\Http\Controllers\SensorunitController::class, 'connect']);

//TEST

Route::get('/demo_norway', [App\Http\Controllers\TestDashboardController::class, 'norwayTest']);
Route::get('/demo_uk', [App\Http\Controllers\TestDashboardController::class, 'ukTest']);


Auth::routes();

Route::get('/myaccount/validation', [App\Http\Controllers\Auth\VerificationController::class, 'getValidation']);
Route::post('/verify/email', [App\Http\Controllers\Auth\VerificationController::class, 'requestValidationMail']);
Route::post('/verify/phone', [App\Http\Controllers\Auth\VerificationController::class, 'requestValidationSMS']);
Route::post('/validate/phone', [App\Http\Controllers\Auth\VerificationController::class, 'verifySMS']);
Route::post('/validate/email', [App\Http\Controllers\Auth\VerificationController::class, 'verifyMail']);
Route::get('/verify/testmail', [App\Http\Controllers\Auth\VerificationController::class, 'testMail']);

Route::get('/payment/generate', [App\Http\Controllers\NetsController::class, 'createSubscription']);
Route::post('/payment/capture', [App\Http\Controllers\NetsController::class, 'capturePaymentInformaiton'])->name('payment.activate')->middleware('auth');
Route::get('/payment/callback', [App\Http\Controllers\NetsController::class, 'callback']);

Route::prefix('admin/payment')->group(function () {
    Route::get('/all', [App\Http\Controllers\NetsController::class, 'index'])->name('payment.index')->middleware('auth', 'checkadmin');
    Route::post('/update', [App\Http\Controllers\NetsController::class, 'update'])->name('payment.update')->middleware('auth', 'checkadmin');
    Route::get('/transaction', [App\Http\Controllers\NetsController::class, 'transaction'])->name('payment.transaction')->middleware('auth', 'checkadmin');

});

Route::get('/admin/gateway', [App\Http\Controllers\Admin\DevelopmentController::class, 'gateway']);
Route::post('/admin/loggateway', [App\Http\Controllers\Admin\DevelopmentController::class, 'logGateway']);

Route::post('/openai/group', [App\Http\Controllers\OpenaiController::class, 'groupAnalyze']);

Route::fallback(function () {
    return view('fallback');
});
/*** 
 * E-mail 
***/
// Route::get('/email', [App\Http\Controllers\MailController::class, 'index']);
//Route::post('/specific', 'MailController@specific');

// Route::get('/dbsensorunit', 'TestController@dbtest');


/*** 
 * Mandeep Test_Demo route
****/
Route::get('/profilemandeep',function(){
    return view('pages.profilemandeep');
});
Route::get('/profilemandeep/{serial_no}',[App\Http\Controllers\FetchSensorDat_Mandeep::class,'sensorData_daily']);
Route::get('/probenomandeep/{serial_no}',[App\Http\Controllers\FetchSensorDat_Mandeep::class,'probeNo_daily']);
