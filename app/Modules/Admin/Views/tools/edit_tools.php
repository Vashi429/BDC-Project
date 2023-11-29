<form class="">

  <div class="row">

    <div class="col-12 col-lg-12 col-md-12">

      <div class="form-group">

        <label for="var_tool_name<?=$data['int_glcode']?>">Tool name <span class="text-danger">*</span></label>

        <div class="input-group">

          <input type="text" class="form-control" name="var_tool_name" id="var_tool_name<?=$data['int_glcode']?>" value="<?php echo isset($data['var_tool_name']) ? $data['var_tool_name'] : ""; ?>">

        </div>

        <span id="emptyErrorUniqItem<?=$data['int_glcode']?>" class="text-danger"></span>

      </div>

    </div>

    <div class="col-12 col-lg-6 col-md-6">

      <div class="form-group">

        <label for="var_width<?=$data['int_glcode']?>">Width</label>

        <div class="input-group">

          <input type="text" class="form-control" name="var_width<?=$data['int_glcode']?>" id="var_width<?=$data['int_glcode']?>"  value="<?php echo isset($data['var_width']) ? $data['var_width'] : ""; ?>">

        </div>

      </div>

    </div>

    <div class="col-12 col-lg-6 col-md-6">

      <div class="form-group">

        <label for="var_height<?=$data['int_glcode']?>">Height</label>

        <div class="input-group">

          <input type="text" class="form-control" name="var_height<?=$data['int_glcode']?>" id="var_height<?=$data['int_glcode']?>"  value="<?php echo isset($data['var_height']) ? $data['var_height'] : ""; ?>">

        </div>

      </div>

    </div>

    <div class="col-12 col-lg-6 col-md-6">

      <div class="form-group">

        <label for="var_size<?=$data['int_glcode']?>">Size</label>

        <div class="input-group">

          <input type="text" class="form-control" name="var_size" id="var_size<?=$data['int_glcode']?>" value="<?php echo isset($data['var_size']) ? $data['var_size'] : ""; ?>">

        </div>

      </div>

    </div>

    <div class="col-12 col-lg-6 col-md-6">

      <div class="form-group">

        <label for="var_stock<?=$data['int_glcode']?>">Stock <span class="text-danger">*</span></label>

        <div class="input-group">

          <input type="text" class="form-control" name="var_stock<?=$data['int_glcode']?>" id="var_stock<?=$data['int_glcode']?>"  value="<?php echo isset($data['var_stock']) ? $data['var_stock'] : ""; ?>" oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">

        </div>

        <span id="emptyErrorStock<?=$data['int_glcode']?>" class="text-danger"></span>

      </div>

    </div>

  </div>

  <button type="button" onclick="insertTool(<?=$data['int_glcode']?>)" class="btn btn-primary m-t-15 waves-effect submit_save">Save Changes</button>

</form>