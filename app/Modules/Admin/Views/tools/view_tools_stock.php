<style>
    .select2-container{
        width: 100% !important;
    }
</style>
<section class="section">

  <div class="row">

    <div class="col-12">

      <nav aria-label="breadcrumb">

        <ol class="breadcrumb">

          <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>

          <li class="breadcrumb-item active" aria-current="page">View Tools Stock</li>

        </ol>

      </nav>

    </div>

    <div class="col-12">

      <div class="card">

        <div class="card-header">

          <h4>Tools & Equipment Stock Management</h4>

          <div class="card-header-form">

            <!-- <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/tools/exportCSV">Export Tools</a> -->

            <button type="button" class="btn btn-primary text-right" data-toggle="modal" data-target="#AddToolsModal">Assign Tools</button>

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

                  <th class="text-center"></th>

                  <th><a href="javascript:void(0);" field="int_glcode" class="_sort">ID</a></th>

                  <th><a href="javascript:void(0);" field="var_tool_name" class="_sort">Tools Name</a></th>

                  <th><a href="javascript:void(0);" field="var_project" class="_sort">Project Name</a></th>

                  <th><a href="javascript:void(0);" field="var_stock" class="_sort">Stock</a></th>

                  <th><a href="javascript:void(0);" field="dt_createddate" class="_sort">Date</a></th>

                  <th class="text-center">Option</th>

                </tr>

              </thead>

              <tbody>

                <?php if (!empty($data)) {

                  foreach ($data as $key => $value) { ?>

                    <tr>

                      <td class="p-0 text-center"></td>

                      <td><?= $value['int_glcode'] ?></td>
                      <td><?= $value['var_tool_name'] ?></td>
                      <td><?= $value['var_project'] ?></td>
                      <td><?= $value['var_stock'] ?></td>
                      <td><?= $value['dt_createddate'] ?></td>

                      <td class="text-center">

                        <a href="javascript:void(0);" onclick="EditTool(<?= $value['int_glcode'] ?>)">

                          <i class="fas fa-exchange-alt"></i>

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

        <h5 class="modal-title" id="formModal">Assign Tools</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">
        <form class="">
          <div class="row">
            <div class="col-12 col-lg-12 col-md-12">
                <div class="form-group">
                    <label for="fk_project">Project <span class="text-danger">*</span></label><br>
                    <select class="form-control" name="fk_project" id="fk_project" required>
                        <option value=""></option>
                        <?php if(!empty($projectData)){ 
                            foreach($projectData as $value){ ?>
                                <option value="<?= $value['int_glcode'] ?>"><?= $value['var_project'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-12 col-lg-12 col-md-12">
                <div class="form-group">
                    <label for="fk_tool">Tools <span class="text-danger">*</span></label><br>
                    <select class="form-control" name="fk_tool" id="fk_tool" required onchange="getStock(this.value);">
                        <option value=""></option>
                        <?php if(!empty($tools)){ 
                            foreach($tools as $key => $value){ ?>
                                <option value="<?= $value['int_glcode'] ?>-<?= $key ?>"><?= $value['var_tool_name'] ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="col-12 col-lg-12 col-md-12">
                <div class="form-group">
                  <label for="var_stock">Stock Assign<span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="var_stock" id="var_stock" oninput="return isNumberKey(event)" pattern="[0-9]*" inputmode="numeric" onkeyup="check_stock(this.value)">
                  </div>
                  <span id="emptyErrorStock" class="text-danger"></span>
                </div>
            </div>

            <input type="hidden" name="hidden_stock" id="hidden_stock">

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

        <h5 class="modal-title" id="formModal">Transfer Tool</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body" id="editmodel">



      </div>

    </div>

  </div>

</div>

<script src="<?= base_url() ?>/public/assets/dist/js/tools_stock.js"></script>

<script>
    $("#fk_project").select2({
        placeholder: 'Select Project',
        dropdownParent: $('#AddToolsModal')
    });
    $("#fk_tool").select2({
        placeholder: 'Select Tool',
        dropdownParent: $('#AddToolsModal')
    });

    function getStock(id){
      var result = id.split('-');
      var final_key = result[1];
      var jqueryarray = <?php echo json_encode($tools_available_stock); ?>;

      var stock = jqueryarray[final_key];
      $("#hidden_stock").val(stock);
    }

    function check_stock(stock){
      var hidden_stock = $("#hidden_stock").val();
      if(Number(stock) > Number(hidden_stock)){
          $("#emptyErrorStock").text('Assign stock should not grater than '+hidden_stock+'.');
          $("#var_stock").val('');
      }else{
        $("#emptyErrorStock").text('');
      }
    }
  function insertTool() {

    var fk_project = $('#fk_project').val();
    var fk_tool = $('#fk_tool').val();
    var var_stock = $('#var_stock').val();

    if (fk_project == '') {

      $('#emptyErrorproject').text('Please Select Project.');
      $('#fk_project').focus();

    } else if (fk_tool == '') {

      $('#emptyErrorproject').text('');
      $('#emptyErrortool').text('Please Select Tool.');
      $('#fk_tool').focus();

    } else if(var_stock == ''){
      
      $('#emptyErrorStock').text('Please enter assign stock');
      $('#emptyErrorproject').text('');
      $('#emptyErrortool').text('');
      $('#var_stock').focus();
    }else{
      
      $('#emptyErrorStock').text('');
      $('#emptyErrorproject').text('');
      $('#emptyErrortool').text('');
      var result = fk_tool.split('-');
      var fk_tool = result[0];
      $.ajax({
        url: sitepath + "/admin/tools/insertStockRecord",
        type: 'POST',
        data: {fk_project: fk_project, fk_tool: fk_tool, var_stock:var_stock},
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
            var txt_message = 'Tool stock assign successfully.';
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


  function transfertool(id) {

    var fk_project = $('#fk_project_transfer').val();
    var var_stock = $('#var_stock_transfore').val();
    var hidden_stock_transfer = $('#hidden_stock_transfer').val();

    if (fk_project == '') {

      $('#emptyErrortoolTransfer').text('Please Select Project.');
      $('#fk_project_transfer').focus();

    } else if(var_stock == ''){
      
      $('#emptyErrorStockTransfer').text('Please enter assign stock');
      $('#var_stock_transfore').focus();
    }else if(Number(var_stock) > Number(hidden_stock_transfer)){
      $('#emptyErrorStockTransfer').text('Transfer stock should not be greater than '+hidden_stock_transfer);
      $('#var_stock_transfore').focus();
    }else{
      $('#emptyErrortoolTransfer').text('');
      $('#emptyErrorStockTransfer').text('');
      $.ajax({
        url: sitepath + "/admin/tools/transferstock",
        type: 'POST',
        data: {fk_project: fk_project, var_stock: var_stock, id:id},
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
            var txt_message = 'Tool Transfer successfully.';
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

      url: sitepath + "/admin/tools/transfor_tool",

      type: 'POST',
      
      dataType: 'json',

      data: {int_glcode: id},

      success: function(responce) {

        $('#EditToolModal').modal('show');

        $('#editmodel').html(responce.html);
        $("#fk_project_transfer").select2({
            placeholder: 'Select Project',
            dropdownParent: $('#EditToolModal')
        });
      }

    });

  }

</script>