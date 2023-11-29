
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
                    <h4>Projects's Image Management</h4>
                    <div class="card-header-form">
                        <a class="btn btn-primary text-right text-white" data-toggle="modal" data-target="#AddProjectItem">Add Project's Images</a>
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
                        <table class="table table-striped" id="viewprojectDoc">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><a href="javascript:void(0);" field="int_glcode" class="_sort">Id</a></th>
                                    <th><a href="javascript:void(0);" field="mm.var_item" class="_sort">Images</a></th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)){
                                    foreach ($data as $key => $value) {  ?>
                                        <tr id="<?= $value['int_glcode'] ?>">
                                            <td></td>
                                            <td><a href="javascript:void(0);" onclick="EditProjectDoc(<?= $value['int_glcode'] ?>)"><?= $value['int_glcode'] ?></a></td>
                                            <td><a href="<?= base_url().'/uploads/projectdoc/'.$value['var_image'] ?>" target="_blank"><img src="<?= base_url()?>/uploads/projectdoc/<?=$value['var_image']?>" height="50px"></a></td>
                                            <td><?=$value['dt_createdate']?></td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="hfield" value="mpd.int_glcode">
                <input type="hidden" name="hsort" value="desc">
                <input type="hidden" name="hpageno" value="0">
                <input type="hidden" name="module" id="module" value="projectDoc">
                <div class="card-footer custom_header">
                    <div>
                        <div class="page-limit page_pagination">
                            <label id="showing_"><?php echo 'Showing 1 to '.count($data).' of '.$total_data.' entries'; ?></label>
                        </div>
                    </div>
                    <div id="pagination">
                        <?php echo $pager_links;?>
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
        <h5 class="modal-title" id="formModal">Add Project's Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="add_project_images_form">
            <input type="hidden" class="form-control" id="fk_project" name="fk_project" value="<?= $fk_project ?>">
            <div class="form-group">
                <label for="var_image">Project Image <span class="text-danger">*</span></label>
                <input class="form-control" id="var_image" name="var_image" required="" type="file">
            </div>
            <button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Add Image</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Labot Type Modal with form -->
<!-- Edit Labot Type Modal with form -->
<div class="modal fade" id="EditProjectDocModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Edit Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="editmodel">

      </div>
    </div>
  </div>
</div>
<script src= "<?= base_url() ?>/public/assets/dist/js/projectdoc.js"></script>
<script>

	$('#add_project_images_form').on('submit', function (e) {
		e.preventDefault();
		var fk_project = $("#fk_project").val();
		$.ajax({
			type: 'post',
			url: sitepath+'/admin/projectDoc/insertRecord',
			data: new FormData(this),
            dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function(){
				$('.submit_save').attr("disabled","disabled");
				$('#add_project_images_form').css("opacity",".5");
			},
			success: function(response){
				if(response.status > 0){
					window.location.href = sitepath+"/admin/projectDoc/listImages/"+ window.btoa(fk_project);
				}else{
					iziToast.error({
						title: 'Error',
						message: response.msg,
						position: 'topRight'
					});
					$('#add_project_images_form').css("opacity","");
					$(".submit_save").removeAttr("disabled");
				}
			}
		});
	});

    function EditProjectDoc(id) {
        var fk_project = $("#fk_project").val();
        $.ajax({
        url: sitepath + "/admin/projectDoc/editProjectDoc",
        type: 'POST',
        data: {int_glcode: id,fk_project:fk_project},
        success: function(responce) {
            $('#EditProjectDocModal').modal('show');
            $('#editmodel').html(responce);
        }
        });
    }
</script>


