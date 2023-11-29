<?php
namespace App\Modules\Admin\Controllers;
use App\Modules\Admin\Models\ProjectModel;
define('Views', 'App\Modules\Admin\Views\project');
use App\Modules\Admin\Models\CommonModel;

class Project extends BaseController{
	public $ProjectModel;
	function __construct(){
		$this->ProjectModel = new ProjectModel();
        $this->CommonModel = new CommonModel();

	}
	public function index(){
		$data['data'] = $this->ProjectModel->getData(0, ADMIN_PER_PAGE_ROWS);
		$data['total_data'] = $total_data =  $this->ProjectModel->total_records_count();
		$data['total_rows'] = $this->ProjectModel->filter_records_count();
		
		$page    = (int) ($this->request->getGet('page') ?? 1);
		$pager_links = $this->pager->makeLinks($page, ADMIN_PER_PAGE_ROWS, $total_data);
		$data['pager_links'] = $pager_links;
		$data['title'] = 'Project List';
		$data['view'] = Views . '\view_Project';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	public function loadData($rowno = 0){
		$search = $_GET['append'];
		$_field = $_GET['field'];
		$_sort = $_GET['sort'];
		$rowperpage = $_GET['entries'];
		$page = $rowno;
		if ($rowno != 0) {
			$rowno = ($rowno - 1) * $rowperpage;
		}
		$data['total_rows'] = $allcount = $this->ProjectModel->filter_records_count($search);
		$data['total_data'] =  $this->ProjectModel->total_records_count();
		$data['result'] = $this->ProjectModel->getData($rowno, $rowperpage, $search, $_field, $_sort);
		$data['pager_links'] = $this->pager->makeLinks($page, $rowperpage, $allcount);
		$data['row'] = $rowno;
		echo json_encode($data);
	}
	public function projectDetails($fk_project){

		$fk_project = base64_decode($fk_project);
		$data['data'] = $this->ProjectModel->projectDetails($fk_project);
		$data['title'] = 'Project List';
		$data['view'] = Views . '\project_Details';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);

	}
	public function addProject(){
		$where = array('chr_publish' => 'Y', 'chr_delete' => 'N');
		$data = [
			'title' => 'Add Project',
			'view' => Views . '\add_Project',
			'heading' => 'Welcome to BDC Project',
			'states' => $this->CommonModel->getStateList(),
			'country' => $this->CommonModel->getAllCountry(),
			'customer' => $this->CommonModel->getResultArray('mst_customer', 'int_glcode, var_displayname', $where, 'var_displayname', $short = 'asc'),
			'companyProfile' => $this->CommonModel->getResultArray('mst_company_profile', 'int_glcode, var_name', $where, 'var_name', 'asc'),
			'gst' => $this->CommonModel->getResultArray('mst_gst', 'int_glcode, var_percent', array(), 'int_glcode', $short = 'asc'),
			'autoProjectID' => 'P_' . $this->CommonModel->getUniqueAutoId('mst_project', 'var_Project_id'),
			'data' => array(),
		];
		return view('admin_tempate/template', $data);
	}
	public function addProjectInfo(){
		$projectId = $this->ProjectModel->addProjectInfo();
		echo $projectId;
		exit;
	}
	public function uploadPODocs(){
		$image_val = array();
		$html = '';
		$html_1 = '';
		if (isset($_FILES['plans']['name'][0])) {
			$destination = 'uploads/project/';
			if (!is_dir('uploads/project')) {
				mkdir('uploads/project', 0777, TRUE);
			}
			foreach ($_FILES['plans']['name'] as $keys => $values) {
				$fileName = uniqid() . '-' . $_FILES['plans']['name'][$keys];
				$fileName = preg_replace('/[^a-zA-Z0-9.\-]/s', '', $fileName);
				if (move_uploaded_file($_FILES['plans']['tmp_name'][$keys], $destination . $fileName)) {
					$file_type = $_FILES['plans']['type'][$keys];
					$parts = explode('.', $fileName);
					$file_extension = end($parts);
					$html .= '<div class="mb-3 col-lg-2 d-flex flex-wrap">';
					$html .= '<div class="image-container position-relative">';
					if ($file_extension == 'pdf') {
						$html .= '<a  class="thumbnail" href="' . base_url() . '/public/uploads/project/' . $fileName . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html .= '<iframe class="thumbnail" src="' . base_url() . '/public/uploads/project/' . $fileName . '#toolbar=0&navpanes=0&scrollbar=0" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html .= '</a>';
					} else if ($file_extension == 'doc' || $file_extension == 'docx') {
						$html .= '<a  class="thumbnail" href="' . base_url() . '/public/uploads/project/' . $fileName . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html .= '<iframe class="thumbnail" src="https://docs.google.com/gview?url=' . base_url() . '/public/uploads/project/' . $fileName . '&embedded=true" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html .= '</a>';
					} else {
						$html .= '<img class="thumbnail" src="' . base_url() . '/public/uploads/project/' . $fileName . '">';
					}
					$html .= '<div class="delete-new-plan-btn remove-uploaded-docs" data-attribute_name="' . $fileName . '"></div>';
					$html .= '</div>';
					$html .= '</div>';
					$html_1 .= '<div class="mb-3 col-lg-2 d-flex flex-wrap">';
					$html_1 .= '<div class="image-container position-relative">';
					if ($file_extension == 'pdf') {
						$html_1 .= '<a  class="thumbnail" href="' . base_url() . '/public/uploads/project/' . $fileName . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html_1 .= '<iframe class="thumbnail" src="' . base_url() . '/public/uploads/project/' . $fileName . '#toolbar=0&navpanes=0&scrollbar=0" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html_1 .= '</a>';
					} else if ($file_extension == 'doc' || $file_extension == 'docx') {
						$html_1 .= '<a  class="thumbnail" href="' . base_url() . '/public/uploads/project/' . $fileName . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html_1 .= '<iframe class="thumbnail" src="https://docs.google.com/gview?url=' . base_url() . '/public/uploads/project/' . $fileName . '&embedded=true" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html_1 .= '</a>';
					} else {
						$html_1 .= '<img class="thumbnail" src="' . base_url() . '/public/uploads/project/' . $fileName . '">';
					}
					$html_1 .= '</div>';
					$html_1 .= '</div>';
					$image_val[] = $fileName;
				}
			}
		}
		$data['image_val'] = $image_val;
		$data['html'] = $html;
		$data['html_1'] = $html_1;
		echo json_encode($data);
		exit;
	}
	public function removeDragDocs(){
		$delete_image_value = $_POST['delete_image_value'];
		$uploaded_plans = $_POST['uploaded_plans'];
		$new_uploaded_plans = explode(',', $uploaded_plans);
		$image_val = array();
		$html = '';
		$html_1 = '';
		if (!empty($new_uploaded_plans)) {
			foreach ($new_uploaded_plans as $key => $val) {
				if ($val != "") {
					if ($val == $delete_image_value) {
						if (file_exists(base_url() . '/public/uploads/project/' . $delete_image_value)) {
							unlink(base_url() . '/public/uploads/project/' . $delete_image_value);
						}
						continue;
					} else {
						$parts = explode('.', $val);
						$file_extension = end($parts);
						$html .= '<div class="mb-3 col-lg-2 d-flex flex-wrap">';
						$html .= '<div class="image-container position-relative">';
						if ($file_extension == 'pdf') {
							$html .= '<a  class="mt-5" href="' . base_url() . '/public/uploads/project/' . $val . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
							$html .= '<iframe src="' . base_url() . '/public/uploads/project/' . $val . '#toolbar=0&navpanes=0&scrollbar=0" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
							$html .= '</a>';
						} else if ($file_extension == 'doc' || $file_extension == 'docx') {
							$html .= '<a  class="thumbnail" href="' . base_url() . '/public/uploads/project/' . $val . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
							$html .= '<iframe class="thumbnail" src="https://docs.google.com/gview?url=' . base_url() . '/public/uploads/project/' . $val . '&embedded=true" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
							$html .= '</a>';
						} else {
							$html .= '<img class="thumbnail" src="' . base_url() . '/public/uploads/project/' . $val . '">';
						}
						$html .= '<div class="delete-new-plan-btn remove-uploaded-docs" data-attribute_name="' . $val . '"></div>';
						$html .= '</div>';
						$html .= '</div>';
						$html_1 .= '<div class="mb-3 col-lg-2 d-flex flex-wrap">';
						$html_1 .= '<div class="image-container position-relative">';
						if ($file_extension == 'pdf') {
							$html_1 .= '<a  class="mt-5" href="' . base_url() . '/public/uploads/project/' . $val . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
							$html_1 .= '<iframe src="' . base_url() . '/public/uploads/project/' . $val . '#toolbar=0&navpanes=0&scrollbar=0" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
							$html_1 .= '</a>';
						} else if ($file_extension == 'doc' || $file_extension == 'docx') {
							$html_1 .= '<a  class="thumbnail" href="' . base_url() . '/public/uploads/project/' . $val . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
							$html_1 .= '<iframe class="thumbnail" src="https://docs.google.com/gview?url=' . base_url() . '/public/uploads/project/' . $val . '&embedded=true" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
							$html_1 .= '</a>';
						} else {
							$html_1 .= '<img class="thumbnail" src="' . base_url() . '/public/uploads/project/' . $val . '">';
						}
						$html_1 .= '<div class="delete-new-plan-btn remove-uploaded-docs" data-attribute_name="' . $val . '"></div>';
						$html_1 .= '</div>';
						$html_1 .= '</div>';
						$image_val[] = $val;
					}
				}
			}
		}
		$data['image_val'] = implode(',', $image_val);
		$data['html'] = $html;
		$data['html_1'] = $html_1;
		echo json_encode($data);
		exit;
	}
	public function addprojectPOdocs(){
		$projectId = $this->ProjectModel->addprojectPOdocs();
		if ($projectId == 1) {
			$fk_project = $this->request->getVar('fk_project');
			$getAllPoDocs = $this->CommonModel->getResultArray('mst_other_documents', '*', array('fk_project' => $fk_project));
			$html = '';
			$html_1 = '';
			if (!empty($getAllPoDocs)) {
				$html .= '<div class="box-header with-border">';
				$html .= '<h4 class="box-title">Current Images</h4>';
				$html .= '</div>';
				$html .= '<div class ="row">';
				foreach ($getAllPoDocs as $val) {
					$img = $val['var_document'];
					$id = $val['int_glcode'];
					$parts = explode('.', $img);
					$file_extension = end($parts);
					$html .= '<div class="mb-3 col-lg-2 d-flex flex-wrap" id="removeImage' . $id . '">';
					$html .= '<div class="image-container">';
					if ($file_extension == 'pdf') {
						$html .= '<a  class="mt-5" href="' . base_url() . '/public/uploads/project/' . $img . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html .= '<iframe src="' . base_url() . '/public/uploads/project/' . $img . '#toolbar=0&navpanes=0&scrollbar=0" class="thumbnail" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html .= '</a>';
					} else if ($file_extension == 'doc' || $file_extension == 'docx') {
						$html .= '<a  class="mt-5" href="' . base_url() . '/public/uploads/project/' . $img . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html .= '<iframe class="thumbnail" src="https://docs.google.com/gview?url=' . base_url() . '/public/uploads/project/' . $img . '&embedded=true" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html .= '</a>';
					} else {
						$html .= '<img class="ml-4 mt-1 thumbnail" src="' . base_url() . '/public/uploads/project/' . $img . '">';
					}
					$html .= '<div class="delete-new-plan-btn delete-uploaded-docs" data-attribute_name="' . $img . '" data-attribute_id="' . $id . '"></div>';
					$html .= '</div>';
					$html .= '</div>';
				}
				$html .= '</div>';
			}
			if (!empty($getAllPoDocs)) {
				$html_1 .= '<div class="box-header with-border">';
				$html_1 .= '<h4 class="box-title">Current Images</h4>';
				$html_1 .= '</div>';
				$html_1 .= '<div class ="row">';
				foreach ($getAllPoDocs as $val) {
					$img = $val['var_document'];
					$id = $val['int_glcode'];
					$parts = explode('.', $img);
					$file_extension = end($parts);
					$html_1 .= '<div class="mb-3 col-lg-2 d-flex flex-wrap" id="removeImage' . $id . '">';
					$html_1 .= '<div class="image-container">';
					if ($file_extension == 'pdf') {
						$html_1 .= '<a  class="mt-5" href="' . base_url() . '/public/uploads/project/' . $img . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html_1 .= '<iframe src="' . base_url() . '/public/uploads/project/' . $img . '#toolbar=0&navpanes=0&scrollbar=0" class="thumbnail" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html_1 .= '</a>';
					} else if ($file_extension == 'doc' || $file_extension == 'docx') {
						$html_1 .= '<a  class="mt-5" href="' . base_url() . '/public/uploads/project/' . $img . '" target="_blank" style="z-index: 1;margin-left:10px; !important">';
						$html_1 .= '<iframe class="thumbnail" src="https://docs.google.com/gview?url=' . base_url() . '/public/uploads/project/' . $img . '&embedded=true" class="custom-uploads" style="position: relative;width: 100%;margin-top: 5px; height:100px"></iframe>';
						$html_1 .= '</a>';
					} else {
						$html_1 .= '<img class="ml-4 mt-1 thumbnail" src="' . base_url() . '/public/uploads/project/' . $img . '">';
					}
					$html_1 .= '</div>';
					$html_1 .= '</div>';
				}
				$html_1 .= '</div>';
			}
			$status = 200;
		} else {
			$status = 400;
			$html = '';
		}
		$data['status'] = $status;
		$data['html'] = $html;
		$data['html_1'] = $html_1;
		echo json_encode($data);
		exit;
	}
	public function getGSTDropDown(){
		$gstType = $this->CommonModel->getGstType($_POST['fk_customer'], $_POST['fk_profile']);
		$data['gstType'] = $gstType;
		echo json_encode($data);
	}
	public function addprojectEstimates(){
		$projectId = $this->ProjectModel->addprojectEstimates();
		if ($projectId > 0) {
			$data['total_amt'] = $this->request->getVar('var_final_amount');
			$data['status'] = 200;
		} else {
			$data['status'] = 400;
		}
		$data['is_existing'] = $this->request->getVar('is_existing');
		if($data['is_existing'] == 'Y'){
			$data['redirect'] = base_url().'/admin/project/estimationList/'.base64_encode($this->request->getVar('fk_project'));
		}
		echo json_encode($data);
		exit;
	}
	public function addprojectMilestones(){
		$projectId = $this->ProjectModel->addprojectMilestones();
		if ($projectId > 0) {
			$data['status'] = 200;
		} else {
			$data['status'] = 400;
		}
		echo json_encode($data);
		exit;
	}
	public function addprojectTasks(){
		$projectId = $this->ProjectModel->addprojectTasks();
		if ($projectId > 0) {
			$data['status'] = 200;
		} else {
			$data['status'] = 400;
		}
		echo json_encode($data);
		exit;
	}
	public function removeUploadedDocs(){
		$id = $_POST['delete_attribute_id'];
		$image = $_POST['delete_image_value'];
		if ($image != "") {
			if (file_exists(base_url() . '/public/uploads/project/' . $image)) {
				unlink(base_url() . '/public/uploads/project/' . $image);
			}
		}
		$this->CommonModel->deleteRow('mst_other_documents', array('int_glcode' => $id));
		echo 1;
		exit;
	}
	public function addEstimateItems(){
		$html = '';
		$fk_customer = $_POST['fk_customer'];
		$fk_profile = $_POST['fk_profile'];
		$no_row = $_POST['no_row'];
		$gstType = $this->CommonModel->getGstType($fk_customer, $fk_profile);
		$gstData = $this->CommonModel->getResultArray('mst_gst', '*', array(), 'int_glcode', 'ASC');
		$html .= '<tr class="addEstimates">
			<td>
					<input class="form-control" id="var_item' . $no_row . '" name="var_item[]" required="" type="text" placeholder="Item Name">
			</td>
			<td>
				<input class="form-control" id="var_quantity' . $no_row . '" name="var_quantity[]" onkeypress="return isNumberKey(event);" required="" type="text" value="0" maxlength="5" onfocusOut ="itemTotalAmount(' . $no_row . ')">
			</td>
			<td>
				<input id="var_rate' . $no_row . '" name="var_rate[]" onkeypress="return isNumberKeyWithDot(event);" required="" type="text" value="0" maxlength="15" onfocusOut ="itemTotalAmount(' . $no_row . ')">
			</td>
			<td>
				<select class="form-control" id="fk_tax' . $no_row . '" name="fk_tax[]" onchange ="itemTotalAmount(' . $no_row . ')">
					<option value="" disabled>Tax</option>
					<option value="0">No ' . $gstType . '</option>';
					if (!empty($gstData)) {
						foreach ($gstData as $value) {
							if ($value['var_percent'] == 0) {
								continue;
							}
							$html .= '<option value="' . $value['int_glcode'] . '" data-val="' . $value['var_percent'] . '">' . $value['var_percent'] . '% ' . $gstType . '</option>';
						}
					}
					$html .= '</select>
				<input type="hidden" value="0" id="var_tax' . $no_row . '" name="var_tax[]">
			</td>
			<td>
				<input type="hidden" name="var_total[]" value="0" id="var_row_total' . $no_row . '">
				<span class="total-amount" id="var_row_total_txt' . $no_row . '">'.CURRENCY_ICON.'0.00</span>
			</td>
			<td>
				<a href="javascript:void(0);" class="remove-invoice" id="btnRemoveEstimate"><i class="fas fa-minus m-0"></i></a>
			</td>
		</tr>';
		echo $html;
	}
	public function CreateMilestone(){
		$milestone_no = $_POST['milestone_no'];
		$total_amount = $_POST['total_amount'];
		$advance_total_amount = $_POST['advance_total_amount'];
		$advance_pay_per = number_format(($advance_total_amount * 100) / $total_amount, 2);
		$var_date = $_POST['var_date'];
		$end_date = $_POST['end_date'];
		helper('date');
		$startDateTime = date_create($var_date);
		$endDateTime = date_create($end_date);
		$interval = $startDateTime->diff($endDateTime);
		$days = $interval->days;
		$milestoneDays = (int)($days / $milestone_no);
		if ($advance_total_amount > 0) {
			$total_amount = $total_amount - $advance_total_amount;
		}
		$final = number_format($total_amount / $milestone_no, 2, '.', '');
		$percent = number_format((100 - $advance_pay_per) / $milestone_no, 2, '.', '');
		$html = '';
		$dayes = 0;
		$totalAmount = 0;
		for ($i = 0; $i < $milestone_no; $i++) {
			$dayes += $milestoneDays;
			$new_date = clone $startDateTime;
			$new_date->modify('+' . $dayes . ' days');
			$newEndDate = $new_date->format('Y-m-d');
			$id = $i + 1;
			$advance_pay_per += $percent;
			$totalAmount += $final;
			$html .= '<tr>
				<td>
					<textarea class="form-control" id="var_description' . $id . '" name="var_description[]" placeholder="Enter Discription"></textarea>
				</td>
				<td>
					<input id="var_percentage' . $id . '" name="var_percentage[]" required="" type="text" value="' . $percent . '" onblur="changeMilestoneAmt(' . $id . ')">
					<input type="hidden" id="hidden_var_percentage' . $id . '" value="' . $percent . '" name="hidden_var_percentage[]">
				</td>
				<td>
					<input id="var_payment' . $id . '" name="var_payment[]" required="" type="text" value="' . $final . '" onblur="changeMilestoneAmt(' . $id . ')">
					<input type="hidden" id="hidden_var_payment' . $id . '" value="' . $final . '" name="hidden_var_payment[]">
				</td>
				<td>
					<input id="var_date' . $id . '" name="var_date[]" required="" type="date" class="datepicker" value="' . $newEndDate . '">
				</td>
			</tr>';
		}
		$html .= '<tr>
			<th><b>Total Estimate<b></th>
			<th>
				<input id="total_var_percentage" name ="total_var_percentage" required="" readonly type="text" value="' . number_format($advance_pay_per, 2, '.', '') . '">
			</th>
			<th>
				<input id="total_var_payment" name ="total_var_payment" required="" readonly type="text" value="' . number_format(($totalAmount + $advance_total_amount), 2, '.', '') . '">
			</th>
		</tr>';
		echo $html;
	}
	public function addTask(){
		$html = '';
		$html .= '<div class="row addtaskmore">
	                <div class="col-12 d-flex justify-content-between">
	                    <h6 class="mt-2">Task ' . $_POST["no_row_T"] . '</h6> <a href="javascript:void(0);" class="remove-invoice m-0" id="btntaskremove"><i class="fas fa-minus m-0"></i></a>
	                </div>
    				<div class="col-12 col-lg-12 col-md-6">
    					<div class="form-group">
    						<label for="var_title">Task Title <span class="text-danger">*</span></label>
    						<input class="form-control" id="var_title' . $_POST["no_row_T"] . '" name="var_title[]" required="" type="text">
    					</div>
    				</div>
    				<div class="col-12 col-lg-12 col-md-6">
    					<div class="form-group">
    						<label for="var_title">Task Details <span class="text-danger">*</span></label>
    						<textarea class="form-control summernote" id="var_details' . $_POST["no_row_T"] . '" name="var_details[]" required></textarea>
    					</div>
    				</div>
    			</div>';
		echo $html;
	}
	public function estimationList($id){
		$fk_project = base64_decode($id);
		$data['data'] = $this->CommonModel->getResultArray('mst_estimate', '*', array('fk_project' => $fk_project,'chr_delete'=>'N'));
		$data['title'] = 'Estimation List List';
		$data['fk_project'] = $fk_project;
		$data['view'] = Views . '\estimation_list';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}
	public function newEstimation($fk_project, $fk_estimation = ''){
		$fk_project = base64_decode($fk_project);
		$data['data'] = $projectData =  $this->CommonModel->getRowArray('mst_project', 'int_glcode, var_project, fk_customer, fk_profile', array('int_glcode' => $fk_project));
		if (!empty($projectData)) {
			$gstType = $this->CommonModel->getGstType($projectData['fk_customer'], $projectData['fk_profile']);
		} else {
			$gstType = 'GST';
		}
		$data['gstType'] = $gstType;
		if ($fk_estimation == '') {
			$data['title'] = 'Add Estimation';
		} else {
			$data['title'] = 'Edit Estimation';
			$fk_estimation = base64_decode($fk_estimation);
			$data['fk_estimation'] = $fk_estimation;
			$data['estimationData'] = $this->ProjectModel->getEstimationData($fk_estimation);
		}
		$data['gst'] = $this->CommonModel->getResultArray('mst_gst', '*', array(), 'int_glcode', 'ASC');
		$data['view'] = Views . '\add_newEstimation';
		$data['heading'] = 'Welcome to BDC Project';
		
		return view('admin_tempate/template', $data);
	}
	
