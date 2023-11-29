<?php
// echo 'ca';exit;
if(!isset($routes))
{ 
    $routes = \Config\Services::routes(true);
}

$routes->group('admin', ['namespace' => 'App\Modules\Admin\Controllers'], function($subroutes){
	
	/*** Route for Dashboard ***/
    
	//Login module
	$subroutes->add('', 'Login::index');
	$subroutes->add('login', 'Login::index');
	$subroutes->add('login/user_signin', 'Login::user_signin');
	$subroutes->add('login/logout', 'Login::logout');

	//Dashboard module	
	$subroutes->add('dashboard', 'Dashboard::index');
	$subroutes->add('dashboard/change_pass', 'Dashboard::change_pass');
	$subroutes->add('dashboard/getCityList', 'Dashboard::getCityList');

	//Customer module
	$subroutes->add('customer', 'Customer::index');
	$subroutes->add('customer/loadData/(:any)', 'Customer::loadData/$1');
	$subroutes->add('customer/addCustomer', 'Customer::addCustomer');
    $subroutes->add('customer/checkUniqueEmail', 'Customer::checkUniqueEmail');
	$subroutes->add('customer/insertRecord', 'Customer::insertRecord');
	$subroutes->add('customer/editCustomer/(:any)', 'Customer::editCustomer/$1');
	$subroutes->add('customer/updateCustomer/(:num)', 'Customer::updateCustomer/$1');
	$subroutes->add('customer/delete_multiple', 'Customer::delete_multiple');
	$subroutes->add('customer/UpdatePublish', 'Customer::UpdatePublish');
	$subroutes->add('customer/createdisplayname', 'Customer::createdisplayname');
	$subroutes->add('customer/checkUniqueGST', 'Customer::checkUniqueGST');
	$subroutes->add('customer/checkUniquePanCard', 'Customer::checkUniquePanCard');
	$subroutes->add('customer/exportCSV', 'Customer::exportCSV');
	$subroutes->add('customer/checkUniquePhone', 'Customer::checkUniquePhone');

	
	// Vendor module
	$subroutes->add('vendor', 'Vendor::index');
	$subroutes->add('vendor/loadData/(:any)', 'Vendor::loadData/$1');
	$subroutes->add('vendor/addVendor', 'Vendor::addVendor');
    $subroutes->add('vendor/checkUniqueEmail', 'Vendor::checkUniqueEmail');
	$subroutes->add('vendor/insertRecord', 'Vendor::insertRecord');
	$subroutes->add('vendor/editVendor/(:any)', 'Vendor::editVendor/$1');
	$subroutes->add('vendor/updateVendor/(:num)', 'Vendor::updateVendor/$1');
	$subroutes->add('vendor/delete_multiple', 'Vendor::delete_multiple');
	$subroutes->add('vendor/UpdatePublish', 'Vendor::UpdatePublish');
	$subroutes->add('vendor/exportCSV', 'Vendor::exportCSV');
	$subroutes->add('vendor/checkUniqueGST', 'Vendor::checkUniqueGST');
	$subroutes->add('vendor/checkUniquePanCard', 'Vendor::checkUniquePanCard');
	$subroutes->add('vendor/checkUniquePhone', 'Vendor::checkUniquePhone');

    
    // Labor Type

    $subroutes->add('laborType','LaborType::index');
    $subroutes->add('laborType/loadData/(:any)', 'LaborType::loadData/$1');
    $subroutes->add('laborType/checkUniqueLaborType', 'LaborType::checkUniqueLaborType');
    $subroutes->add('laborType/insertRecord', 'LaborType::insertRecord');
    $subroutes->add('laborType/editLaborType', 'LaborType::editLaborType');
	$subroutes->add('laborType/delete_multiple', 'LaborType::delete_multiple');
	$subroutes->add('laborType/UpdatePublish', 'LaborType::UpdatePublish');
	$subroutes->add('laborType/exportCSV', 'LaborType::exportCSV');
	
	// Material Type

    $subroutes->add('material','Material::index');
    $subroutes->add('material/loadData/(:any)', 'Material::loadData/$1');
    $subroutes->add('material/insertRecord', 'Material::insertRecord');
    $subroutes->add('material/editMaterial', 'Material::editMaterial');
	$subroutes->add('material/delete_multiple', 'Material::delete_multiple');
	$subroutes->add('material/UpdatePublish', 'Material::UpdatePublish');
	$subroutes->add('material/exportCSV', 'Material::exportCSV');

	
	//Supervisor module
	$subroutes->add('supervisor', 'Supervisor::index');
	$subroutes->add('supervisor/loadData/(:any)', 'Supervisor::loadData/$1');
	$subroutes->add('supervisor/loadDataattendance/(:any)', 'Supervisor::loadDataattendance/$1');
	$subroutes->add('supervisor/addSupervisor', 'Supervisor::addSupervisor');
    $subroutes->add('supervisor/checkUniqueEmail', 'Supervisor::checkUniqueEmail');
	$subroutes->add('supervisor/insertRecord', 'Supervisor::insertRecord');
	$subroutes->add('supervisor/editSupervisor/(:any)', 'Supervisor::editSupervisor/$1');
	$subroutes->add('supervisor/updateSupervisor/(:num)', 'Supervisor::updateSupervisor/$1');
	$subroutes->add('supervisor/delete_multiple', 'Supervisor::delete_multiple');
	$subroutes->add('supervisor/exportCSV', 'Supervisor::exportCSV');
	$subroutes->add('supervisor/exportCSVAttendance', 'Supervisor::exportCSVAttendance');
	$subroutes->add('supervisor/checkUniquePhone', 'Supervisor::checkUniquePhone');
	$subroutes->add('supervisor/checkUniqueUsername', 'Supervisor::checkUniqueUsername');
	$subroutes->add('supervisor/checkUniquePanCard', 'Supervisor::checkUniquePanCard');
	$subroutes->add('supervisor/UpdatePublish', 'Supervisor::UpdatePublish');
	$subroutes->add('supervisor/checkUniqueAadharCard', 'Supervisor::checkUniqueAadharCard');
	$subroutes->add('supervisor-attendance', 'Supervisor::supervisor_attendance');
	
	
	// project
    $subroutes->add('project','Project::index');
    $subroutes->add('project/loadData/(:any)', 'Project::loadData/$1');
    $subroutes->add('project/addProject', 'Project::addProject');
    $subroutes->add('project/projectDetails/(:any)', 'Project::projectDetails/$1');
    $subroutes->add('project/addProjectInfo', 'Project::addProjectInfo');
    $subroutes->add('project/addEstimateItems', 'Project::addEstimateItems');
    $subroutes->add('project/CreateMilestone', 'Project::CreateMilestone');
    $subroutes->add('project/addLaborAssign', 'Project::addLaborAssign');
    $subroutes->add('project/countlaboramount', 'Project::countlaboramount');
    $subroutes->add('project/uploadPODocs', 'Project::uploadPODocs');
    $subroutes->add('project/addTask', 'Project::addTask');
    $subroutes->add('project/removeDragDocs', 'Project::removeDragDocs');
    $subroutes->add('project/addprojectPOdocs', 'Project::addprojectPOdocs');
    $subroutes->add('project/removeUploadedDocs', 'Project::removeUploadedDocs');
    $subroutes->add('project/addprojectEstimates', 'Project::addprojectEstimates');
    $subroutes->add('project/getGSTDropDown', 'Project::getGSTDropDown');
    $subroutes->add('project/addprojectMilestones', 'Project::addprojectMilestones');
    $subroutes->add('project/addProjectAssigns', 'Project::addProjectAssigns');
    $subroutes->add('project/addprojectTasks', 'Project::addprojectTasks');
    $subroutes->add('project/estimationList/(:any)', 'Project::estimationList/$1');
    $subroutes->add('project/newEstimation/(:any)', 'Project::newEstimation/$1');
    $subroutes->add('project/editMilestone/(:any)', 'Project::editMilestone/$1');
	$subroutes->add('project/UpdatePublish', 'Project::UpdatePublish');
	$subroutes->add('project/delete_multiple', 'Project::delete_multiple');
	$subroutes->add('project/milestoneList/(:any)', 'Project::milestoneList/$1');
	$subroutes->add('project/newMilestone/(:any)', 'Project::newMilestone/$1');
	$subroutes->add('project/addSupervisor', 'Project::addSupervisor');
	$subroutes->add('project/insertSupervisor', 'Project::insertSupervisor');
	$subroutes->add('project/exportCSV', 'Project::exportCSV');
	$subroutes->add('project/getInvoice/(:any)', 'Project::getInvoice/$1');

	

	

	

	

	

    
    // invoice
    $subroutes->add('invoice','Invoice::index');
    $subroutes->add('invoice/loadData/(:any)','Invoice::loadData/$1');
    $subroutes->add('invoice/addInvoice', 'Invoice::addInvoice');
    $subroutes->add('invoice/addEstimateItems', 'Invoice::addEstimateItems');
    $subroutes->add('invoice/editInvoice/(:any)', 'Invoice::editInvoice/$1');
    $subroutes->add('invoice/viewInvoice/(:any)', 'Invoice::viewInvoice/$1');
    $subroutes->add('invoice/pdfViewer/(:any)', 'Invoice::pdfViewer/$1');
    $subroutes->add('invoice/updateRecord/(:any)', 'Invoice::updateRecord/$1');
    $subroutes->add('invoice/getGSTDropDown', 'Invoice::getGSTDropDown');
    $subroutes->add('invoice/getCustomerProject', 'Invoice::getCustomerProject');
    $subroutes->add('invoice/searchhsn', 'Invoice::searchhsn');
    $subroutes->add('invoice/insertRecord', 'Invoice::insertRecord');
    $subroutes->add('invoice/removeInvoiceItem', 'Invoice::removeInvoiceItem');
    $subroutes->add('invoice/exportCSV', 'Invoice::exportCSV');
    $subroutes->add('invoice/getCustomerAddress', 'Invoice::getCustomerAddress');
    $subroutes->add('invoice/delete_multiple', 'Invoice::delete_multiple');
    $subroutes->add('invoice/addUnpaidInvoicelist', 'Invoice::addUnpaidInvoicelist');
    $subroutes->add('invoice/recordPayment/(:any)', 'Invoice::recordPayment/$1');
	
	// Company Profile 
	$subroutes->add('companyProfile', 'CompanyProfile::index');
	$subroutes->add('companyProfile/loadData/(:any)', 'CompanyProfile::loadData/$1');
	$subroutes->add('companyProfile/addCompanyProfile', 'CompanyProfile::addCompanyProfile');
    $subroutes->add('companyProfile/checkUniquePhone', 'CompanyProfile::checkUniquePhone');
	$subroutes->add('companyProfile/insertRecord', 'CompanyProfile::insertRecord');
	$subroutes->add('companyProfile/editCompanyProfile/(:any)', 'CompanyProfile::editCompanyProfile/$1');
	$subroutes->add('companyProfile/updateCompanyProfile/(:num)', 'CompanyProfile::updateCompanyProfile/$1');
	$subroutes->add('companyProfile/delete_multiple', 'CompanyProfile::delete_multiple');
	$subroutes->add('companyProfile/UpdatePublish', 'CompanyProfile::UpdatePublish');
	$subroutes->add('companyProfile/exportCSV', 'CompanyProfile::exportCSV');
	$subroutes->add('companyProfile/checkUniqueGST', 'CompanyProfile::checkUniqueGST');
	$subroutes->add('companyProfile/checkUniquePanCard', 'CompanyProfile::checkUniquePanCard');

	// Consultancy 
	$subroutes->add('consultancy', 'Consultancy::index');
	$subroutes->add('consultancy/loadData/(:any)', 'Consultancy::loadData/$1');
	$subroutes->add('consultancy/addConsultancy', 'Consultancy::addConsultancy');
    $subroutes->add('consultancy/checkUniquePhone', 'Consultancy::checkUniquePhone');
	$subroutes->add('consultancy/insertRecord', 'Consultancy::insertRecord');
	$subroutes->add('consultancy/editConsultancy/(:any)', 'Consultancy::editConsultancy/$1');
	$subroutes->add('consultancy/updateConsulancy/(:num)', 'Consultancy::updateConsulancy/$1');
	$subroutes->add('consultancy/delete_multiple', 'Consultancy::delete_multiple');
	$subroutes->add('consultancy/UpdatePublish', 'Consultancy::UpdatePublish');
	$subroutes->add('consultancy/exportCSV', 'Consultancy::exportCSV');
	$subroutes->add('consultancy/checkUniqueGST', 'Consultancy::checkUniqueGST');
	$subroutes->add('consultancy/checkUniquePanCard', 'Consultancy::checkUniquePanCard');

	// Material Type
    $subroutes->add('tools','Tools::index');
    $subroutes->add('tools/loadData/(:any)', 'Tools::loadData/$1');
    $subroutes->add('tools/insertRecord', 'Tools::insertRecord');
    $subroutes->add('tools/insertStockRecord', 'Tools::insertStockRecord');
    $subroutes->add('tools/transfor_tool', 'Tools::transfor_tool');
    $subroutes->add('tools/transferstock', 'Tools::transferstock');
    $subroutes->add('tools/editTools', 'Tools::editTools');
	$subroutes->add('tools/delete_multiple', 'Tools::delete_multiple');
	$subroutes->add('tools/UpdatePublish', 'Tools::UpdatePublish');
	$subroutes->add('tools/exportCSV', 'Tools::exportCSV');
    $subroutes->add('tools-stock-management','Tools::stock_managment');
    $subroutes->add('tools/loadDataStock/(:any)', 'Tools::loadDataStock/$1');
	
	// Rreceived Payment Type
    $subroutes->add('receivedPayment','ReceivedPayment::index');
    $subroutes->add('receivedPayment/loadData/(:any)','ReceivedPayment::loadData/$1');
    $subroutes->add('receivedPayment/addReceivedPayment', 'ReceivedPayment::addReceivedPayment');
    $subroutes->add('receivedPayment/addinvoicelist', 'ReceivedPayment::addinvoicelist');
    $subroutes->add('receivedPayment/getGSTDropDown', 'ReceivedPayment::getGSTDropDown');
    $subroutes->add('receivedPayment/tabview', 'ReceivedPayment::tabview');
    $subroutes->add('receivedPayment/insertRecord', 'ReceivedPayment::insertRecord');
    $subroutes->add('receivedPayment/delete_multiple', 'ReceivedPayment::delete_multiple');
    $subroutes->add('receivedPayment/getProjects', 'ReceivedPayment::getProjects');
    $subroutes->add('receivedPayment/paymentApplyToInvoice', 'ReceivedPayment::paymentApplyToInvoice');
    $subroutes->add('receivedPayment/applyToInvoice/(:any)', 'ReceivedPayment::applyToInvoice/$1');
    $subroutes->add('receivedPayment/getCompanyProfile', 'ReceivedPayment::getCompanyProfile');
    $subroutes->add('receivedPayment/editReceivedPayment/(:any)', 'ReceivedPayment::editReceivedPayment/$1');
    $subroutes->add('receivedPayment/updateRecord/(:num)', 'ReceivedPayment::updateRecord/$1');

	// Expense management
    $subroutes->add('expense','Expense::index');
    $subroutes->add('expense/loadData/(:any)', 'Expense::loadData/$1');
    $subroutes->add('bill/list_bill_expens/(:any)', 'Expense::list_bill_expens/$1');
    $subroutes->add('expense/addExpense', 'Expense::addExpense');
    $subroutes->add('expense/insertRecord', 'Expense::insertRecord');
    $subroutes->add('expense/editExpense/(:any)', 'Expense::editExpense/$1');
    $subroutes->add('expense/updateRecord/(:num)', 'Expense::updateRecord/$1');
	$subroutes->add('expense/delete_multiple', 'Expense::delete_multiple');
	$subroutes->add('expense/exportCSV', 'Expense::exportCSV');
	$subroutes->add('expense/getProjects', 'Expense::getProjects');
	$subroutes->add('expense/getLabor', 'Expense::getLabor');
	$subroutes->add('expense/getassignmentprice', 'Expense::getassignmentprice');

	// Challan management
    $subroutes->add('challan','Challan::index');
    $subroutes->add('challan/loadData/(:any)', 'Challan::loadData/$1');
    $subroutes->add('challan/addChallan', 'Challan::addChallan');
    $subroutes->add('challan/insertRecord', 'Challan::insertRecord');
    $subroutes->add('challan/editChallan/(:any)', 'Challan::editChallan/$1');
    $subroutes->add('challan/updateRecord/(:num)', 'Challan::updateRecord/$1');
	$subroutes->add('challan/delete_multiple', 'Challan::delete_multiple');
	$subroutes->add('challan/exportCSV', 'Challan::exportCSV');
	$subroutes->add('challan/getProjectItem', 'Challan::getProjectItem');

	// Bill Management
	$subroutes->add('bill','Bill::index');
    $subroutes->add('bill/loadData/(:any)', 'Bill::loadData/$1');
    $subroutes->add('bill/addBill', 'Bill::addBill');
    $subroutes->add('bill/insertRecord', 'Bill::insertRecord');
    $subroutes->add('bill/checkBillNumber', 'Bill::checkBillNumber');
    $subroutes->add('bill/editBill/(:any)', 'Bill::editBill/$1');
    $subroutes->add('bill/updateRecord/(:num)', 'Bill::updateRecord/$1');
	$subroutes->add('bill/delete_multiple', 'Bill::delete_multiple');
	$subroutes->add('bill/exportCSV', 'Bill::exportCSV');
	$subroutes->add('bill/getChallanList', 'Bill::getChallanList');
	$subroutes->add('bill/getBillList', 'Bill::getBillList');
	$subroutes->add('bill/getProjects', 'Bill::getProjects');
	$subroutes->add('bill/getAdvanceExpenseList', 'Bill::getAdvanceExpenseList');

	// Project's item management
	$subroutes->add('projectItem/list/(:any)','ProjectItem::list/$1');
	$subroutes->add('projectItem/editProjectItem','ProjectItem::editProjectItem');
    $subroutes->add('projectItem/loadData/(:any)', 'ProjectItem::loadData/$1');
    $subroutes->add('projectItem/insertRecord', 'ProjectItem::insertRecord');
    $subroutes->add('projectItem/updateProjectItem/(:num)', 'ProjectItem::updateProjectItem/$1');
	$subroutes->add('projectItem/delete_multiple', 'ProjectItem::delete_multiple');
	$subroutes->add('projectItem/exportCSV/(:any)', 'ProjectItem::exportCSV/$1');

	// Project 's Image management
	$subroutes->add('projectDoc/listImages/(:any)', 'ProjectDoc::listImages/$1');
	$subroutes->add('projectDoc/loadData/(:any)', 'ProjectDoc::loadData/$1');
	$subroutes->add('projectDoc/insertRecord', 'ProjectDoc::insertRecord');
	$subroutes->add('projectDoc/editProjectDoc', 'ProjectDoc::editProjectDoc');
    $subroutes->add('projectDoc/updateProjectDoc/(:any)', 'ProjectDoc::updateProjectDoc/$1');


	

	

	
});