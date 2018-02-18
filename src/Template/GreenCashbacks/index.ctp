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
                <i class="fa fa-usd fa-fw"></i>  GREEN CASH
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
                    LIST OF USER'S GREEN CASH
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
                                                        <th>Total Green Cash</th>
                                                        <th width="11%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($greencash)) {
                                                        //pr($orders); 
                                                        foreach ($greencash as $key => $order) {
                                                            ?>
                                                            <tr>
                                                                <td width="3%"><?php echo stripslashes($key + 1) ?></td>
                                                                <td width="10%"><?php echo stripslashes($order['name']) ?></td>
                                                                <td width="12%" style="text-align:left;"><?php echo $order['totalCash']; ?></td>
                                                                <td width="8%">
                                                                    <?php echo $this->Html->link('', ['controller' => 'GreenCashbacks', 'action' => 'view', $order['id']], ['class' => 'btn btn-info fa fa-eye', 'escape' => false, 'title' => 'VIEW']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'GreenCashbacks', 'action' => 'edit', $order['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>

                                <!--                                                                                <a class='btn btn-primary btn-sm' href='#'><i class='fa fa-lg fa-pencil'></i></a>-->
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