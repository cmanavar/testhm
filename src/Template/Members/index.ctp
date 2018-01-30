<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('patient-dropdown.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-user fa-fw"></i>MEMBERS
                <a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "add"]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW MEMBER</button></a>
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
                    LIST MEMBERS
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
                                                        <th width="2%">Sr.no</th>
                                                        <th>Name</th> 
                                                        <th>Contact Info</th>
                                                        <th>Payment Details</th>
                                                        <th>Status</th>
                                                        <th width="17%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($members)) {
                                                        foreach ($members as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo stripslashes($val['name']) ?></td>
                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td><b>ADDRESS </b></td>
                                                                            <td>: <?php echo $val['address']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><b>EMAIL ID </b></td>
                                                                            <td>: <?php echo $val['email']; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="16%"><b>PHONE NO </b></td>
                                                                            <td>: <?php echo $val['phone_no']; ?></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td width="57%"><b>PAYMENT TYPE </b></td>
                                                                            <td>: <?php echo $val['payment_type']; ?></td>
                                                                        </tr>
                                                                        <?php if ($val['payment_type'] == 'CHEQUE') { ?>
                                                                            <tr>
                                                                                <td><b>Bank Name: </b></td>
                                                                                <td>: <?php echo $val['bank_name']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Cheque No </b></td>
                                                                                <td>: <?php echo $val['cheque_no']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Cheque Date </b></td>
                                                                                <td>: <?php echo $val['cheque_date']->format('d-m-Y'); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        <?php if ($val['payment_type'] == 'UPI') { ?>
                                                                            <tr>
                                                                                <td><b>Transaction Id : </b><?php echo $val['transcation_id']; ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </table>
                                                                </td>
                                                                <td>
                                                                    <?php if (isset($val['active']) && $val['active'] != 'N') { ?>
                                                                        <a href="javascript:void(0)" class="btn btn-success btn-sm"> Active </a>
                                                                    <?php } else { ?>
                                                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm acc-active" data-url="<?php echo $this->Url->build(['controller' => 'Members', 'action' => 'useractive', $val['id']]) ?>" data-modified_by="<?php echo $this->request->session()->read('Auth.User.id'); ?>"> Inactive </a>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Members', 'action' => 'view', $val['id']], ['class' => 'btn btn-info fa fa-eye', 'escape' => false, 'title' => 'VIEW']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Members', 'action' => 'edit', $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                </td>     
                                                            </tr>

                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="6" style="text-align:center;"><b>No Records found </b></td></tr>';
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