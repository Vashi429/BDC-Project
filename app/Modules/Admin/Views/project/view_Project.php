<section class="section">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"  aria-current="page">View Project</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Project Management</h4>
                    <div class="card-header-form">
                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/project/projectDetails">View Project</a>
                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/project/exportCSV">Export Project</a>
                        <a class="btn btn-primary text-right" href="<?= base_url() ?>/admin/project/addProject">Add Project</a>
                    </div>
                </div>
                <div class="card-header custom_header">
                    <div class="dataTables_length">
                        <label>
                            Show:
                            <select name="dp_entries" class="form-control">
                                <option value="10" selected="">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            entries
                        </label>
                    </div>
                    <div class="card-header-form">
                        <form>
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Search" id="search" name="search">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" id="searchbtn"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped" id="viewProject">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="custom-checkbox custom-checkbox-table custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th><a href="javascript:void(0);" field="mp.int_glcode" class="_sort">Id</a></th>
                                    <th><a href="javascript:void(0);" field="mp.var_Project_id" class="_sort">Project Id</a></th>

                                    <th><a href="javascript:void(0);" field="mp.var_project" class="_sort">Name</a></th>
                                    <th><a href="javascript:void(0);" field="mc.var_name" class="_sort">Customer</a></th>
                                    <th><a href="javascript:void(0);" field="ms.var_name" class="_sort">Supervisor</a></th>
                                    <th><a href="javascript:void(0);" field="ms.duration" class="_sort">Duration</a></th>
                                    <th><a href="javascript:void(0);" field="ms.duration" class="_sort">Total Estimation (<?=CURRENCY_ICON?>) </a></th>
                                    <th><a href="javascript:void(0);" field="ms.duration" class="_sort">Total Milstone (<?=CURRENCY_ICON?>)</a></th>
                                    <th class="text-center">Publish</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)){
                                    foreach ($data as $key => $value) { 
                                        $tickimg = ($value['chr_publish'] == 'Y') ? "tick.png" : "tick_cross.png";
                                        if ($value['chr_publish'] == 'Y') {
                                            $title = "Hide me";
                                            $update_val = 'N';
                                        } else {
                                            $title = "Display me";
                                            $update_val = 'Y';
                                        } ?>
                                        <tr id="<?= $value['int_glcode'] ?>">
                                            <td class="p-0 text-center">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" name="ids[]" value="<?= $value['int_glcode'] ?>" id="checkbox-<?= $value['int_glcode'] ?>">
                                                    <label for="checkbox-<?= $value['int_glcode'] ?>" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </td>  
                                            <td><a href="<?= base_url() ?>/admin/project/projectDetails/<?= base64_encode($value['int_glcode']) ?>"><?php echo $value['int_glcode'] ?></a></td>
                                            <td><?php echo $value['var_Project_id'] ?></td>
                                            <td><?php echo $value['var_project'] ?></td>

                                            <td><?php echo $value['var_customer'] ?></td>
                                            <?php if($value['var_supervisor'] != ""){ ?>
                                                <td><a href="javascript:void(0);" onclick="addSupervisor(<?= $value['int_glcode'].','.$value['fk_supervisor'];?>)"><?php echo $value['var_supervisor']; ?></a></td>
                                           <?php }else{ ?>
                                            <td><a href="javascript:void(0);" onclick="addSupervisor(<?=$value['int_glcode'];?>)" class="btn btn-primary">Add</a></td>
                                            <?php } ?>

                                            
                                            <td><?php echo $value['duration'] ?></td>
                                            <td><?php echo (!empty($value['total_estimation_amount']))? CURRENCY_ICON.$value['total_estimation_amount']:CURRENCY_ICON."0.00"; ?></td>
                                            <td><?php echo ($value['total_milestone_amount'] != '')?CURRENCY_ICON.$value['total_milestone_amount'] : CURRENCY_ICON."0.00"; ?></td>

                                            
                                            <td class="text-center">
                                                <a href="javascript:void(0);">
                                                    <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('project', 'mst_project', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
                                                </a>
                                            </td>
                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" title="Option"></button>
                                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a  class="dropdown-item has-icon" href="<?= base_url() ?>/admin/project/POList/<?php echo base64_encode($value['int_glcode']);?>"><i class="fa fa-eye"></i> View PO List</a>
                                                        
                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/project/estimationList/<?php echo base64_encode($value['int_glcode']);?>"><i class="fa fa-eye"></i> View Estimations</a>

                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/project/milestoneList/<?php echo base64_encode($value['int_glcode']);?>"><i class="fa fa-eye"></i> View Milestones</a>

                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/projectItem/list/<?php echo base64_encode($value['int_glcode']);?>"><i class="fa fa-eye"></i> View items</a>
                                                        <a class="dropdown-item has-icon" href="<?= base_url() ?>/admin/projectDoc/listImages/<?php echo base64_encode($value['int_glcode']);?>"><i class="fa fa-eye"></i> Project Images</a>

                                                        
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="hfield" value="int_glcode">
                <input type="hidden" name="hsort" value="desc">
                <input type="hidden" name="hpageno" value="0">
                <input type="hidden" name="module" id="module" value="project">
                <div class="card-footer custom_header">
                    <div>
                        <div class="page-limit page_pagination">
                            <label id="showing_"><?php echo 'Showing 1 to '.count($data).' of '.$total_data.' entries'; ?></label>
                        </div>
                        <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
                    </div>
                    <div id="pagination">
                        <?php echo $pager_links;?>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Labot Type Modal with form -->
<div class="modal fade" id="AddSupervisor" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Add Supervisor Name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="Addmodel">

      </div>
    </div>
  </div>
</div>
<script>
    var siteurl = '<?= base_url() ?>';
    var perPage = '<?= ADMIN_PER_PAGE_ROWS ?>';
</script>  


<script src= "<?= base_url() ?>/public/assets/dist/js/project.js"></script>
<script>
  function addSupervisor(fk_project,fk_supervisor = '') {
   
    $.ajax({
      url: sitepath + "/admin/project/addSupervisor",
      type: 'POST',
      data: {fk_project: fk_project,supervisor_id:fk_supervisor},
      success: function(responce) {
        console.log(responce);
        $('#AddSupervisor').modal('show');
        $('#Addmodel').html(responce);
        if(fk_supervisor != '')
        {
          $('#formModal').text('Edit Supervisor Name');
        }else{
          $('#formModal').text('Add Supervisor Name');

        }
        $(".fk_supervisor").select2({
            width: '100%'
      });
      }
    });
  }
  function insertSupervisor(int_glcode = '') {
    var fk_supervisor = $('#fk_supervisor').val();
    var fk_project = $('#fk_project').val();
    $('#emptyErrorUniqType').text('');
    if (fk_supervisor == '') {
      $('#emptyErrorUniqType').text('Please select supervisor.');
      $('#fk_supervisor').focus();
      return false;
    } else {
      $.ajax({
        url: sitepath + "/admin/project/insertSupervisor",
        type: 'POST',
        data: {fk_supervisor: fk_supervisor,fk_project:fk_project,int_glcode:int_glcode},
        beforeSend: function(){
          $('.submit_save').attr("disabled","disabled");
        },
        success: function(responce) {
          if (responce == 'Success') 
          {
            $('#emptyErrorUniqType').text('');
          if(int_glcode>0){
              var message = 'Spervisor updated successfully.';
            }else{
              var message = 'Spervisor added successfully.';
            }
            iziToast.success({
              title: 'Success',
              message: message,
              position: 'topRight'
            });
            location.reload();;
          }else{
            iziToast.error({
              title: 'Error',
              message: responce,
              position: 'topRight'
            });
          }
        }
      });
    }
  }
</script>
