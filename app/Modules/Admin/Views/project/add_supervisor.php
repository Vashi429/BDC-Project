<form class="">
    <input type="hidden" name="fk_project" id="fk_project" value="<?=$fk_project;?>">
    <input type="hidden" name="supervisor_id" id="supervisor_id" value="<?=$supervisor_id;?>">

  <div class="form-group">
    <label for="var_type">Supervisor Name</label>
    <select class="form-control  fk_supervisor" name="fk_supervisor" id="fk_supervisor">
        <option value="">Please select supervisor</option>
        <?php foreach($data as $key => $value){ ?>
            <option value="<?=$value['int_glcode']?>" <?=($supervisor_id == $value['int_glcode'])?"selected":"";?>><?=$value['var_name']?></option>
       <?php } ?>
    </select>
    <span id="emptyErrorUniqType" class="text-danger"></span>
  </div>
  <button type="button" onclick="insertSupervisor(<?=$supervisor_id;?>)" class="btn btn-primary m-t-15 waves-effect submit_save">Save Changes</button>
</form>
