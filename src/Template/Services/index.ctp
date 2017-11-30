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
                <i class="fa fa-asterisk fa-fw"></i>SERVICES
                <a href="<?php echo $this->Url->build(["controller" => "Services", "action" => "add"]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW SERVICE</button></a>
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
                    LIST SERVICES
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
                                                        <th>Service Name</th> 
                                                        <th>Category Name</th>
                                                        <th width="40%">Descriptions</th>
                                                        <th>Status</th>
                                                        <th width="17%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($services)) {
                                                        foreach ($services as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo stripslashes($val['service_name']) ?></td>
                                                                <td><?php echo stripslashes($val['category_name']) ?></td>
                                                                <td><?php echo stripslashes(substr($val['service_description'], 0, 100)); ?></td>
                                                                <td><?php echo stripslashes($val['status']) ?></td>
                                                                <td>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Services', 'action' => 'view', $val['id']], ['class' => 'btn btn-info fa fa-eye', 'escape' => false, 'title' => 'VIEW']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Services', 'action' => 'edit', $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Services', 'action' => 'delete']) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Ratecards', 'action' => 'index', $val['id']], ['class' => 'btn btn-primary fa fa-inr', 'escape' => false, 'title' => 'RATECARD']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Questions', 'action' => 'index', $val['id']], ['class' => 'btn btn-success fa fa-question', 'escape' => false, 'title' => 'QUESTIONS']); ?>
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