<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');

$routes->get('/products', function(){
    return view('product');
});

$routes->get('/contact', function(){
    return view('contact');
});

$routes->get('/terms-and-condition', function(){
    return view('terms-and-condition');
});
$routes->get('/refund-policy', function(){
    return view('refund-policy');
});

$routes->get('/privacy-policy-terms', function(){
    return view('privacy-policy-terms');
});
$routes->get('/disclimer', function(){
    return view('disclimer');
});
$routes->get('/cookies-policy', function(){
    return view('cookies-policy');
});


$routes->add('/user/login', 'AuthUser/User::login');
$routes->add('/user/register', 'AuthUser/User::register');

$routes->add('/user/thankyou', 'AuthUser/User::thankyou');

$routes->add('/vendor/login', 'AuthVendor/Vendor::login');
$routes->add('/vendor/register', 'AuthVendor/Vendor::register');

$routes->add('/admin/login', 'AuthAdmin/Admin::login');

$routes->get('/load/(:any)', 'Home::load/$1');
$routes->add('/uploadimage', 'Home::singleImageUpload');

$routes->group('admin', ['filter' => 'AdminFilter'], function($routes){
    $routes->add('dashboard', 'AdminArea/Admin::dashboard');

    $routes->add('member/list', 'AdminArea/Admin::memberListAll');
    $routes->add('member/edit', 'AdminArea/Admin::memberEdit');

    $routes->add('vendor/list/all', 'AdminArea/Admin::vendorListAll');

    $routes->add('ad/list', 'AdminArea/Admin::listAd');

    $routes->add('income/adview', 'AdminArea/Income::listAdView');

    $routes->add('topup', 'AdminArea/Admin::topUp');

    $routes->add('logout', 'AuthAdmin/Admin::logout');
});

$routes->group('vendor', ['filter' => 'VendorFilter'], function($routes){
    $routes->add('dashboard', 'VendorArea/Vendor::dashboard');
    $routes->add('profile', 'VendorArea/Vendor::profile');
    $routes->add('profile/edit-profile', 'VendorArea/Vendor::profileEdit');
    $routes->add('profile/change-password', 'VendorArea/Vendor::changePassword');
    $routes->add('profile/change-txn-password', 'VendorArea/Vendor::profileTxnPassword');
    $routes->add('ad/new', 'VendorArea/Vendor::adNew');
    $routes->add('ad/pending', 'VendorArea/Vendor::adPending');
    $routes->add('ad/approved', 'VendorArea/Vendor::adApproved');
    $routes->add('logout', 'AuthVendor/Vendor::logout');
});

$routes->group('member', ['filter' => 'MemberFilter'], function($routes){
    $routes->add('dashboard', 'MemberArea/Member::dashboard');
    $routes->add('profile/letter', 'MemberArea/Member::welcomeLetter');
    $routes->add('profile', 'MemberArea/Member::profile');
    $routes->add('profile/edit-profile', 'MemberArea/Member::profileEdit');
    $routes->add('profile/change-password', 'MemberArea/Member::changePassword');
    $routes->add('profile/change-txn-password', 'MemberArea/Member::profileTxnPassword');
    $routes->add('team/direct', 'MemberArea/Member::directTeam');
    $routes->add('team/generation', 'MemberArea/Member::generationTeam');
    $routes->add('withdrawal/request', 'MemberArea/Member::withdrawal');
    $routes->add('withdrawal/report', 'MemberArea/Member::withdrawalReport');
    $routes->add('kyc', 'MemberArea/Member::kyc');
    $routes->add('pin/transfer', 'MemberArea/Member::pinTransfer');
    $routes->add('pin/request', 'MemberArea/Member::pinRequest');
    $routes->add('pin/history', 'MemberArea/Member::pinHistory');
    $routes->add('pin/box', 'MemberArea/Member::pinBox');
    $routes->add('topup', 'MemberArea/Member::topUp');
    $routes->add('task', 'MemberArea/Member::task');
    $routes->add('income/level-sponsor', 'MemberArea/Member::levelSponsorIncome');
    $routes->add('income/self-ad-view', 'MemberArea/Member::selfAdViewincome');
    $routes->add('income/ref-ad-view', 'MemberArea/Member::refAdViewincome');
    $routes->add('income/pin-wallet', 'MemberArea/Member::pinWalletIncome');
    $routes->add('income/reward-award', 'MemberArea/Member::rewardAwardIncome');
    $routes->add('logout', 'AuthUser/User::logout');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
