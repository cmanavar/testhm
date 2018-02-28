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
                <i class="fa fa-star fa-fw"></i> GREEN CASH
            </h1>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW GREEN CASH HISTORY
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($greencash); exit; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Reference Name</td>
                                            <td>Plan Name</td>
                                            <td>Status</td>
                                            <td>Plan Sale Date</td>
                                            <td class="text-right">Amount</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $monthDetails = ''; ?>
                                        <?php if (is_array($greencash['cashHistory']) && !empty($greencash['cashHistory'])) { ?>
                                            <?php foreach ($greencash['cashHistory'] as $key => $val) { ?>
                                                <?php $monthDetails = date('M-Y', strtotime($val['date'])); ?>
                                                <tr>
                                                    <td><?= $key + 1; ?></td>
                                                    <td><?= $val['refer_membership_name']; ?></td>
                                                    <td><?= $val['plan_name']; ?></td>
                                                    <td><?= ucfirst(strtolower($val['status'])); ?></td>
                                                    <td><?= $val['date']; ?></td>
                                                    <td class="text-right"><?= number_format($val['amount'], 2); ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="text-right">
                                                <td colspan="5">Total Cash</td>
                                                <td><?= number_format($greencash['totalCash'], 2); ?></td>
                                            </tr>
                                            <tr class="text-right">
                                                <td colspan="5">Paid Cash</td>
                                                <td><?= "- " . number_format($greencash['totalpaidCash'], 2); ?></td>
                                            </tr>
                                            <tr class="text-right">
                                                <td colspan="5">Due Cash</td>
                                                <?php $dueamount = $greencash['totalCash'] - $greencash['totalpaidCash']; ?>
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
            <!-- /.panel -->
            <?php if (isset($dueamount) && $dueamount != 0) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        PAYMENT DETAILS ( <?= $monthDetails; ?> )
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <?php echo $this->Form->create($greencashpayment, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">PAYMENT MONTH <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('payment_month', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'PAYMENT MONTH', 'value' => $monthDetails, 'maxlength' => 255, 'readonly']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">PAYMENT TYPE <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('payment_type', ['label' => false, 'type' => 'select', 'options' => ['CHEQUE' => 'CHEQUE', 'UPI' => 'UPI', 'OTHER' => 'OTHER'], 'empty' => 'SELECT PAYMENT TYPE', 'id' => 'payment-type', 'class' => ' demo-default required', 'placeholder' => 'SELECT PAYMENT TYPE']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="chequeDetail" id="chequeDetails" style="display:none">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">BANK NAME <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <div class="input text">
                                                <?php echo $this->Form->input('bank_name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER BANK NAME', 'maxlength' => 255]); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">CHEQUE NUMBER <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <div class="input text">
                                                <?php echo $this->Form->input('cheque_no', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER CHEQUE NUMBER', 'maxlength' => 255]); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">CHEQUE DATE <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <div class="input text">
                                                <?php echo $this->Form->input('cheque_date', ['label' => false, 'type' => 'text', 'class' => 'form-control required datepicker normal-font', 'placeholder' => 'ENTER CHEQUE DATE', 'maxlength' => 255]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="transcationDetail" id="transcationDetails" style="display:none">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">TRANSACTION ID <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <div class="input text">
                                                <?php echo $this->Form->input('transcation_id', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER TRANSACTION ID', 'maxlength' => 255]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="otherDetail" id="otherDetails" style="display:none">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">OTHERS <span class="text-danger">*</span></label>
                                        <div class="col-sm-6">
                                            <div class="input text">
                                                <?php echo $this->Form->input('other_details', ['rows' => '3', 'cols' => '90', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER OTHER PAYMENT DETAILS'], ['type' => 'textarea']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">PAYMENT AMOUNT <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('payment_amount', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'PAYMENT AMOUNT', 'value' => $dueamount, 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3  control-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">SAVE PAYMENT</button>                                    
                                <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- /.row -->
</div>

