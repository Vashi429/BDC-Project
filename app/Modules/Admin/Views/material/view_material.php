<section class="section">
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Material</li>
        </ol>
      </nav>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Material Management</h4>
          <div class="card-header-form">
            <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/material/exportCSV">Export Material</a>
            <button type="button" class="btn btn-primary text-right" data-toggle="modal" data-target="#AddMaterialModal">Add Material</button>
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
                  <th><a href="javascript:void(0);" field="var_item" class="_sort">Item Name</a></th>
                  <th>Units</th>
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
                    <tr>
                      <td class="p-0 text-center">
                        <div class="custom-checkbox custom-control">
                          <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input" id="checkbox-1">
                          <label for="checkbox-1" class="custom-control-label">&nbsp;</label>
                        </div>
                      </td>
                      <td><?= $value['var_item'] ?></td>
                      <td><?= $value['var_unit'] ?></td>
                      <td class="text-center">
                        <a href="javascript:void(0);">
                          <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('material', 'mst_material', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
                        </a>
                      </td>
                      <td class="text-center">
                        <a href="javascript:void(0);" onclick="EditMaterial(<?= $value['int_glcode'] ?>)" )>
                          <i class="fas fa-edit"></i>
                        </a>
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
        <input type="hidden" name="module" id="module" value="material">
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
<div class="modal fade" id="AddMaterialModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Add Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="">
          <div class="form-group">
            <label for="var_item">Item Name<span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-list"></i>
                </div>
              </div>
              <input type="text" class="form-control" name="var_item" id="var_item">
            </div>
            <span id="emptyErrorUniqItem" class="text-danger"></span>
          </div>
          <div class="form-group">
            <label for="var_unit">Unit<span class="text-danger">*</span></label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-balance-scale"></i>
                </div>
              </div>
              <input type="text" class="form-control" name="var_unit" id="var_unit">
            </div>
            <span id="emptyErrorUniqUnit" class="text-danger"></span>
          </div>
          <button type="button" onclick="insertMaterial()" class="btn btn-primary m-t-15 waves-effect submit_save">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Labot Type Modal with form -->
<div class="modal fade" id="EditMaterialModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
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
<script src="<?= base_url() ?>/public/assets/dist/js/material.js"></script>
<script>
  function insertMaterial(int_glcode = '') {
    var var_item = $('#var_item' + int_glcode).val();
    var var_unit = $('#var_unit' + int_glcode).val();
    if (var_item == '') {
      $('#emptyErrorUniqItem' + int_glcode).text('Please Enter Item Name.');
      $('#var_item' + int_glcode).focus();
    } else if (var_unit == '') {
      $('#emptyErrorUniqItem' + int_glcode).text('');
      $('#emptyErrorUniqUnit' + int_glcode).text('Please Enter Item Unit.');
      $('#var_unit' + int_glcode).focus();
    } else {
      $('#emptyErrorUniqItem' + int_glcode).text('');
      $('#emptyErrorUniqUnit' + int_glcode).text('');

      $.ajax({
        url: sitepath + "/admin/material/insertRecord",
        type: 'POST',
        data: {var_item: var_item,var_unit: var_unit,int_glcode: int_glcode},
        success: function(responce) {
          if (responce == 'Error') {
            iziToast.error({
              title: 'Oops...',
              message: 'Something went wrong!',
              position: 'topRight'
            });
          } else {
            $('#var_item').val('');
            $('#var_unit').val('');
            if(int_glcode>0){
              var txt_message = 'Material updated successfully.';
            }else{
              var txt_message = 'Material added successfully.';
            }
            iziToast.success({
              title: 'Success',
              message: txt_message,
              position: 'topRight'
            });
            location.reload();;
          }
        }
      });
    }
  }

  function EditMaterial(id) {
    $.ajax({
      url: sitepath + "/admin/material/editMaterial",
      type: 'POST',
      data: {int_glcode: id},
      success: function(responce) {
        $('#EditMaterialModal').modal('show');
        $('#editmodel').html(responce);
      }
    });
  }
</script>