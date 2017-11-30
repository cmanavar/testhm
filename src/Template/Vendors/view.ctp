<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-user fa-fw"></i>VENDORS
                <a href="<?php echo $this->Url->build(["controller" => "Vendors", "action" => "index"]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW VENDOR DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
//                    echo "<pre>";
//                    print_r($vendor);
//                    exit;
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <?php // foreach ($category as $key => $val) {   ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Name</label></td>
                                    <td><?= $vendor['name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Email</label></td>
                                    <td><?= $vendor['email']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Phone number</label></td>
                                    <?php
                                    $phoneNumber = $vendor['phone_no'];
                                    $phoneNumber .= ($vendor['phone_number_2'] != '') ? ' | ' . $vendor['phone_number_2'] : '';
                                    ?>
                                    <td><?= $phoneNumber; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Profile Picture</label></td>
                                    <td><?= $this->Html->image(USER_PROFILE_PATH . $vendor['profile_pic'], ['height' => 100, 'width' => 100]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Name </label></td>
                                    <td><?= $vendor['service_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Shift Timing</label></td>
                                    <td><?= $vendor['shift_start'] . ' - ' . $vendor['shift_end']; ?></td>
                                </tr>
<!--                                <tr>
                                    <td width="30%"><label class="control-label">Square Image Preview</label></td>
                                    <td><?= $this->Html->image(SERVICE_SQUARE_BANNER_PATH . $$vendorservices['square_image'], ['height' => 65, 'width' => 65]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Popular</label></td>
                                    <td><?= $services['popular']; ?></td>
                                </tr>-->
                                <tr>
                                    <td width="30%"><label class="control-label">Status</label></td>
                                    <td><?= (isset($vendor['active']) && $vendor['active'] == 'Y') ? 'ACTIVE' : 'INACTIVE'; ?></td>
                                </tr>
                                <?php // }   ?>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.row -->
    </div>
</div>