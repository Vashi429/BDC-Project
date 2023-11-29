<form action="javascript:void(0)" enctype="multipart/form-data" method="POST" id="edit_project_item_form">
		<input type="hidden" class="form-control" id="fk_project" name="fk_project" value="<?= $fk_project ?>">
		<div class="form-group">
			<label for="fk_material">Material <span class="text-danger">*</span></label>
			<select class="form-control select2" id="fk_material" name="fk_material" required>
				<option value="">Select Material</option>
				<?php if(!empty($materials_edit)){
					foreach($materials_edit as $val){ ?>
						<option <?= ($data['fk_material'] == $val['int_glcode'])?"selected":"";?> value="<?= $val['int_glcode'] ?>"><?= $val['var_item']." (".$val['var_unit'].")" ?></option>	
					<?php }
				} ?>
			</select>
		</div>
		<div class="form-group">
			<label for="var_stock">Stock <span class="text-danger">*</span></label>
			<input class="form-control" id="var_stock" name="var_stock" required="" value="<?=$data['var_stock']?>" type="text"  oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
		</div>
		<button class="btn btn-primary pd-x-20 mg-t-10 submit_save" type="submit">Update Item</button>
	</form>
<script>
$(".select2").select2({
        width:'100%',
});
$('#edit_project_item_form').on('submit', function (e) {
	e.preventDefault();
	var fk_project = $("#fk_project").val();
	var id = '<?= $data['int_glcode'] ?>';
	$.ajax({
		type: 'post',
		url: sitepath+'/admin/projectItem/updateProjectItem/'+id,
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
				window.location.href = sitepath+"/admin/projectItem/list/"+ window.btoa(fk_project);
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
