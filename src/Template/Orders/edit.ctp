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
                <i class="fa fa-shopping-cart fa-fw"></i> ORDERS
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
                    EDIT ORDERS DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="15%">Order ID</td>
                                        <td colspan="4"><?= "#" . $orderDetails['order_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Customer Name</td>
                                        <td colspan="4"><?= $orderDetails['username']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email Address</td>
                                        <td colspan="4"><?= $orderDetails['useremail']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number</td>
                                        <td colspan="4"><?= $orderDetails['userphone']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td colspan="4"><?= $orderDetails['user_address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Schedule Time</td>
                                        <td colspan="4"><?= $orderDetails['schedule_date'] . " | " . $orderDetails['schedule_time']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>On Inspections</td>
                                        <td colspan="4"><?= $orderDetails['on_inspections']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td colspan="4"><?= $orderDetails['status']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Payment Status</td>
                                        <td colspan="4"><?= $orderDetails['payment_status']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Order Total</td>
                                        <td colspan="4"><?= $orderDetails['total_amount']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>No</td>
                                        <td>Service Details</td>
                                        <td class="text-right">Amount</td>
                                        <td class="text-right">Quantity</td>
                                        <td class="text-right">Total</td>
                                    </tr>
                                    <?php if (isset($orderDetails['services']) && !empty($orderDetails['services'])) { ?>
                                        <?php foreach ($orderDetails['services'] as $key => $service) { ?> 
                                            <tr>
                                                <td rowspan="<?php echo count($service['services']) + 1; ?>"><b><?= $key + 1; ?></b></td>
                                                <td colspan="4"><b><?= $orderDetails['category_name'] . " | " . $orderDetails['service_name']; ?></b></td>
                                            </tr>
                                            <?php if (isset($service['services']) && !empty($service['services'])) { ?>
                                                <?php foreach ($service['services'] as $k => $v) { ?>
                                                    <?php // pr($v); //exit; ?>
                                                    <tr>
                                                        <td><?php echo $v['serviceDescription']; ?></td>
                                                        <td class="text-right"><?php echo $v['amount']; ?></td>
                                                        <td class="text-right"><?php echo $v['quantity']; ?></td>
                                                        <td class="text-right"><?php echo $v['total_amount']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <tr class="text-right">
                                        <td colspan="4"><b>Total</b></td>
                                        <td class="text-right"><?php echo $orderDetails['total']['amount']; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td colspan="4"><b>GST (<?= GST_TAX; ?>%)</b></td>
                                        <td><?php echo $orderDetails['total']['tax']; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td colspan="4">
                                            <?php if (isset($orderDetails['is_coupon_applied']) && $orderDetails['is_coupon_applied'] == 'Y') { ?>
                                                <b>Discount Applied <?php echo $orderDetails['coupon_code']; ?></b>
                                            <?php } else { ?>
                                                <b>Discount Applied</b>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo "- " . number_format($orderDetails['total']['discount'], 2); ?></td>
                                    </tr>
                                    <tr class="text-right">
                                        <td colspan="4"><b>Wallet</b></td>
                                        <td><?php echo "- " . number_format($orderDetails['total']['wallet_amount'], 2); ?></td>
                                    </tr>
                                    <?php if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') { ?>
                                        <tr class="text-right">
                                            <td colspan="4"><b>Bill Amount</b></td>
                                            <td><?php echo number_format($orderDetails['total']['bill_amount'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="text-right">
                                        <td colspan="4">
                                            <b>Order Total</b>
                                            <?php if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') { ?>
                                                (Minimum Charges Applied)
                                            <?php } ?>
                                        </td>
                                        <td><?php echo number_format($orderDetails['total']['total_amount'], 2); ?></td>
                                    </tr>
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
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    ASSIGN ORDER DETAILS 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($order); //exit; ?>
                            <?php echo $this->Form->create($order, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
<!--                            <input type="hidden" name="cart_order_id" value="<?= $v['cart_order_id']; ?>" />-->
<!--                            <input type="hidden" name="order_id" value="<?= $orders['order_id']; ?>" />-->
                            <?php //$orderStatus = ['PENDING' => 'PENDING', 'PLACED' => 'PLACED', 'ON_INSPECTION '=> 'ON INSPECTION', 'SCHEDULE' => 'SCHEDULE', 'COMPLETED' => 'COMPLETED', 'CANCELLED' => 'CANCELLED']; ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ORDER STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php echo $this->Form->input('status', ['label' => false, 'type' => 'select', 'options' => $orderStatus, 'empty' => 'SELECT ORDER STATUS', 'id' => 'vendors', 'class' => ' demo-default select-category required', 'placeholder' => 'ORDER STATUS']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ASSIGN VENDOR <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php echo $this->Form->input('vendors_id', ['label' => false, 'type' => 'select', 'options' => $vendors[$v['service_id']], 'empty' => 'SELECT MEMBERSHIP PLAN', 'id' => '', 'class' => ' demo-default select-category required', 'placeholder' => 'ASSIGN VENDOR']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-5">
                                    <button type="submit" class="btn btn-primary">SAVE ORDERS</button>                                    
                                    <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>