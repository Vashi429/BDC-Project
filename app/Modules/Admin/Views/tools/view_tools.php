<section class="section">
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">View Tools</li>
        </ol>
      </nav>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Tools & Equipment Management</h4>
          <div class="card-header-form">
            <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/tools/exportCSV">Export Tools</a>
            <button type="button" class="btn btn-primary text-right" data-toggle="modal" data-target="#AddToolsModal">Add Tools</button>
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
            <table class="table table-striped" id="viewTools">
              <thead>
                <tr>
                  <th class="text-center">
                    <div class="custom-checkbox custom-checkbox-table custom-control">
                      <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                      <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                    </div>
                  </th>
                  <th><a href="javascript:void(0);" field="var_tool_name" class="_sort">Tools Name</a></th>
                  <th><a href="javascript:void(0);" field="var_size" class="_sort">Size</a></th>
                  <th><a href="javascript:void(0);" field="var_width" class="_sort">Width</a></th>
                  <th><a href="javascript:void(0);" field="var_height" class="_sort">Height</a></th>
                  <th><a href="javascript:void(0);" field="var_stock" class="_sort">Stock</a></th>
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
                      <td><?= $value['var_tool_name'] ?></td>
                      <td><?= $value['var_size'] ?></td>
                      <td><?= $value['var_width'] ?></td>
                      <td><?= $value['var_height'] ?></td>
                      <td><?= $value['var_stock'] ?></td>
                      <td class="text-center">
                        <a href="javascript:void(0);">
                          <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('tools', 'mst_tools', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
                        </a>
                      </td>
                      <td class="text-center">
                        <a href="javascript:void(0);" onclick="EditTool(<?= $value['int_glcode'] ?>)">
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
        <input type="hidden" name="module" id="module" value="tools">
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
<div class="modal fade" id="AddToolsModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Add Tools</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="">
          <div class="row">
            <div class="col-12 col-lg-12 col-md-12">
              <div class="form-group">
                <label for="var_tool_name">Tool Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control" name="var_tool_name" id="var_tool_name">
                </div>
                <span id="emptyErrorUniqItem" class="text-danger"></span>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6">
              <div class="form-group">
                <label for="var_width">Width</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="var_width" id="var_width">
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6">
              <div class="form-group">
                <label for="var_height">Height</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="var_height" id="var_height">
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6">
              <div class="form-group">
                <label for="var_size">Size</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="var_size" id="var_size">
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6">
              <div class="form-group">
                <label for="var_stock">Stock <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control" name="var_stock" id="var_stock" oninput="return isNumberKey(event)" pattern="[0-9]*" inputmode="numeric">
                </div>
                <span id="emptyErrorStock" class="text-danger"></span>
              </div>
            </div>
          </div>
          <button type="button" onclick="insertTool()" class="btn btn-primary m-t-15 waves-effect submit_save">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Labot Type Modal with form -->
<div class="modal fade" id="EditToolModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="formModal">Edit Tool</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="editmodel">

      </div>
    </div>
  </div>
</div>
<script src="<?= base_url() ?>/public/assets/dist/js/tools.js"></script>
<script>
  function insertTool(int_glcode = '') {
    var var_tool_name = $('#var_tool_name' + int_glcode).val();
    var var_stock = $('#var_stock' + int_glcode).val();
    var var_size = $('#var_size' + int_glcode).val();
    var var_width = $('#var_width' + int_glcode).val();
    var var_height = $('#var_height' + int_glcode).val();
    if (var_tool_name == '') {
      $('#emptyErrorUniqItem' + int_glcode).text('Please Enter Tool Name.');
      $('#var_tool_name' + int_glcode).focus();
    } else if (var_stock == '') {
      $('#emptyErrorStock' + int_glcode).text('');
      $('#emptyErrorStock' + int_glcode).text('Please Enter Tool Stock.');
      $('#var_stock' + int_glcode).focus();
    } else {
      $('#emptyErrorUniqItem' + int_glcode).text('');
      $('#emptyErrorStock' + int_glcode).text('');
      $.ajax({
        url: sitepath + "/admin/tools/insertRecord",
        type: 'POST',
        data: {var_tool_name: var_tool_name, var_size: var_size, var_stock:var_stock, var_width:var_width, var_height:var_height, int_glcode: int_glcode},
        beforeSend: function(){
          $('.submit_save').attr("disabled","disabled");
        },
        success: function(responce) {
          if (responce == 'Error') {
            iziToast.error({
              title: 'Oops...',
              message: 'Something went wrong!',
              position: 'topRight'
            });
          } else {
            $('#var_tool_name').val('');
            $('#var_size').val('');
            if(int_glcode>0){
              var txt_message = 'Tool updated successfully.';
            }else{
              var txt_message = 'Tool added successfully.';
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

  function EditTool(id) {
    $.ajax({
      url: sitepath + "/admin/tools/editTools",
      type: 'POST',
      data: {int_glcode: id},
      success: function(responce) {
        $('#EditToolModal').modal('show');
        $('#editmodel').html(responce);
      }
    });
  }
</script>