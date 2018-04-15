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
                <i class="fa fa-user fa-fw"></i>EXPERTS
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
                    VIEW EXPERTS DETAILS
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
                                    <td width="30%"><label class="control-label">Agreement </label></td>
                                    <td>
                                        <?php if (isset($vendor['agreement']) && $vendor['agreement'] != '') { ?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Vendors', 'action' => 'downloadAgreements', $vendor['id']]) ?>"  class="btn btn-success btn-sm">DOWNLOAD AGREEMENTS</a>
                                        <?php } else { ?>
                                            <?php echo "-"; ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Id Proof </label></td>
                                    <td>
                                        <?php if (isset($vendor['id_proof']) && $vendor['id_proof'] != '') { ?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Vendors', 'action' => 'downloadIdProof', $vendor['id']]) ?>"  class="btn btn-success btn-sm">DOWNLOAD ID PROOF</a>
                                        <?php } else { ?>
                                            <?php echo "-"; ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Name </label></td>
                                    <td><?= $vendor['service_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Vendor Type </label></td>
                                    <td><?= ucfirst(strtolower(str_replace("_", " ", $vendor['vendor_type']))); ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Status</label></td>
                                    <td><?= (isset($vendor['active']) && $vendor['active'] == 'Y') ? 'ACTIVE' : 'INACTIVE'; ?></td>
                                </tr>

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