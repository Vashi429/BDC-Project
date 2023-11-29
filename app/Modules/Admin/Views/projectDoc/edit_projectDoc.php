<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="edit_project_image_form">
    <input type="hidden" class="form-control" id="fk_project" name="fk_project" value="<?= $fk_project ?>">
    <div class="form-group">
        <label for="var_image">Project Image <span class="text-danger">*</span></label>
        <input class="form-control" id="var_image" name="var_image" value="<?=$data['var_image']?>" type="file">
        <?php if($data['var_image']!=""){ ?>
            <a href="<?= base_url().'/uploads/projectdoc/'.$data['var_image'] ?>" target="_blank">Open Image</a>
        <?php } ?>
    </div>
    <input class="form-control" id="var_image_hidden" name="var_image_hidden" value="<?=$data['var_image']?>" required="" type="hidden">

    <button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Update Image</button>
</form>
<script>

$('#edit_project_image_form').on('submit', function (e) {
	e.preventDefault();
	var fk_project = $("#fk_project").val();
	var id = '<?= $data['int_glcode'] ?>';
	$.ajax({
		type: 'post',
		url: sitepath+'/admin/projectDoc/updateProjectDoc/'+id,
		data: new FormData(this),
		dataType: 'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function(){
			$('.submit_save').attr("disabled","disabled");
			$('#edit_project_item_form').css("opacity",".5");
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
				$('#edit_project_item_form').css("opacity","");
				$(".submit_save").removeAttr("disabled");
			}
		}
	});
});
</script>