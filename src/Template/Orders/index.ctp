<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<?php echo $this->element('js/datatable-delete'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-shopping-cart fa-fw"></i>  ORDERS
  <!--                <a href="<?php echo $this->Url->build(["controller" => "Questions", "action" => "add", $service_id]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW QUESTION</button></a>-->
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo ($this->Flash->render()); ?>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    LIST OF ORDERS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <div class="table-responsive">
                            <div id="dataTables-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="" id="dataTables-responsive_length">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-responsive" >
                                                <thead>
                                                    <tr>
                                                        <th width="2%">Sr.No</th>
                                                        <th>Username</th> 
                                                        <th>User Details</th>
                                                        <th>Service Details</th>
                                                        <th>Order Status</th>
                                                        <th>Order Time</th>
                                                        <th width="11%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($orders)) {
                                                        foreach ($orders as $key => $order) {
                                                            ?>
                                                            <tr>
                                                                <td width="3%"><?php echo stripslashes($key + 1) ?></td>
                                                                <td width="10%"><?php echo stripslashes($order['username']) ?></td>
                                                                <td style="text-align:left;" width="19%">
                                                                    <label> <span style="font-weight: bolder">Email :</span> </label> <?php echo stripslashes($order['useremail']); ?><br>
                                                                    <label> Phone : </label> <?php echo stripslashes($order['userphone']); ?>
                                                                </td>
                                                                <td style="text-align:left;">
                                                                    <label> Service Name : </label> <?php echo $order['service_name']; ?><br>
                                                                    <label> Schedule Time : </label> <?php echo $order['schedule_date'] . " | " . $order['schedule_time']; ?><br>
                                                                    <?php if ($order['on_inspections'] == 'N') { ?>
                                                                        <label> Charge : </label> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $order['total_amount']; ?>
                                                                        <?php if (isset($order['is_minimum_charge']) && ($order['is_minimum_charge'] == 'Y')) { ?>
                                                                            (Minimum Charges Applied)
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <?php $tottmps = ''; ?>
                                                                        <?php if (isset($order['total_amount']) && ($order['total_amount'] != '0.00')) { ?>
                                                                            <?php $tottmps = $order['total_amount']; ?>
                                                                        <?php } ?>
                                                                        <?php $tottmps .= ' (On Inspections)'; ?>
                                                                        <label> Charge : </label> <?= $tottmps ?>
                                                                    <?php } ?>
                                                                </td>   
                                                                <td width="10%"><?php echo $order['status']; ?></td>   
                                                                <td width="12%" style="text-align:left;"><?php echo $order['created_at']; ?></td>
                                                                <td width="8%">
                                                                    <a class='btn btn-info btn-sm' href='#'><i class='fa fa-lg fa-eye'></i></a>
                                                                    <a class='btn btn-primary btn-sm' href='#'><i class='fa fa-lg fa-pencil'></i></a>
        <!--                                                                                <a class='btn btn-primary btn-sm' href='#'><i class='fa fa-lg fa-pencil'></i></a>-->
                                                                </td>    
                                                            </tr>

                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="7" style="text-align:center;"><b>No Records found </b></td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                        <!--paginations-->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>