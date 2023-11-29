<form class="">
  <div class="form-group">
    <label for="var_item<?=$data['int_glcode']?>">Item name<span class="text-danger">*</span></label>
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fas fa-list"></i>
        </div>
      </div>
      <input type="text" class="form-control" name="var_item" id="var_item<?=$data['int_glcode']?>" value="<?php echo isset($data['var_item']) ? $data['var_item'] : ""; ?>">
    </div>
    <span id="emptyErrorUniqItem<?=$data['int_glcode']?>" class="text-danger"></span>
  </div>
  <div class="form-group">
    <label for="var_unit<?=$data['int_glcode']?>">Unit<span class="text-danger">*</span></label>
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fas fa-balance-scale"></i>
        </div>
      </div>
      <input type="text" class="form-control" name="var_unit" id="var_unit<?=$data['int_glcode']?>" value="<?php echo isset($data['var_unit']) ? $data['var_unit'] : ""; ?>">
    </div>
    <span id="emptyErrorUniqUnit<?=$data['int_glcode']?>" class="text-danger"></span>
  </div>
  <button type="button" onclick="insertMaterial(<?=$data['int_glcode']?>)" class="btn btn-primary m-t-15 waves-effect submit_save">Save Changes</button>
</form>