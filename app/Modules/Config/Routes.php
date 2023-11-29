<?php
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('', ['namespace' => 'App\Modules\Controllers'], function($subroutes){

	$subroutes->add('', 'Home::index');
	

	
	

	
	

	
	
});
