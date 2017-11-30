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
                <i class="fa fa-tags fa-fw"></i>SERVICE CATEGORIES
                <a href="<?php echo $this->Url->build(["controller" => "ServiceCategory", "action" => "add"]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW SERVICE CATEGORY</button></a>
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
                    LIST SERVICE CATEGORIES
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
                                                        <th>No of Services</th>
                                                        <th>Status</th>
                                                        <th>Order#</th>
                                                        <th width="12%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($categories)) {
                                                        foreach ($categories as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo stripslashes($val['name']) ?></td>
                                                                <td><?php echo stripslashes($val['service_count']); ?></td>
                                                                <td><?php echo stripslashes($val['status']) ?></td>
                                                                <td><?php echo stripslashes($val['order_id']) ?></td>    
                                                                <td>
        <!--                                                                    <a class='btn btn-info btn-sm' href='#'><i class='fa fa-lg fa-eye'></i></a>-->
                                                                    <?php echo $this->Html->link('', ['controller' => 'ServiceCategory', 'action' => 'view', $val['id']], ['class' => 'btn btn-info fa fa-eye', 'escape' => false, 'title' => 'VIEW']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'ServiceCategory', 'action' => 'edit', $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'ServiceCategory', 'action' => 'delete']) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>
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