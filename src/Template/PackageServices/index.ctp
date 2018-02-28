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
                <i class="fa fa-gift fa-fw"></i>  PACKAGE SERVICES
<!--                <a href="<?php echo $this->Url->build(["controller" => "Orders", "action" => "add"]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW ORDER</button></a>-->
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
                    LIST OF PACKAGE SERVICES
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
                                                        <th width="13%">Service Details</th>
                                                        <th>Order Details</th>
                                                        <th>Order Time</th>
                                                        <th width="11%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($orders)) {
                                                        //pr($orders); 
                                                        foreach ($orders as $key => $order) {
                                                            ?>
                                                            <tr>
                                                                <td width="3%"><?php echo stripslashes($key + 1) ?></td>
                                                                <td width="10%"><?php echo stripslashes($order['username']) ?></td>
                                                                <td style="text-align:left;" width="19%">
                                                                    <label> <span style="font-weight: bolder">Email :</span> </label> <?php echo stripslashes($order['useremail']); ?><br>
                                                                    <label> Phone : </label> <?php echo stripslashes($order['userphone']); ?><br>
                                                                    <label> Usertype : </label> <?php echo (isset($order['usertype']) && $order['usertype'] != '') ? stripslashes($order['usertype']) : '-'; ?>
                                                                </td>
                                                                <td style="text-align:left;">
                                                                    <label> Service Name : </label> <?php echo $order['service_name']; ?><br>
                                                                    <label> Schedule Time : </label> <?php echo $order['schedule_date']; ?><br>
                                                                </td>   
                                                                <td width="20%">
                                                                    <label> Order id : </label> <?= '#' . $order['id'] ?><br>
                                                                    <label> Order Status : </label> <?php echo $order['status']; ?><br>
                                                                    <?php if (isset($order['vandor_name']) && $order['vandor_name'] != '') { ?>
                                                                        <label> Service Expert : </label> <?php echo $order['vandor_name']; ?>
                                                                    <?php } ?>
                                                                </td>   
                                                                <td width="12%" style="text-align:left;"><?php echo $order['created_at']; ?></td>
                                                                <td width="8%">
                                                                    <?php echo $this->Html->link('', ['controller' => 'PackageServices', 'action' => 'view', $order['id']], ['class' => 'btn btn-info fa fa-eye', 'escape' => false, 'title' => 'VIEW']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'PackageServices', 'action' => 'edit', $order['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                </td>    
                                                            </tr>

                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="8" style="text-align:center;"><b>No Records found </b></td></tr>';
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