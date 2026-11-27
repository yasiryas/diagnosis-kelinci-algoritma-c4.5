<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->addPlaceholder('slug', '[a-z0-9-]+');

$routes->get('/', 'Home::landing');
$routes->get('mulai-diagnosis', 'Home::startDiagnosis');
$routes->get('diagnosa', 'Diagnosis::index');
$routes->post('diagnosa/proses', 'Diagnosis::proses');
$routes->get('artikel', 'Artikel::index');
$routes->get('artikel/(:slug)', 'Artikel::show/$1');

$routes->group('user', ['filter' => 'login'], static function ($routes) {
    $routes->get('', 'User::index');
    $routes->get('index', 'User::index');
    $routes->get('diagnosis', 'User::diagnosis');
    $routes->post('diagnosis', 'User::simpanDiagnosis');
    $routes->get('profile', 'User::profile');
    $routes->get('edit', 'User::edit');
    $routes->get('changepassword', 'User::changePassword');
    $routes->post('updateprofile', 'User::updateProfile');
    $routes->post('updatepassword', 'User::updatePassword');
    $routes->get('prosesdiagnosis', 'User::prosesDiagnosis');
    $routes->post('konsultasi', 'Diagnosis::proses');
});

$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('', 'Admin::index');
    $routes->get('index', 'Admin::index');
    $routes->get('profile', 'Admin::profile');
    $routes->get('userlist', 'Admin::userlist');
    $routes->get('gejala', 'Admin::gejala');
    $routes->post('tambahgejala', 'Admin::tambahGejala');
    $routes->post('updategejala', 'Admin::updateGejala');
    $routes->post('deletegejala', 'Admin::deleteGejala');
    $routes->get('penyakit', 'Admin::penyakit');
    $routes->post('tambahpenyakit', 'Admin::tambahPenyakit');
    $routes->post('updatepenyakit', 'Admin::updatePenyakit');
    $routes->post('deletepenyakit', 'Admin::deletePenyakit');
    $routes->get('datasample', 'Admin::dataSample');
    $routes->post('tambahsample', 'Admin::tambahSample');
    $routes->post('updatesample', 'Admin::updateSample');
    $routes->post('deletesample', 'Admin::deleteSample');
    $routes->get('detail/(:num)', 'Admin::detail/$1');
    $routes->get('decisiontree', 'Admin::decisionTree');
    $routes->get('artikel', 'Admin::artikel');
    $routes->post('tambahartikel', 'Admin::tambahArtikel');
    $routes->post('updateartikel', 'Admin::updateArtikel');
    $routes->post('deleteartikel', 'Admin::deleteArtikel');
});

$routes->group('mining', ['filter' => 'admin'], static function ($routes) {
    $routes->get('hitung', 'Mining::hitung');
    $routes->post('mining', 'Mining::mining');
    $routes->post('kosong', 'Mining::kosong');
});

/*
 * --------------------------------------------------------------------
 * Myth:Auth routes
 * --------------------------------------------------------------------
 */
$routes->group('', ['namespace' => 'Myth\Auth\Controllers'], function ($routes) {
    // Login / out
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');

    // Registration
    $routes->get('register', 'AuthController::register', ['as' => 'register']);
    $routes->post('register', 'AuthController::attemptRegister');

    // Activation
    $routes->get('activate-account', 'AuthController::activateAccount', ['as' => 'activate-account']);
    $routes->get('resend-activate-account', 'AuthController::resendActivateAccount', ['as' => 'resend-activate-account']);

    // Forgot / Reset
    $routes->get('forgot', 'AuthController::forgotPassword', ['as' => 'forgot']);
    $routes->post('forgot', 'AuthController::attemptForgot');
    $routes->get('reset-password', 'AuthController::resetPassword', ['as' => 'reset-password']);
    $routes->post('reset-password', 'AuthController::attemptReset');

    // Change password
    $routes->get('change', 'AuthController::changePassword', ['as' => 'change']);
    $routes->post('change', 'AuthController::attemptChange');
    $routes->get('change-password', 'AuthController::passwordChange', ['as' => 'change-password']);
    $routes->post('change-password', 'AuthController::attemptPassword');
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