	public function exportCSV(){
		$estimationData = $this->ProjectModel->getData('','');
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"ProjectList" . time() . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen('php://output', 'w');
		fputcsv($handle, array("No","Id", "Project Id", "Name", "Customer", "Supervisor", "Duration", "Total Estimation (₹)","Total Milstone (₹)","Publish"));
		$cnt = 1;
		if (!empty($estimationData)) {
			foreach ($estimationData as $val) {
				$data = array(
					$cnt++,
					$val['int_glcode'],
					$val['var_Project_id'],
					$val['var_project'],
					$val['var_customer'],
					($val['var_supervisor'])?$val['var_supervisor']:"N/A",
					$val['duration'],
					($val['total_estimation_amount'] != '')?$val['total_estimation_amount']:"0.00",
					($val['total_milestone_amount'] != '')?$val['total_milestone_amount']:"0.00",
					($val['chr_publish'] == 'Y')?"Publish":"Un-Publish",
				);
				fputcsv($handle, $data);
			}
		}
		fclose($handle);
		exit;
	}

	public function UpdatePublish(){
		$this->CommonModel->updatedisplay();
	}
	public function delete_multiple(){
		$result = $this->CommonModel->delete_multiple('mst_estimate');
		echo $result;
	}
	public function milestoneList($id){
		$fk_project = base64_decode($id);
		$data['data'] = $this->CommonModel->getResultArray('mst_milestone', '*', array('fk_project' => $fk_project));
		$data['project_data'] = $projectData =  $this->CommonModel->getRowArray('mst_project', 'int_glcode, var_project, fk_customer, fk_profile', array('int_glcode' => $fk_project));
		$data['title'] = 'Milestone List List';
		$data['fk_project'] = $fk_project;
		$data['view'] = Views . '\milestone_list';
		$data['heading'] = 'Welcome to BDC Project';
	
		return view('admin_tempate/template', $data);
	}


