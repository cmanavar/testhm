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
                    EDIT PACKAGE SERVICES DETAILS
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
                            <?php $orderStatus = ['PENDING' => 'PENDING', 'PLACED' => 'PLACED', 'SCHEDULE' => 'SCHEDULE', 'COMPLETED' => 'COMPLETED']; ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ORDER STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php echo $this->Form->input('service_status', ['label' => false, 'type' => 'select', 'options' => $orderStatus, 'empty' => 'SELECT ORDER STATUS', 'id' => 'vendors', 'class' => ' demo-default select-category required', 'placeholder' => 'ORDER STATUS']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ASSIGN VENDOR <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php echo $this->Form->input('vendors_id', ['label' => false, 'type' => 'select', 'options' => $vendors, 'empty' => 'SELECT MEMBERSHIP PLAN', 'id' => '', 'class' => ' demo-default select-category required', 'placeholder' => 'ASSIGN VENDOR']); ?>
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