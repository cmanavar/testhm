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
                            <table class="table table-bordered form-horizontal">
                                <tr>
                                    <td width="15%"><?= $this->Html->image(USER_PROFILE_PATH . $vendor['profile_pic'], ['height' => 200, 'width' => 200]) . "<br/>" ?></td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-1"><label>Name</label></div>
                                                    <div class="col-md-9">: <?= $vendor['user_type']; ?></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-1"><label>Email</label></div>
                                                    <div class="col-md-9">: <?= $vendor['email']; ?></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-1"><label>Phone</label></div>
                                                    <div class="col-md-9">: <?= $vendor['phone_no']; ?></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-1"><label>Service</label></div>
                                                    <div class="col-md-9">: <?= $vendor['service_name']; ?></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-1"><label>Due Cash</label></div>
                                                    <?php $dueamount = $vendor['credits'] - $vendor['debit']; ?>
                                                    <div class="col-md-9">: <?= number_format($dueamount, 2); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW VENDOR CASH HISTORY
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($vendor); //exit;  ?>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Order Id</td>
                                            <td>Type</td>
                                            <td>Status</td>
                                            <td>Cash Received Date</td>
                                            <td>Total Amount</td>
                                            <td class="text-right">Amount</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (is_array($vendor['vendor_cash']) && !empty($vendor['vendor_cash'])) { ?>
                                            <?php foreach ($vendor['vendor_cash'] as $key => $val) { ?>
                                                <tr>
                                                    <td><?= $key + 1; ?></td>
                                                    <td><?= "#" . $val['order_id']; ?></td>
                                                    <td><?= ucfirst(strtolower($val['payment_type'])); ?></td>
                                                    <td><?= ucfirst(strtolower($val['payment_status'])); ?></td>
                                                    <td><?= $val['created']->format('d-M-Y'); ?></td>
                                                    <td class="text-right"><?= number_format($val['total_amount'], 2); ?></td>
                                                    <td class="text-right"><?= number_format($val['commissions'], 2); ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="text-right">
                                                <td colspan="6">Total Credit Cash (COD Payment)</td>
                                                <td><?= number_format($vendor['credits'], 2); ?></td>
                                            </tr>
                                            <tr class="text-right">
                                                <td colspan="6">Paid Cash (Online Payment)</td>
                                                <td><?= "- " . number_format($vendor['debit'], 2); ?></td>
                                            </tr>
                                            <tr class="text-right">
                                                <td colspan="6">Due Cash</td>
                                                <?php $dueamount = $vendor['credits'] - $vendor['debit']; ?>
                                                <td><?= number_format($dueamount, 2); ?></td>
                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="6">No Records Found</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    CASE SATTLEMENTS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($vendorCash, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PAYMENT RECEIVED BY <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('payment_type', ['label' => false, 'type' => 'select', 'options' => $users, 'empty' => 'SELECT PAYMENT RECEIVED BY', 'id' => 'payment-type', 'class' => ' demo-default required', 'placeholder' => 'SELECT PAYMENT RECEIVED BY', 'value' => $this->request->session()->read('Auth.User.id'), 'disabled']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PAYMENT AMOUNT <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('payment_amount', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'PAYMENT AMOUNT', 'value' => $dueamount, 'maxlength' => 255, 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE CASH SATTLEMENTS</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>