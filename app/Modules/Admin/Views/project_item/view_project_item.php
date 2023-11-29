
<section class="section">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/project">View Projects</a></li>
                    <li class="breadcrumb-item active"  aria-current="page">View Project Item</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Projects's Item Management</h4>
                    <div class="card-header-form">
                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/projectItem/exportCSV/<?= base64_encode($fk_project) ?>">Export Project's Item</a>
                        <a class="btn btn-primary text-right text-white" data-toggle="modal" data-target="#AddProjectItem">Add Project's Item</a>
                    </div>
                </div>
               
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="viewprojectItem">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="custom-checkbox custom-checkbox-table custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th><a href="javascript:void(0);" field="int_glcode" class="_sort">Id</a></th>
                                    <th><a href="javascript:void(0);" field="mm.var_item" class="_sort">Material Name</a></th>
                                    <th><a href="javascript:void(0);" field="mp.var_unit" class="_sort">Unit</a></th>
                                    <th><a href="javascript:void(0);" field="pt.var_stock" class="_sort">Total Stock<a></th>
                                    <th><a href="javascript:void(0);" field="pt.var_due_stock" class="_sort">Total Remaining Stock<a></th>
                                    <th class="text-center">Publish</th>
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
                                            <td class="text-center">
                                                <a href="javascript:void(0);" onclick="EditProjectItem(<?= $value['int_glcode'] ?>)">
                                                <?= $value['int_glcode'] ?>
                                                </a>
                                            </td>
                                            <td><?=$value['var_item']?></td>
                                            <td><?=$value['var_unit']?></td>
                                            <td><?=$value['var_stock']?></td>
                                            <td><?=$value['var_due_stock']?></td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);">
                                                    <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('customer', 'mst_customer', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="hfield" value="pt.int_glcode">
                <input type="hidden" name="hsort" value="desc">
                <input type="hidden" name="hpageno" value="0">
                <input type="hidden" name="module" id="module" value="projectItem">
                <div class="card-footer custom_header">
                    <div>
                        
                        <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
                    </div>
                   
                  </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Labot Type Modal with form -->
<div class="modal fade" id="AddProjectItem" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Add Project's Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="add_project_item_form">
            <input type="hidden" class="form-control" id="fk_project" name="fk_project" value="<?= $fk_project ?>">
            <div class="form-group">
                <label for="fk_material">Material <span class="text-danger">*</span></label>
                <select class="form-control select2" id="fk_material" name="fk_material" required>
                    <option value="">Select Material</option>
                    <?php if(!empty($materials)){
                        foreach($materials as $val){ ?>
                            <option value="<?= $val['int_glcode'] ?>"><?= $val['var_item']." (".$val['var_unit'].")" ?></option>	
                        <?php }
                    } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="var_stock">Stock <span class="text-danger">*</span></label>
                <input class="form-control" id="var_stock" name="var_stock" required="" type="text"  oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
            </div>
            <button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Item</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Labot Type Modal with form -->
<div class="modal fade" id="EditProjectItemModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Edit Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="editmodel">

      </div>
    </div>
  </div>
</div>
<script>
    $("#fk_material").select2({
        width:'100%',
    });
	$('#add_project_item_form').on('submit', function (e) {
		e.preventDefault();
		var fk_project = $("#fk_project").val();
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/projectItem/insertRecord',
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#add_project_item_form').css("opacity",".5");
			},
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/projectItem/list/"+ window.btoa(fk_project);
				}else{
					iziToast.error({
						title: 'Error',
						message: response.msg,
						position: 'topRight'
					});
					$('#add_project_item_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
	});

    function EditProjectItem(id) {
        var fk_project = $("#fk_project").val();
        $.ajax({
        url: sitepath + "/admin/projectItem/editProjectItem",
        type: 'POST',
        data: {int_glcode: id,fk_project:fk_project},
        success: function(responce) {
         
            $('#EditProjectItemModal').modal('show');
            $('#editmodel').html(responce);
        }
        });
    }
    $("#viewprojectItem").dataTable();
</script>



