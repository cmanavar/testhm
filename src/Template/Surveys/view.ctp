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
                <i class="fa fa-user fa-fw"></i>SURVEYS
                <a href="<?php echo $this->Url->build(["controller" => "Surveys", "action" => "index"]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
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
                    VIEW SURVEYS DETAILS
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
                                    <td width="30%"><label class="control-label">Survey Id</label></td>
                                    <td><?= ucfirst(strtolower($servey['survey_id'])); ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Area Type</label></td>
                                    <td><?= ucfirst(strtolower($servey['user_type'])); ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Name</label></td>
                                    <td><?= $servey['person_name']; ?></td>
                                </tr>
                                <?php if (isset($servey['company_name']) && $servey['company_name'] != '') { ?>
                                    <tr>
                                        <td width="30%"><label class="control-label">Company Name</label></td>
                                        <td><?= $servey['company_name']; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Address</label></td>
                                    <td><?= $servey['address']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Contact Number</label></td>
                                    <td><?= $servey['contact_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Email Address</label></td>
                                    <td><?= $servey['email']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Appoinment Date</label></td>
                                    <td><?= $servey['appoinment_date']->format('d-m-Y'); ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Appoinment Time</label></td>
                                    <td><?= $servey['appoinment_time']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">What service or repair work usually you perform at your place?</label></td>
                                    <td><?= $servey['services_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Who performs the service or repair work at your place?</label></td>
                                    <td><?= $servey['who_performs_the_service_or_repair_work_at_your_place']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">How would you rate your Current Service provider's satisfaction with us?</label></td>
                                    <td>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><b>Quality Of Service</b></td>
                                                <td><?= $servey['rating_quality_of_service']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Price Range</td>
                                                <td><?= $servey['rating_price_range']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Punctuality</td>
                                                <td><?= $servey['rating_punctuality']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Cleanliness</td>
                                                <td><?= $servey['rating_cleanliness']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Professional Behavior</td>
                                                <td><?= $servey['rating_professional_behavior']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Periodic Checkups</td>
                                                <td><?= $servey['rating_periodic_checkups']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Over All Rating</td>
                                                <td><?= $servey['rating_over_all']; ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>How often do you typically use Repair and Service work?</td>
                                    <td><?= str_replace("_", " ", $servey['how_often_do_you_typically_use_repair_and_service_work']); ?></td>
                                </tr>
                                <tr>
                                    <td>How much usually they charge for Repair and Service work?</td>
                                    <td><?= str_replace("_", " ", $servey['how_much_usually_they_charge_for_repair_and_service_work']); ?></td>
                                </tr>
                                <tr>
                                    <td>How long do you have wait to avail their Service?</td>
                                    <td><?= str_replace("_", " ", $servey['how_long_do_you_have_wait_to_avail_their_service']); ?></td>
                                </tr>
                                <tr>
                                    <td>According to you who is Ideal service provider?</td>
                                    <td><?= str_replace("_", " ", $servey['according_to_you_who_is_Ideal_service_provider']); ?></td>
                                </tr>
                                <tr>
                                    <td>If we come up with the Service Provider to your satisfaction will you listen to us?</td>
                                    <td><?= str_replace("_", " ", $servey['if_we_come_up_with_the_service_provider_to_your_satisfaction_wil']); ?></td>
                                </tr>
                                <tr>
                                    <td>Appointment Status</td>
                                    <td><?= ucfirst(strtolower($servey['appoinment_status'])) ?></td>
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