	public function newMilestone($fk_project){
		$fk_project = base64_decode($fk_project);
		$data['total_estimation_amount'] = $this->CommonModel->getValById('mst_estimate', 'sum(var_total_amount)', array('fk_project' => $fk_project));
		$data['total_milestone_amount'] = $this->CommonModel->getValById('mst_milestone', 'sum(var_payment)', array('fk_project' => $fk_project));
		$data['data'] =  $this->CommonModel->getRowArray('mst_project', 'int_glcode, var_project, fk_customer, fk_profile, end_date', array('int_glcode' => $fk_project));
		$data['title'] = 'Add Milestone';
		$data['view'] = Views . '\add_newMilestone';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	}


	public function editMilestone($fk_project, $fk_milestone){
		$fk_project = base64_decode($fk_project);
		$data['total_estimation_amount'] = $this->CommonModel->getValById('mst_estimate', 'sum(var_total_amount)', array('fk_project' => $fk_project));
		$data['total_milestone_amount'] = $this->CommonModel->getValById('mst_milestone', 'sum(var_payment)', array('fk_project' => $fk_project));
		$data['data'] =  $this->CommonModel->getRowArray('mst_project', 'int_glcode, var_project, fk_customer, fk_profile, end_date', array('int_glcode' => $fk_project));
		$fk_milestone =  base64_decode($fk_milestone);
		$data['fk_milestone'] = $fk_milestone;
		$data['title'] = 'Edit Milestone';
		$data['milestonedata'] = $this->CommonModel->getRowArray('mst_milestone', '*', array('int_glcode' => $fk_milestone));
		$data['view'] = Views . '\edit_milestone';
		$data['heading'] = 'Welcome to BDC Project';
		return view('admin_tempate/template', $data);
	
	
	}

	public function addSupervisor()
	{
		$where = array('chr_publish' => 'Y', 'chr_delete' => 'N');
		$data['supervisor_id'] = $_POST['supervisor_id'];
		$data['fk_project'] = $_POST['fk_project'];

		$data['data'] = $this->CommonModel->getResultArray('mst_supervisor', 'int_glcode, var_name', $where, $order_by = 'var_name', $short = 'asc');

		return view(Views . '\add_supervisor', $data);
	}

	public function insertSupervisor(){
		
		$id = ($_POST['int_glcode'])?? "";
		$data = array(
            "fk_supervisor"=> $_POST['fk_supervisor'],
        );
		$this->CommonModel->updateRows('mst_project',$data, array('int_glcode'=>$this->request->getVar('fk_project')));
		echo 'Success';
		exit;
	}
}
