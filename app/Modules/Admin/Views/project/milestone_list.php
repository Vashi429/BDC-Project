

<section class="section">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>/project">View Projects</a></li>
                        <li class="breadcrumb-item active"  aria-current="page">View Milestone List</li>
                    </ol>
                </nav>
                </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Milestone Management</h4>
                        <div class="card-header-form">
                            <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/project/newMilestone/<?= base64_encode($fk_project) ?>">Add Milestone</a>
                        </div>
                    </div>
                
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="milestoneList">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <div class="custom-checkbox custom-checkbox-table custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th><a href="javascript:void(0);" field="int_glcode" class="_sort">Id</a></th>
                                        <th><a href="javascript:void(0);" field="var_payment" class="_sort">Total Amount</a></th>
                                        <th><a href="javascript:void(0);" field="var_date" class="_sort">Completion Date</a></th>
                                        <th><a href="javascript:void(0);" field="var_description" class="_sort">Description<a></th>
                                        <th><a href="javascript:void(0);" field="dt_createddate" class="_sort">Created Date<a></th>
                                        <th>Publish</th>
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
                                                <td class="text-center">
                                                    <a href="<?= base_url()?>/admin/project/editMilestone/<?=base64_encode($fk_project)?>/<?=base64_encode($value['int_glcode'])?>">
                                                    <?= $value['int_glcode'] ?>
                                                    </a>
                                                </td>
                                                <td><?=$value['var_payment']?></td>
                                                <td><?=dateformate($value['var_date'])?></td>
                                                <td><?=$value['var_description']?></td>
                                                <td><?=dateformate($value['dt_createddate'])?></td>
                                                <td class="text-center">
                                                <a href="javascript:void(0);">
                                                    <img id="tick-<?php echo $value['int_glcode']; ?>" height="16" width="16" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo base_url() . '/public/assets/site_imges/' . $tickimg; ?>" style="vertical-align:middle;cursor:pointer;" onclick="UpdatePublish('project', 'mst_estimate', 'chr_publish', '<?php echo $update_val; ?>', '<?php echo $value['int_glcode']; ?>');">
                                                </a>
                                            </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="module" id="module" value="project">
                           
                        </div>
                    </div>
                    <div class="card-footer custom_header">
                        <div>
                            <button type="submit" class="btn btn-danger btn_fnt" name="btn_delete" id="btn_delete"><i class="icon dripicons-trash color_change"></i>Delete</button>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
    </section>
    <script>
        $("#milestoneList").dataTable();
    </script>



