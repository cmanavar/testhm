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
        <?php echo $this->Form->create('', ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    EDIT ORDERS DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    //echo "<pre>";
                    //pr($orders); //exit;  
                    ?>
                    <div class="row">
                        <div class="col-md-12">
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
                                    <td colspan="4"><?= $orders['schedule_date'] . " | " . $orders['schedule_time']; ?></td>
                                </tr>
                                <tr>
                                    <td>On Inspections</td>
                                    <td colspan="4"><?= $orders['on_inspections']; ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td colspan="4"><?= $orders['status']; ?></td>
                                </tr>
                                <tr>
                                    <td>Payment Status</td>
                                    <td colspan="4"><?= $orders['payment_status']; ?></td>
                                </tr>
                                <tr>
                                    <td>Order Total</td>
                                    <td colspan="4"><?= $orders['total_amount']; ?></td>
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
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    ASSIGN ORDER DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">

                                <tr>
                                    <td>No</td>
                                    <td>Service Details</td>
                                    <td>Assign Vendors</td>
                                    <td>Amount</td>
                                    <td>Quantity</td>
                                    <td>Total</td>
                                </tr>
                                <?php if (isset($orders['services']) && !empty($orders['services'])) { ?>
                                    <?php foreach ($orders['services'] as $key => $service) { ?> 
                                        <tr>
                                            <td rowspan="<?php echo count($service['services']) + 1; ?>"><b><?= $key + 1; ?></b></td>
                                            <td colspan="5"><b><?= $service['category']; ?></b></td>
                                        </tr>
                                        <?php if (isset($service['services']) && !empty($service['services'])) { ?>
                                            <?php foreach ($service['services'] as $k => $v) { ?>
                                                <?php // pr($v); //exit; ?>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4">Services Name</div>
                                                            <div class="col-md-8">: <?php echo $v['service_name']; ?></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">Services Descriptions</div>
                                                            <div class="col-md-8">: <?php echo $v['serviceDescription']; ?></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-left" width='17%'>
                                                        <input type="hidden" name="order_assign[<?= $k ?>][cart_order_id]" value="<?= $v['cart_order_id']; ?>" />
                                                        <input type="hidden" name="order_assign[<?= $k ?>][order_id]" value="<?= $orders['order_id']; ?>" />
                                                        <?php echo $this->Form->input('order_assign[' . $k . '][vendor_id]', ['label' => false, 'type' => 'select', 'options' => $vendors[$v['service_id']], 'empty' => 'SELECT MEMBERSHIP PLAN', 'id' => '', 'class' => ' demo-default select-category required', 'placeholder' => 'ASSIGN VENDORS']); ?>
                                                    </td>
                                                    <td class="text-right"><?php echo $v['amount']; ?></td>
                                                    <td class="text-right"><?php echo $v['quantity']; ?></td>
                                                    <td class="text-right"><?php echo $v['total_amount']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <tr class="text-right">
                                    <td colspan="5"><b>Total</b></td>
                                    <td class="text-right"><?php echo $orders['total']['amount']; ?></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="5"><b>GST</b></td>
                                    <td><?php echo $orders['total']['tax']; ?></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="5">
                                        <?php if (isset($orders['is_coupon_applied']) && $orders['is_coupon_applied'] == 'Y') { ?>
                                            <b>Discount Applied <?php echo $orders['coupon_code']; ?></b>
                                        <?php } else { ?>
                                            <b>Discount Applied</b>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo "- " . number_format($orders['total']['discount'], 2); ?></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="5"><b>Wallet</b></td>
                                    <td><?php echo "- " . number_format($orders['total']['wallet_amount'], 2); ?></td>
                                </tr>
                                <tr class="text-right">
                                    <td colspan="5"><b>Order Total</b></td>
                                    <td><?php echo number_format($orders['total']['total_amount'], 2); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 pull-right text-right">
                            <button type="submit" class="btn btn-primary">ORDER SCHEDULED</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>