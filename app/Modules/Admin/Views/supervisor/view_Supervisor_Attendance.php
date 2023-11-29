<section class="section">

    <div class="row">

        <div class="col-12">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="<?= base_url() ?>/admin/dashboard">Home</a></li>

                    <li class="breadcrumb-item active"  aria-current="page">View Supervisor Attendance</li>

                </ol>

            </nav>

        </div>

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <h4>Supervisor Attendance</h4>

                    <div class="card-header-form">

                        <a class="btn btn-primary text-right mr-2" href="<?= base_url() ?>/admin/supervisor/exportCSVAttendance">Export Supervisors Attendance</a>

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

                        <table class="table table-striped" id="viewSupervisor">

                            <thead>

                                <tr>

                                    <th></th>
                                    <th><a href="javascript:void(0);" field="int_glcode" class="_sort">Id</a></th>

                                    <th><a href="javascript:void(0);" field="var_name" class="_sort">Project</a></th>

                                    <th><a href="javascript:void(0);" field="var_username" class="_sort">Superviser</a></th>

                                    <th><a href="javascript:void(0);" field="var_email" class="_sort">Punch Time<a></th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php if(!empty($data)){
                                    foreach ($data as $key => $value) { 
                                ?>

                                        <tr id="<?= $value['int_glcode'] ?>">

                                            <td></td>
                                            <td><?=$value['int_glcode']?></td>

                                            <td><?=$value['var_project']?></td>

                                            <td><?=$value['var_name']?></td>

                                            <td><?=$value['var_entry']?></td>

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

                <input type="hidden" name="module" id="module" value="supervisor">

                <div class="card-footer custom_header">

                    <div>

                        <div class="page-limit page_pagination">

                            <label id="showing_"><?php echo 'Showing 1 to '.count($data).' of '.$total_data.' entries'; ?></label>

                        </div>

                    </div>

                    <div id="pagination">

                        <?php echo $pager_links;?>

                    </div>

                  </div>

            </div>

        </div>

    </div>

</section>

<script>

    var siteurl = '<?= base_url() ?>';

    var perPage = '<?= ADMIN_PER_PAGE_ROWS ?>';

</script>   

<script src= "<?= base_url() ?>/public/assets/dist/js/supervisor_attendance.js"></script>

