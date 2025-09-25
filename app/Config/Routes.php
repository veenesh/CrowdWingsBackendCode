<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */
// Create a new instance of our RouteCollection class.

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
/* $routes->get('/', 'Home::index');
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
}); */



$routes->add('/admin/login', 'Auth/Admin::login');


$routes->group('admin', ['filter' => 'AdminFilter'], function($routes){
    $routes->add('dashboard', 'AdminArea/Admin::dashboard');

    $routes->add('member/list', 'AdminArea/Admin::memberListAll');
    $routes->add('member/edit', 'AdminArea/Admin::memberEdit');
    $routes->add('member/withdrawal', 'AdminArea/Admin::withdrawalList');
    
    
    $routes->add('vendor/list/all', 'AdminArea/Admin::vendorListAll');

    $routes->add('ad/list', 'AdminArea/Admin::listAd');

    $routes->add('widthrawal/request', 'AdminArea/Income::withdrawalRequest');
    $routes->add('widthrawal/request-history', 'AdminArea/Income::withdrawalRequestHistory');
    $routes->add('widthrawal/imps-status', 'AdminArea/Income::impsStatus');

    $routes->add('income/adview', 'AdminArea/Income::listAdView');
    $routes->add('income/withdrawal-request', 'AdminArea/Income::withdrawalRequest');

    $routes->add('topup', 'AdminArea/Admin::topUp');

    $routes->add('logout', 'AuthAdmin/Admin::logout');
});



$routes->group("api", function ($routes) {
    $routes->get("cron/roi", "Api\Income::roiCronJob");
    $routes->get("cron/matching-full", "Api\Income::matchingFullCronJob");
    $routes->get("cron/matching-day", "Api\Income::matchingDCronJob");
    $routes->get("cron/matching-night", "Api\Income::matchingNCronJob");


    $routes->get("token/all", "Api\Token::index");
    $routes->get("token/all/forex", "Api\Token::forexToken");
    $routes->get("update-forex", "Api\Token::updateForexData");
    $routes->get("token/all/crypto", "Api\Token::cryptoToken");
    $routes->post("login", "Api\Auth::index");
    $routes->post("register", "Api\Auth::register");
    $routes->post("sponsor-name", "Api\Auth::SponsorName");
    $routes->post("forgot-password", "Api\Auth::forgotPassword");

    $routes->post("new-otp", "Api\Auth::sendNewOtp");
    $routes->post("validate-otp", "Api\Auth::validateOTP");
    $routes->get("news", "Api\Member::news");
    $routes->get("autotransfer", "Api\Income::autoTransfer");
    $routes->get("autotransfer2", "Api\Income::autoTransfer2");
    
    $routes->get("token/valid", "Api\Auth::tokenValid", ['filter' => 'ApiFilter']);
    $routes->get("member/profile", "Api\Member::index", ['filter' => 'ApiFilter']);
    
    $routes->post("member/profile-update", "Api\Member::updateProfile", ['filter' => 'ApiFilter']);
    $routes->get("member/data", "Api\Member::memberData");
    $routes->get("member/downline", "Api\Member::memberDownlineByPosition");
    $routes->post("member/change-password", "Api\Member::changePassword", ['filter' => 'ApiFilter']);
    
    $routes->get("income", "Api\Income::index", ['filter' => 'ApiFilter']);
    $routes->get("income/direct", "Api\Income::directData", ['filter' => 'ApiFilter']);
    $routes->get("income/level", "Api\Income::levelData", ['filter' => 'ApiFilter']);
    $routes->get("income/roi", "Api\Income::roiData", ['filter' => 'ApiFilter']);
    $routes->get("income/salary", "Api\Income::salaryData", ['filter' => 'ApiFilter']);

    $routes->get("income/auto-pool-income", "Api\Income::autoPoolIncome", ['filter' => 'ApiFilter']);

    $routes->get("income/royality", "Api\Income::royality", ['filter' => 'ApiFilter']);
    $routes->get("income/reward", "Api\Income::rewardData", ['filter' => 'ApiFilter']);
    $routes->get("income/binary", "Api\Income::matching", ['filter' => 'ApiFilter']);
    $routes->get("income/leftrightbusiness", "Api\Income::leftrightbusiness", ['filter' => 'ApiFilter']);
    //$routes->get("matching", "Api/Member::matching", ['filter' => 'ApiFilter']);
    
    $routes->get("addFund", "Api\Income::addFund", ['filter' => 'ApiFilter']);
    $routes->get("addedFund", "Api\Income::addedFund", ['filter' => 'ApiFilter']);

    $routes->post("upgrade", "Api\Income::upgrade", ['filter' => 'ApiFilter']);
    $routes->post("transfer", "Api\Income::transfer", ['filter' => 'ApiFilter']);
    $routes->post("withdrawal", "Api\Income::withdrawal", ['filter' => 'ApiFilter']);
    
    $routes->get("withdrawals", "Api\Income::listWithdrawals", ['filter' => 'ApiFilter']);
    $routes->get("memberdeposits", "Api\Income::memberDeposites", ['filter' => 'ApiFilter']);
    
    $routes->get("upgrades", "Api\Income::listUpgrades", ['filter' => 'ApiFilter']);
    
    $routes->get("team", "Api\Member::team", ['filter' => 'ApiFilter']);
    $routes->get("matching-team", "Api\Member::matchingTeam", ['filter' => 'ApiFilter']);
    
    $routes->post("team-details", "Api\Member::teamDetails", ['filter' => 'ApiFilter']);
    
    $routes->get("help", "Api\Member::help", ['filter' => 'ApiFilter']);
    $routes->post("help/message", "Api\Member::helpMesssage", ['filter' => 'ApiFilter']);
    
    
    $routes->post('upload-file', 'Api\UploadController::uploadProfilePic', ['filter' => 'ApiFilter']);
    /* $routes->get("list", "Api/Auth::list");
    
    
    
    
    $routes->get("mining/income", "Api/Member::miningIncome", ['filter' => 'ApiFilter']);
    $routes->get("mining/todayincome", "Api/Member::todayEarning", ['filter' => 'ApiFilter']);
    $routes->get("mine", "Api/Member::mine", ['filter' => 'ApiFilter']); */
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
/* if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
 */