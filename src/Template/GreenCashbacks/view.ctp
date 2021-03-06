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
                            <?php //pr($greencash); exit; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Name</td>
                                        <td><?= $greencash['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Cash</td>
                                        <td><?= $greencash['totalCash']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Paid Cash</td>
                                        <td><?= $greencash['totalpaidCash']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Due Cash</td>
                                        <td><?= $greencash['totalCash'] - $greencash['totalpaidCash']; ?></td>
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
                            <?php if (isset($greencash['paymentHistory']) && !empty($greencash['paymentHistory'])) { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>No</td>
                                                <td>Payment Month</td>
                                                <td>Payment Types</td>
                                                <td>Payment Details</td>
                                                <td class="text-right">Amount</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($greencash['paymentHistory'] as $key => $val) { ?>
                                                <tr>
                                                    <td><?= $key + 1; ?></td>
                                                    <td><?= $val['payment_month']; ?></td>
                                                    <td> <?= $val['payment_type'] ?></td>
                                                    <td>
                                                        <?php if ($val['payment_type'] == 'CHEQUE') { ?>
                                                            <label>BANK NAME :</label> <?= $val['bank_name'] ?><br>
                                                            <label>CHEQUE NO :</label> <?= $val['cheque_no'] ?><br>
                                                            <label>CHEQUE DATE :</label> <?= $val['cheque_date']->format('d-M-Y'); ?><br>
                                                        <?php } elseif ($val['payment_type'] == 'CHEQUE') { ?>
                                                            <label>UPI TRANSACTION ID :</label> <?= $val['transcation_id'] ?><br>
                                                        <?php } else { ?>
                                                            <label>OTHER DETAILS :</label> <?= $val['other_details'] ?><br>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-right"><?= number_format($val['payment_amount'], 2); ?></td>
                                                </tr>
                                            <?php } ?>
                                                <tr>
                                                    <td colspan="4"><b>Total Payments</b></td>
                                                    <td class="text-right"><?= number_format($greencash['totPayments']['tot'], 2); ?></td>
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