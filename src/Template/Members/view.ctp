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
                <i class="fa fa-user fa-fw"></i>MEMBERS
                <a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "index"]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
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
                                    <td width="30%"><label class="control-label">Membership Plan</label></td>
                                    <td><?= $member['plan_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Name</label></td>
                                    <td><?= $member['name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Address</label></td>
                                    <td><?= $member['address']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">City</label></td>
                                    <td><?= $member['city']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Phone number</label></td>
                                    <?php
                                    $phoneNumber = $member['phone_no'];
                                    ?>
                                    <td><?= $phoneNumber; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Email</label></td>
                                    <td><?= $member['email']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Birth Date</label></td>
                                    <td><?= $member['birthdate']->format('d-M-Y'); ?></td>
                                </tr>
                                <?php if (isset($member['aniversary_date']) && $member['aniversary_date'] != '') { ?>
                                    <tr>
                                        <td width="30%"><label class="control-label">Anniversary Date</label></td>
                                        <td><?= $member['aniversary_date']->format('d-M-Y'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php 
                                    $familymemberdetails = '';
                                    if($member['person_1'] != '' || !empty($member['birthdate_1'])) {
                                        $familymemberdetails .= $member['person_1'] . " | ". $member['birthdate_1']->format('d-M-Y');
                                    }
                                    if($member['person_2'] != '' && !empty($member['birthdate_2'])) {
                                        $familymemberdetails .= "<br/>" . $member['person_2'] . " | ". $member['birthdate_2']->format('d-M-Y');
                                    }
                                    if($member['person_3'] != '' && !empty($member['birthdate_3'])) {
                                        $familymemberdetails .= "<br/>" . $member['person_3'] . " | ". $member['birthdate_3']->format('d-M-Y');
                                    }
                                    if($member['person_4'] != '' && !empty($member['birthdate_4'])) {
                                        $familymemberdetails .= "<br/>" . $member['person_4'] . " | ". $member['birthdate_4']->format('d-M-Y');
                                    }
                                    if($member['person_5'] != '' && !empty($member['birthdate_5'])) {
                                        $familymemberdetails .= "<br/>" . $member['person_5'] . " | ". $member['birthdate_5']->format('d-M-Y');
                                    }
                                ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Family Member Details</label></td>
                                    <td><?= $familymemberdetails; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Occupation</label></td>
                                    <td><?= $member['occupation']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Company Name</label></td>
                                    <td><?= $member['company_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Company Website</label></td>
                                    <td><a href='<?= $member['company_website']; ?>' class="black-text" target="" ><?= $member['company_website']; ?></a></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Payment Type</label></td>
                                    <td><?= $member['payment_type']; ?></td>
                                </tr>
                                <?php if($member['payment_type'] == 'CHEQUE') { ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Cheque Details</label></td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td width="125px;">Bank Name</td>
                                                <td>: <?= $member['bank_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Cheque Number</td>
                                                <td>: <?= $member['cheque_no']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Cheque Date</td>
                                                <td>: <?= $member['cheque_date']->format('d-M-Y'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($member['payment_type'] == 'UPI') { ?>
                                <tr>
                                    <td><label class="control-label">Transaction Id </label></td>
                                    <td>: <?= $member['transcation_id']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if($member['payment_type'] == 'OTHER') { ?>
                                <tr>
                                    <td><label class="control-label">Other Details </label></td>
                                    <td>: <?= $member['other_details']; ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Reference User</label></td>
                                    <td><?= $member['reference_user_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Status</label></td>
                                    <td><?= (isset($member['active']) && $member['active'] == 'Y') ? 'Active' : 'Inactive'; ?></td>
                                </tr>
                                <?php // }    ?>
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