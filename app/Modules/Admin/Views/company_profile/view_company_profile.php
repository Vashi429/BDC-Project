
<section class="section">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"  aria-current="page">View Company Profile</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Company Profile Management</h4>
                    <div class="card-header-form">
                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/companyProfile/exportCSV">Export Company Profiles</a>
                        <a class="btn btn-primary text-right" href="<?= base_url() ?>/admin/companyProfile/addCompanyProfile">Add Company Profile</a>
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
                        <table class="table table-striped" id="viewCompanyProfile">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <div class="custom-checkbox custom-checkbox-table custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th><a href="javascript:void(0);" field="int_glcode" class="_sort">Id</a></th>
                                    <th><a href="javascript:void(0);" field="var_name" class="_sort">Name</a></th>
                                    <th><a href="javascript:void(0);" field="var_phone" class="_sort">Phone No<a></th>
                                    <th>Pancard No</th>
                                    <th>GST No</th>
                                    <th class="text-center">Publish</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data)){
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
                                            <td><a href="<?= base_url() ?>/admin/companyProfile/editCompanyProfile/<?= base64_encode($value['int_glcode']) ?>"><?=$value['int_glcode']?></a></td>
                                            <td><?=$value['var_name']?></td>
                                            <td><?=$value['var_phone']?></td>
                                            <td><?=$value['var_pan']?></td>
                                            <td><?=$value['var_gst']?></td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);">
                                                    <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('companyProfile', 'mst_company_profile', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
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
                <input type="hidden" name="module" id="module" value="companyProfile">
                <div class="card-footer custom_header">
                    <div>
                        <div class="page-limit page_pagination">
                            <label id="showing_"><?php echo 'Showing 1 to '.count($data).' of '.$total_data.' entries'; ?></label>
                        </div>
                        <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
                    </div>
                    <div id="pagination">
                        <?php echo $pager_links;?>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</section>
<script src= "<?= base_url() ?>/public/assets/dist/js/companyProfile.js"></script>
