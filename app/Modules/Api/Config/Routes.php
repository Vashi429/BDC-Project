<?php
if(!isset($routes)){ 
    $routes = \Config\Services::routes(true);
}

$routes->group('Api', ['namespace' => 'App\Modules\Api\Controllers'], function($subroutes){
	
		/*** Route for Dashboard ***/    
	//Supervisor Api Routes
	$subroutes->add('supervisor/login', 'Supervisor::login');
	$subroutes->add('supervisor/home', 'Supervisor::home');
	$subroutes->add('supervisor/SupervisorProjectList', 'Supervisor::SupervisorProjectList');
	$subroutes->add('supervisor/VendorList', 'Supervisor::VendorList');
	$subroutes->add('supervisor/ItemList', 'Supervisor::ItemList');
	$subroutes->add('supervisor/insertChallanReocde', 'Supervisor::insertChallanReocde');
	$subroutes->add('supervisor/updateChallanReocde', 'Supervisor::updateChallanReocde');
	$subroutes->add('supervisor/ChallanList', 'Supervisor::ChallanList');
	$subroutes->add('supervisor/TaskList', 'Supervisor::TaskList');
	$subroutes->add('supervisor/AddTask', 'Supervisor::AddTask');
	$subroutes->add('supervisor/UpdateTask', 'Supervisor::UpdateTask');
	$subroutes->add('supervisor/searchChallan', 'Supervisor::searchChallan');
	$subroutes->add('supervisor/getState', 'Supervisor::getState');
	$subroutes->add('supervisor/getCity', 'Supervisor::getCity');
	$subroutes->add('supervisor/add_vendor', 'Supervisor::add_vendor');
	$subroutes->add('supervisor/checkUniqueEmail', 'Supervisor::checkUniqueEmail');
	$subroutes->add('supervisor/checkUniquePhone', 'Supervisor::checkUniquePhone');
	$subroutes->add('supervisor/checkUniquePanCard', 'Supervisor::checkUniquePanCard');
	$subroutes->add('supervisor/checkUniqueGstNumber', 'Supervisor::checkUniqueGstNumber');
	$subroutes->add('supervisor/checkUniqueAdharCard', 'Supervisor::checkUniqueAdharCard');
	$subroutes->add('supervisor/attendance', 'Supervisor::attendance');
	$subroutes->add('supervisor/AttendanceProjectList', 'Supervisor::AttendanceProjectList');
	$subroutes->add('supervisor/ExpenseList', 'Supervisor::ExpenseList');
	$subroutes->add('supervisor/expense_list_bill', 'Supervisor::expens_list_bill');
	$subroutes->add('supervisor/insertExpense', 'Supervisor::add_expens');
	$subroutes->add('supervisor/expenseProfileList', 'Supervisor::profile_list_expense');
	$subroutes->add('supervisor/expenseProjectList', 'Supervisor::project_list_using_profile_expense');
	$subroutes->add('supervisor/expenseLabourList', 'Supervisor::get_labour_using_project');
	$subroutes->add('supervisor/expenseBillList', 'Supervisor::getBillList');

	

	

});