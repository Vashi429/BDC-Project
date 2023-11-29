<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

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
$routes->setDefaultNamespace('App\Modules\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//pages root
$routes->get('set-language/(:segment)', 'Language::setLanguage/$1');


// $routes->get('{locale}', 'Home::index');
$routes->get('properties', 'Property::index');
$routes->get('city/(:any)', 'Property::cityDetail/$1');
$routes->get('properties/loadData/(:any)', 'Property::loadData/$1');
$routes->get('addProperty', 'Property::addProperty');
$routes->get('editProperty/(:any)', 'Property::editProperty/$1');

$routes->get('property/insertProperty', 'Property::insertProperty');
$routes->get('property/check_unique_skuid', 'Property::check_unique_skuid');
$routes->get('property/check_unique_slug', 'Property::check_unique_slug');
$routes->get('property/getSubCategory', 'Property::getSubCategory');
$routes->get('property/uploadImage', 'Property::uploadImage');
$routes->get('property/removeDragImges', 'Property::removeDragImges');
$routes->get('property/uploadPlan', 'Property::uploadPlan');
$routes->get('property/removeDragPlans', 'Property::removeDragPlans');

$routes->get('postProperty', 'Property::postProperty');
$routes->get('newProperty', 'Property::newProperty');

$routes->get('property/(:any)', 'Property::PropertyDetails/$1');
$routes->get('aboutUs', 'Pages::about_us');
$routes->get('whyUs', 'Pages::why_us');
$routes->get('services', 'Pages::services');
$routes->get('contactUs', 'Pages::contact_us');
$routes->get('termsConditions', 'Pages::terms_condition');
$routes->get('privacyPolicy', 'Pages::privacyPolicy');

$routes->get('signUp', 'Login::signUp');
$routes->get('login', 'Login::index');
$routes->get('forgotPassword', 'Login::forgotPassword');
$routes->get('logout', 'Login::logout');
$routes->get('changPassword/(:any)', 'Login::changPassword/$1');



$db = \Config\Database::connect();

/**
 * --------------------------------------------------------------------
 * HMVC Routing
 * --------------------------------------------------------------------
 */

foreach (glob(APPPATH . 'Modules/*', GLOB_ONLYDIR) as $item_dir) {
	if (file_exists($item_dir . '/Config/Routes.php')) {
		require_once($item_dir . '/Config/Routes.php');
	}
}

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
