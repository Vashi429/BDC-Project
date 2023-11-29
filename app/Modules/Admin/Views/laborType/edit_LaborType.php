<form class="">
  <div class="form-group">
    <label for="var_type<?=$data['int_glcode']?>">Labot Type<span class="text-danger">*</span></label>
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fas fa-list"></i>
        </div>
      </div>
      <input type="text" class="form-control" name="var_type" id="var_type<?=$data['int_glcode']?>" onfocusout="check_unique_type(<?=$data['int_glcode']?>)" value="<?php echo isset($data['var_type']) ? $data['var_type'] : ""; ?>">
    </div>
    <span id="emptyErrorUniqType<?=$data['int_glcode']?>" class="text-danger"></span>
  </div>
  <div class="form-group">
    <label for="var_skill_wages<?=$data['int_glcode']?>">skill Wages (Per Day)<span class="text-danger">*</span></label>
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fas fa-rupee-sign"></i>
        </div>
      </div>
      <input type="text" class="form-control" name="var_skill_wages" id="var_skill_wages<?=$data['int_glcode']?>" value="<?php echo isset($data['var_skill_wages']) ? $data['var_skill_wages'] : ""; ?>" oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
    </div>
    <span id="emptyErrorUniqWages<?=$data['int_glcode']?>" class="text-danger"></span>
  </div>

  <div class="form-group">
    <label for="var_unskill_wages<?=$data['int_glcode']?>">Un-skill Wages (Per Day)<span class="text-danger">*</span></label>
    <div class="input-group">
      <div class="input-group-prepend">
        <div class="input-group-text">
          <i class="fas fa-rupee-sign"></i>
        </div>
      </div>
      <input type="text" class="form-control" name="var_unskill_wages" id="var_unskill_wages<?=$data['int_glcode']?>" value="<?php echo isset($data['var_unskill_wages']) ? $data['var_unskill_wages'] : ""; ?>" oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
    </div>
    <span id="emptyErrorUniqWages<?=$data['int_glcode']?>" class="text-danger"></span>
  </div>
  <button type="button" onclick="insertLaborType(<?=$data['int_glcode']?>)" class="btn btn-primary m-t-15 waves-effect submit_save">Save Changes</button>
</form>