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
                    VIEW GREEN CASH DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($orders); exit; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php //pr($greencash); exit;?>
                                    <tr>
                                        <td width='15%'>Name</td>
                                        <td><?= $greencash['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Cash</td>
                                        <td><?= number_format($greencash['totalCash'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Paid Cash</td>
                                        <td><?= number_format($greencash['totalpaidCash'], 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Due Cash</td>
                                        <?php $dueCash = $greencash['totalCash'] - $greencash['totalpaidCash']; ?>
                                        <td><?= number_format($dueCash, 2); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    SEARCH FILTERS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <?php echo $this->Form->create($filter, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                        <label class="col-sm-2 control-label">Date Filters <span class="text-danger">*</span></label>
                        <div class="col-md-3">
                            <?php $months = ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December']; ?>
                            <?php echo $this->Form->input('months', ['label' => false, 'type' => 'select', 'options' => $months, 'empty' => 'SELECT MONTH', 'id' => '', 'class' => ' demo-default select-category required', 'placeholder' => 'SELECT MONTH']); ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            $yearArr = [];
                            $sYears = 2018;
                            $cYears = date('Y');
                            for ($i = $sYears; $i <= $cYears; $i++) {
                                $yearArr[$i] = $i;
                            }
                            ?>
                            <?php echo $this->Form->input('years', ['label' => false, 'type' => 'select', 'options' => $yearArr, 'empty' => 'SELECT YEAR', 'id' => '', 'class' => ' demo-default select-category required', 'placeholder' => 'SELECT YEAR']); ?>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">SEARCH</button>                                    
                            <?php echo $this->Html->link('RESET FILTER', array('controller' => 'GreenCashbacks', 'action' => 'resetfilters', $greencash['id']), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW GREEN CASH HISTORY
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($greencash); exit;  ?>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
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
                                        <?php if (is_array($greencash['cashHistory']) && !empty($greencash['cashHistory'])) { ?>
                                            <?php foreach ($greencash['cashHistory'] as $key => $val) { ?>
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
                                <?php if (isset($dueamount) && $dueamount != 0) { ?>
                                    <div class="row">
                                        <label class="col-sm-3"></label>
                                        <div class="col-sm-9 text-right">
                                            <?php echo $this->Html->link('Green Cash Settlement', ['controller' => 'GreenCashbacks', 'action' => 'payment', $greencash['id']], ['class' => 'btn btn-primary', 'escape' => false, 'title' => 'Green Cash Settlement']); ?>
                                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    PAYMENT HISTORY
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($greencash['paymnetHistory']) && !empty($greencash['paymnetHistory'])) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Payment Month</td>
                                                <td>Payment Details</td>
                                                <td class="text-right">Amount</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?= '1'; ?></td>
                                                <td><?= $greencash['paymnetHistory']['payment_month']; ?></td>
                                                <td>
                                                    <label>PAYMENT TYPE :</label> <?= $greencash['paymnetHistory']['payment_type'] ?><br>
                                                    <?php if ($greencash['paymnetHistory']['payment_type'] == 'CHEQUE') { ?>
                                                        <label>BANK NAME :</label> <?= $greencash['paymnetHistory']['bank_name'] ?><br>
                                                        <label>CHEQUE NO :</label> <?= $greencash['paymnetHistory']['cheque_no'] ?><br>
                                                        <label>CHEQUE DATE :</label> <?= $greencash['paymnetHistory']['cheque_date']->format('d-M-Y'); ?><br>
                                                    <?php } elseif ($greencash['paymnetHistory']['payment_type'] == 'CHEQUE') { ?>
                                                        <label>UPI TRANSACTION ID :</label> <?= $greencash['paymnetHistory']['transcation_id'] ?><br>
                                                    <?php } else { ?>
                                                        <label>OTHER DETAILS :</label> <?= $greencash['paymnetHistory']['other_details'] ?><br>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-right"><?= number_format($greencash['paymnetHistory']['payment_amount'], 2); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>

</div>