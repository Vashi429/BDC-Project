<section class="section">
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Labor Type</li>
        </ol>
      </nav>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Labor Type Management</h4>
          <div class="card-header-form">
            <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/laborType/exportCSV">Export Labour Type</a>
            <button type="button" class="btn btn-primary text-right" data-toggle="modal" data-target="#AddLabotTypeModal">Add Labor Type</button>
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
            <table class="table table-striped" id="viewlabor">
              <thead>
                <tr>
                  <th class="text-center">
                    <div class="custom-checkbox custom-checkbox-table custom-control">
                      <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                      <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                    </div>
                  </th>
                  <th><a href="javascript:void(0);" field="var_type" class="_sort">Labor Type</a></th>
                  <th>Skill Wages (Per Day)</th>
                  <th>Un-Skill Wages (Per Day)</th>

                  <th class="text-center">Publish</th>
                  <th class="text-center">Option</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($data)) {
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
                      <td><?= $value['var_type'] ?></td>
                      <td><?= $value['var_skill_wages'] ?></td>
                      <td><?= $value['var_unskill_wages'] ?></td>

                      <td class="text-center">
                        <a href="javascript:void(0);">
                          <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('laborType', 'mst_labor_type', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
                        </a>
                      </td>
                      <td class="text-center">
                        <a href="javascript:void(0);" onclick="EditLaborType(<?= $value['int_glcode'] ?>)"><i class="fas fa-edit"></i></a>
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
        <input type="hidden" name="module" id="module" value="laborType">
        <div class="card-footer custom_header">
          <div>
            <div class="page-limit page_pagination">
              <label id="showing_"><?php echo 'Showing 1 to ' . count($data) . ' of ' . $total_data . ' entries'; ?></label>
            </div>
            <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
          </div>
          <div id="pagination">
          <?php echo $pager_links; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Add Labot Type Modal with form -->
<div class="modal fade" id="AddLabotTypeModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Add Labor Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="">
          <div class="form-group">
            <label for="var_type">Labor Type<span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-list"></i>
                </div>
              </div>
              <input type="text" class="form-control" name="var_type" id="var_type" onfocusout="check_unique_type()">
            </div>
            <span id="emptyErrorUniqType" class="text-danger"></span>
          </div>
          <div class="form-group">
            <label for="var_wages">Skill Wages (Per Day)<span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-rupee-sign"></i>
                </div>
              </div>
              <input type="text" class="form-control" name="var_skill_wages" id="var_skill_wages" oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
            </div>
            <span id="emptyErrorUniqWages" class="text-danger"></span>
          </div>

          <div class="form-group">
            <label for="var_wages">Un-Skill Wages (Per Day)<span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-rupee-sign"></i>
                </div>
              </div>
              <input type="text" class="form-control" name="var_unskill_wages" id="var_unskill_wages" oninput="return isNumberKey(event)"  pattern="[0-9]*" inputmode="numeric">
            </div>
            <span id="emptyErrorUniqWages" class="text-danger"></span>
          </div>
          <button type="button" onclick="insertLaborType()" class="btn btn-primary m-t-15 waves-effect submit_save">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Labot Type Modal with form -->
<div class="modal fade" id="EditLabotTypeModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Edit Labor Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="editmodel">

      </div>
    </div>
  </div>
</div>
<script src="<?= base_url() ?>/public/assets/dist/js/laborType.js"></script>
<script>
  function check_unique_type(int_glcode = '') {
    var var_type = $('#var_type' + int_glcode).val();
    $.ajax({
      url: sitepath + "/admin/laborType/checkUniqueLaborType",
      type: 'POST',
      data: {var_type: var_type,int_glcode: int_glcode},
      success: function(responce) {
        if (responce == 1) {
          $('#emptyErrorUniqType' + int_glcode).text('Please Enter Unique Labor Type.');
          $('#var_type' + int_glcode).focus();
          $(".submit_save").attr("disabled", true);
          return false;
        } else {
          $('#emptyErrorUniqType' + int_glcode).text('');
          $(".submit_save").attr("disabled", false);
          return true;
        }
      }
    });
  }

  function insertLaborType(int_glcode = '') {
    var var_type = $('#var_type' + int_glcode).val();
    var var_skill_wages = $('#var_skill_wages' + int_glcode).val();
    var var_unskill_wages = $('#var_unskill_wages' + int_glcode).val();

    if (var_type == '') {
      $('#emptyErrorUniqType' + int_glcode).text('Please Enter Labor Type.');
      $('#var_type' + int_glcode).focus();
    } else if(check_unique_type(int_glcode)==0){
      return false;
    } else {
      $('#emptyErrorUniqType' + int_glcode).text('');
      $('#emptyErrorUniqWages' + int_glcode).text('');
      $.ajax({
        url: sitepath + "/admin/laborType/insertRecord",
        type: 'POST',
        data: {var_type: var_type,var_skill_wages: var_skill_wages,var_unskill_wages:var_unskill_wages,int_glcode: int_glcode},
        beforeSend: function(){
          $('.submit_save').attr("disabled","disabled");
        },
        success: function(responce) {
          if (responce == 'Success') {
            $('#var_type').val('');
            $('#var_skill_wages').val('');
            $('#var_unskill_wages').val('');

            if(int_glcode>0){
              var message = 'Labor Type updated successfully.';
            }else{
              var message = 'Labor Type added successfully.';
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

  function EditLaborType(id) {
    $.ajax({
      url: sitepath + "/admin/laborType/editLaborType",
      type: 'POST',
      data: {int_glcode: id},
      success: function(responce) {
        $('#EditLabotTypeModal').modal('show');
        $('#editmodel').html(responce);
      }
    });
  }
</script>