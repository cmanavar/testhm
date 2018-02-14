<!--
 * Template : index
 *
 * Function : Display list of Orders
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /orders
-->

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-gift fa-fw"></i> PACKAGE SERVICES
            </h1>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW PACKAGE SERVICES DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($orders); exit; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="15%">Order ID</td>
                                        <td colspan="4"><?= "#" . $orders['order_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Customer Name</td>
                                        <td colspan="4"><?= $orders['username']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email Address</td>
                                        <td colspan="4"><?= $orders['useremail']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td colspan="4"><?= $orders['userphone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td colspan="4"><?= $orders['user_address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Schedule Time</td>
                                        <td colspan="4"><?= $orders['schedule_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Service Name</td>
                                        <td colspan="4"><?= $orders['service_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td colspan="4"><?= $orders['status']; ?></td>
                                    </tr>
                                    <?php if (isset($orders['vandor_name']) && $orders['vandor_name'] != '') { ?>
                                        <tr>
                                            <td>Expert Name</td>
                                            <td colspan="4"><?= $orders['vandor_name']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
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